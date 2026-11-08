<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Terapkan pengaturan dari halaman Admin > Settings
        try {
            config([
                'app.name' => Setting::get('site_name', 'Farmasi Solusi Kita'),
                'session.lifetime' => (int) Setting::get('session_timeout', config('session.lifetime')),
            ]);
        } catch (\Throwable $e) {
            // Tabel settings belum ada (mis. saat migrate pertama kali)
        }
    }
}
