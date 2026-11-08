<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Alur utama toko: keranjang -> checkout -> pesanan pelanggan -> kelola pesanan di admin.
 */
class ShopFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private User $admin;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::insert([
            ['key' => 'shipping_cost', 'value' => '15000', 'type' => 'integer', 'group' => 'payment'],
            ['key' => 'tax_rate', 'value' => '10', 'type' => 'integer', 'group' => 'payment'],
        ]);
        Setting::flushCache();

        $this->customer = User::factory()->create();
        $this->admin = User::factory()->create(['is_admin' => true]);

        $category = Category::create(['name' => 'Obat Bebas', 'slug' => 'obat-bebas', 'is_active' => true]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Paracetamol 500mg',
            'slug' => 'paracetamol-500mg',
            'sku' => 'PCT-500',
            'price' => 10000,
            'sale_price' => 8000,
            'stock_quantity' => 10,
            'manage_stock' => true,
            'status' => 'active',
            'unit' => 'strip',
        ]);
    }

    private function checkoutData(array $overrides = []): array
    {
        return array_merge([
            'customer_name' => 'Budi',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Melati No. 1',
            'shipping_city' => 'Jakarta',
            'shipping_state' => 'DKI Jakarta',
            'shipping_postal_code' => '12345',
            'payment_method' => 'cod',
        ], $overrides);
    }

    private function placeOrder(int $quantity = 2): Order
    {
        Cart::create(['user_id' => $this->customer->id, 'product_id' => $this->product->id, 'quantity' => $quantity]);

        $this->actingAs($this->customer)->post('/checkout', $this->checkoutData())->assertRedirect();

        return Order::latest('id')->firstOrFail();
    }

    public function test_customer_pages_load(): void
    {
        $this->actingAs($this->customer);

        $this->get('/dashboard')->assertOk()->assertSee('Riwayat Pesanan')->assertSee('pharma-3d.js', false);
        $this->get('/orders')->assertOk()->assertSee('Riwayat Pesanan');
        $this->get('/profile')->assertOk()->assertSee($this->customer->email);
        $this->get('/profile/edit')->assertOk();
    }

    public function test_customer_dashboard_tracks_active_order(): void
    {
        $order = $this->placeOrder();

        $this->actingAs($this->customer)->get('/dashboard')
            ->assertOk()
            ->assertSee('Lacak Pesanan Aktif')
            ->assertSee($order->order_number);

        $this->get("/orders/{$order->id}")->assertOk()->assertSee($order->order_number);
    }

    public function test_admin_profile_page_uses_admin_layout(): void
    {
        $this->actingAs($this->admin)->get('/profile/edit')->assertOk()->assertSee('Admin Panel');
    }

    public function test_checkout_creates_order_with_correct_totals_and_reduces_stock(): void
    {
        Cart::create(['user_id' => $this->customer->id, 'product_id' => $this->product->id, 'quantity' => 2]);

        $this->actingAs($this->customer)->get('/checkout')->assertOk()->assertSee('Buat Pesanan');

        $response = $this->actingAs($this->customer)->post('/checkout', $this->checkoutData());

        $order = Order::firstOrFail();
        $response->assertRedirect(route('checkout.success', $order));

        // 2 x 8000 = 16000, diskon 4000, ongkir 15000, pajak 10% x 16000 = 1600
        $this->assertEquals(20000, $order->subtotal);
        $this->assertEquals(4000, $order->discount_amount);
        $this->assertEquals(15000, $order->shipping_amount);
        $this->assertEquals(1600, $order->tax_amount);
        $this->assertEquals(32600, $order->total_amount);
        $this->assertSame('Jakarta', $order->shipping_city);
        $this->assertSame('Budi', $order->customer_name);
        $this->assertCount(1, $order->orderItems);

        $this->assertSame(8, $this->product->fresh()->stock_quantity);
        $this->assertSame(0, Cart::count());

        $this->get(route('checkout.success', $order))->assertOk()->assertSee($order->order_number);
        $this->get(route('orders.show', $order))->assertOk();
    }

    public function test_checkout_rejects_quantity_above_stock(): void
    {
        Cart::create(['user_id' => $this->customer->id, 'product_id' => $this->product->id, 'quantity' => 50]);

        $this->actingAs($this->customer)
            ->post('/checkout', $this->checkoutData())
            ->assertSessionHasErrors('stock');

        $this->assertSame(0, Order::count());
        $this->assertSame(10, $this->product->fresh()->stock_quantity);
    }

    public function test_customer_can_cancel_pending_order_and_stock_returns(): void
    {
        $order = $this->placeOrder(3);
        $this->assertSame(7, $this->product->fresh()->stock_quantity);

        $this->actingAs($this->customer)->patch(route('orders.cancel', $order))->assertRedirect();

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(10, $this->product->fresh()->stock_quantity);
    }

    public function test_customer_cannot_see_other_customers_order(): void
    {
        $order = $this->placeOrder();
        $other = User::factory()->create();

        $this->actingAs($other)->get(route('orders.show', $order))->assertForbidden();
    }

    public function test_guest_cart_is_moved_to_account_on_login(): void
    {
        $this->post('/cart', ['product_id' => $this->product->id, 'quantity' => 2])->assertJson(['success' => true]);
        $this->assertSame(1, Cart::whereNull('user_id')->count());

        // Bawa cookie session yang sama seperti browser
        $guestSessionId = Cart::whereNull('user_id')->value('session_id');

        $this->withCookie(config('session.cookie'), $guestSessionId)
            ->post('/login', ['email' => $this->customer->email, 'password' => 'password']);

        $this->assertAuthenticatedAs($this->customer);
        $this->assertSame(2, (int) Cart::where('user_id', $this->customer->id)->sum('quantity'));
        $this->assertSame(0, Cart::whereNull('user_id')->count());
    }

    public function test_admin_order_pages_load_with_an_order(): void
    {
        $order = $this->placeOrder();
        $this->actingAs($this->admin);

        $this->get('/admin/orders')->assertOk()->assertSee($order->customer_name);
        $this->get("/admin/orders/{$order->id}")->assertOk()->assertSee('Jl. Melati No. 1');
        $this->get("/admin/orders/{$order->id}/edit")->assertOk();
        $this->get("/admin/orders/{$order->id}/invoice")->assertOk();
        $this->get("/admin/orders/{$order->id}/shipping-label")->assertOk();

        $export = $this->get('/admin/orders/export');
        $export->assertOk();
        $this->assertStringContainsString($order->order_number, $export->streamedContent());
    }

    public function test_admin_can_update_order_and_cancelling_restores_stock(): void
    {
        $order = $this->placeOrder(2);

        $this->actingAs($this->admin)->put("/admin/orders/{$order->id}", array_merge($this->checkoutData(), [
            'status' => 'shipped',
            'payment_status' => 'paid',
            'payment_method' => 'cod',
            'tracking_number' => 'JNE123',
            'billing_address' => 'Jl. Melati No. 1',
            'billing_city' => 'Jakarta',
            'billing_postal_code' => '12345',
        ]))->assertRedirect(route('admin.orders.show', $order));

        $order->refresh();
        $this->assertSame('shipped', $order->status);
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('JNE123', $order->tracking_number);
        $this->assertNotNull($order->shipped_at);

        // Status lewat form biasa di halaman detail -> redirect, bukan JSON
        $this->patch("/admin/orders/{$order->id}/status", ['status' => 'cancelled'])->assertRedirect();
        $this->assertSame(10, $this->product->fresh()->stock_quantity);

        // Status lewat AJAX -> JSON
        $this->patchJson("/admin/orders/{$order->id}/payment-status", ['payment_status' => 'refunded'])
            ->assertJson(['success' => true]);
    }

    public function test_admin_customer_verification_toggle_works(): void
    {
        $this->customer->forceFill(['email_verified_at' => null])->save();

        $this->actingAs($this->admin)->patch("/admin/customers/{$this->customer->id}/toggle-verification");
        $this->assertNotNull($this->customer->fresh()->email_verified_at);

        $this->patch("/admin/customers/{$this->customer->id}/toggle-verification");
        $this->assertNull($this->customer->fresh()->email_verified_at);
    }

    public function test_admin_exports_are_reachable(): void
    {
        $this->actingAs($this->admin);

        $this->get('/admin/customers/export')->assertOk();
        $this->get('/admin/reports/export?type=sales&format=excel')->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->get('/admin/reports/export?type=inventory&format=pdf')->assertOk()->assertSee('Laporan Inventaris');
    }

    public function test_product_with_checkboxes_can_be_saved(): void
    {
        $this->actingAs($this->admin)->post('/admin/products', [
            'category_id' => $this->product->category_id,
            'name' => 'Vitamin C',
            'sku' => 'VITC-1',
            'price' => 25000,
            'stock_quantity' => 5,
            'status' => 'active',
            'manage_stock' => '1',
            'is_featured' => '1',
            'prescription_required' => '1',
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.products.index'));

        $this->assertTrue(Product::where('sku', 'VITC-1')->first()->is_featured);
    }

    public function test_ordered_product_cannot_be_deleted(): void
    {
        $this->placeOrder();

        $this->actingAs($this->admin)->delete("/admin/products/{$this->product->id}")->assertSessionHas('error');
        $this->assertNotNull($this->product->fresh());
    }

    public function test_settings_sections_can_be_saved_and_maintenance_turned_off(): void
    {
        $this->actingAs($this->admin);

        $this->post('/admin/settings/update', ['tax_rate' => 5, 'shipping_cost' => 20000])->assertSessionHasNoErrors();
        $this->assertEquals(5, Setting::get('tax_rate'));

        $this->post('/admin/settings/update', ['maintenance_mode' => '1', 'email_notifications' => '0', 'auto_backup' => '0', 'session_timeout' => 60]);
        $this->assertEquals(1, Setting::get('maintenance_mode'));
        $this->assertNull(Setting::where('key', '_token')->first());

        // Saat maintenance: pengunjung diblokir, admin tetap bisa masuk
        $this->get('/admin/dashboard')->assertOk();
        auth()->logout();
        $this->get('/products')->assertStatus(503);

        $this->actingAs($this->admin)->post('/admin/settings/update', ['maintenance_mode' => '0', 'email_notifications' => '0', 'auto_backup' => '0']);
        auth()->logout();
        $this->get('/products')->assertOk();
    }

    public function test_all_pages_load_when_an_order_exists(): void
    {
        $order = $this->placeOrder();
        $categoryId = $this->product->category_id;

        $this->actingAs($this->admin);
        foreach ([
            '/admin/dashboard', '/admin/products', '/admin/products/create', "/admin/products/{$this->product->id}",
            "/admin/products/{$this->product->id}/edit", '/admin/categories', '/admin/categories/create',
            "/admin/categories/{$categoryId}", "/admin/categories/{$categoryId}/edit", '/admin/orders/create',
            '/admin/customers', '/admin/customers/create', "/admin/customers/{$this->customer->id}",
            "/admin/customers/{$this->customer->id}/edit", '/admin/reports', '/admin/reports?report_type=products',
            '/admin/reports?report_type=customers', '/admin/reports?report_type=inventory', '/admin/reports/chart-data',
            '/admin/settings',
        ] as $url) {
            $this->get($url)->assertOk();
        }

        // CSS halaman pesanan admin benar-benar ikut dirender (dulu hilang karena @section vs @stack)
        $this->get('/admin/orders')->assertSee('.status-select-modern', false)->assertSee('admin-orders.js', false);

        auth()->logout();
        foreach (['/', '/products', '/products/search?q=para', '/category/obat-bebas', '/product/paracetamol-500mg', '/cart'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_inactive_products_are_not_shown_on_home(): void
    {
        $this->product->update(['status' => 'inactive', 'is_featured' => true]);

        $this->get('/')->assertOk()->assertDontSee('Paracetamol 500mg');
    }
}
