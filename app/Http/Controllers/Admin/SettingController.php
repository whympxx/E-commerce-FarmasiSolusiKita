<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Pengaturan yang boleh disimpan, beserta aturan validasinya.
     * Halaman pengaturan punya beberapa form terpisah, jadi setiap field memakai "sometimes":
     * hanya divalidasi kalau field itu ikut dikirim.
     */
    private const RULES = [
        'site_name' => 'sometimes|required|string|max:255',
        'site_description' => 'sometimes|nullable|string|max:500',
        'currency' => 'sometimes|required|string|max:10',
        'contact_email' => 'sometimes|required|email',
        'contact_phone' => 'sometimes|nullable|string|max:20',
        'address' => 'sometimes|nullable|string|max:500',
        'tax_rate' => 'sometimes|nullable|numeric|min:0|max:100',
        'shipping_cost' => 'sometimes|nullable|numeric|min:0',
        'maintenance_mode' => 'sometimes|boolean',
        'email_notifications' => 'sometimes|boolean',
        'auto_backup' => 'sometimes|boolean',
        'session_timeout' => 'sometimes|nullable|integer|min:5|max:480',
        'social_facebook' => 'sometimes|nullable|url',
        'social_instagram' => 'sometimes|nullable|url',
        'social_twitter' => 'sometimes|nullable|url',
    ];

    public function index()
    {
        // Get current settings from database
        $settings = $this->getSettings();

        // Get system statistics
        $systemStats = $this->getSystemStats();

        return view('admin.settings.index', compact('settings', 'systemStats'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), self::RULES);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Simpan hanya pengaturan yang dikenal (bukan _token dll.)
            $this->updateSettings($request->only(array_keys(self::RULES)));

            Setting::flushCache();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengaturan berhasil diperbarui!'
                ]);
            }

            return redirect()->route('admin.settings.index')
                ->with('success', 'Pengaturan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui pengaturan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengaturan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Backup database. Untuk SQLite, file database disalin ke storage/app/backups.
     */
    public function backup()
    {
        try {
            $connection = config('database.default');
            $backupDir = storage_path('app/backups');
            File::ensureDirectoryExists($backupDir);

            if (config("database.connections.{$connection}.driver") !== 'sqlite') {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup otomatis hanya tersedia untuk database SQLite. Untuk MySQL gunakan mysqldump/phpMyAdmin.'
                ], 422);
            }

            $source = config("database.connections.{$connection}.database");
            if (!File::exists($source)) {
                $source = database_path($source);
            }

            $backupPath = $backupDir . '/backup_' . date('Y-m-d_H-i-s') . '.sqlite';
            File::copy($source, $backupPath);

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dibuat di storage/app/backups',
                'file' => basename($backupPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clearCache()
    {
        try {
            Cache::flush();

            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dibersihkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getSettings()
    {
        // Get settings from database or return defaults
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();

        return array_merge([
            'site_name' => 'Farmasi Solusi Kita',
            'site_description' => 'Toko obat online terpercaya',
            'contact_email' => 'admin@farmasisolusi.com',
            'contact_phone' => '+62 21 1234 5678',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'currency' => 'IDR',
            'tax_rate' => 10,
            'shipping_cost' => 15000,
            'maintenance_mode' => false,
            'email_notifications' => true,
            'auto_backup' => true,
            'session_timeout' => 120,
            'social_facebook' => '',
            'social_instagram' => '',
            'social_twitter' => '',
        ], $settings);
    }

    private function updateSettings($data)
    {
        foreach ($data as $key => $value) {
            $exists = DB::table('settings')->where('key', $key)->exists();

            if ($exists) {
                DB::table('settings')->where('key', $key)->update([
                    'value' => $value,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('settings')->insert([
                    'key' => $key,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function getSystemStats()
    {
        return [
            'total_users' => DB::table('users')->count(),
            'total_products' => DB::table('products')->count(),
            'total_orders' => DB::table('orders')->count(),
            'total_revenue' => DB::table('orders')->where('payment_status', 'paid')->sum('total_amount'),
            'disk_usage' => $this->getDiskUsage(),
            'memory_usage' => $this->getMemoryUsage(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];
    }

    private function getDiskUsage()
    {
        $total = @disk_total_space(storage_path()) ?: 0;
        $free = @disk_free_space(storage_path()) ?: 0;
        $used = $total - $free;

        return [
            'total' => $total,
            'used' => $used,
            'free' => $free,
            'percentage' => $total > 0 ? round(($used / $total) * 100, 2) : 0
        ];
    }

    private function getMemoryUsage()
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        $limitBytes = $this->convertToBytes($memoryLimit);

        return [
            'limit' => $memoryLimit == '-1' ? 'Unlimited' : $memoryLimit,
            'usage' => $memoryUsage,
            'peak' => $memoryPeak,
            // memory_limit -1 berarti tanpa batas
            'percentage' => $limitBytes > 0 ? round(($memoryUsage / $limitBytes) * 100, 2) : 0
        ];
    }

    private function convertToBytes($memoryLimit)
    {
        $memoryLimit = trim((string) $memoryLimit);
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) $memoryLimit;

        switch ($unit) {
            case 'k': return $value * 1024;
            case 'm': return $value * 1024 * 1024;
            case 'g': return $value * 1024 * 1024 * 1024;
            default: return $value;
        }
    }
}
