<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Farmasi Solusi Kita',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nama website/toko',
            ],
            [
                'key' => 'site_description',
                'value' => 'Toko obat online terpercaya dengan berbagai produk farmasi berkualitas',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Deskripsi website',
            ],
            [
                'key' => 'contact_email',
                'value' => 'admin@farmasisolusi.com',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Email kontak utama',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+62 21 1234 5678',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Nomor telepon kontak',
            ],
            [
                'key' => 'address',
                'value' => 'Jl. Contoh No. 123, Jakarta Selatan, DKI Jakarta 12345',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Alamat lengkap',
            ],
            [
                'key' => 'currency',
                'value' => 'IDR',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Mata uang default',
            ],
            [
                'key' => 'tax_rate',
                'value' => '10',
                'type' => 'integer',
                'group' => 'payment',
                'description' => 'Persentase pajak (%)',
            ],
            [
                'key' => 'shipping_cost',
                'value' => '15000',
                'type' => 'integer',
                'group' => 'payment',
                'description' => 'Biaya pengiriman default (IDR)',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Mode maintenance website',
            ],
            [
                'key' => 'email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Aktifkan notifikasi email',
            ],
            [
                'key' => 'auto_backup',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Backup otomatis database',
            ],
            [
                'key' => 'session_timeout',
                'value' => '120',
                'type' => 'integer',
                'group' => 'system',
                'description' => 'Timeout session dalam menit',
            ],
            [
                'key' => 'logo_url',
                'value' => '/images/logo.png',
                'type' => 'string',
                'group' => 'general',
                'description' => 'URL logo website',
            ],
            [
                'key' => 'favicon_url',
                'value' => '/images/favicon.ico',
                'type' => 'string',
                'group' => 'general',
                'description' => 'URL favicon website',
            ],
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/farmasisolusi',
                'type' => 'string',
                'group' => 'social',
                'description' => 'URL Facebook',
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/farmasisolusi',
                'type' => 'string',
                'group' => 'social',
                'description' => 'URL Instagram',
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/farmasisolusi',
                'type' => 'string',
                'group' => 'social',
                'description' => 'URL Twitter',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
} 