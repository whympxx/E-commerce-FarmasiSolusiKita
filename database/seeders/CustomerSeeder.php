<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@email.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@email.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'password' => Hash::make('password'),
                'email_verified_at' => null, // Belum verifikasi
                'is_admin' => false,
            ],
            [
                'name' => 'Dewi Sartika',
                'email' => 'dewi.sartika@email.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
            ],
            [
                'name' => 'Muhammad Fajar',
                'email' => 'muhammad.fajar@email.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
            ],
            [
                'name' => 'Nina Kartika',
                'email' => 'nina.kartika@email.com',
                'password' => Hash::make('password'),
                'email_verified_at' => null, // Belum verifikasi
                'is_admin' => false,
            ],
            [
                'name' => 'Rudi Hermawan',
                'email' => 'rudi.hermawan@email.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
            ],
            [
                'name' => 'Lina Marlina',
                'email' => 'lina.marlina@email.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
            ],
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus.setiawan@email.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
            ],
            [
                'name' => 'Yuni Safitri',
                'email' => 'yuni.safitri@email.com',
                'password' => Hash::make('password'),
                'email_verified_at' => null, // Belum verifikasi
                'is_admin' => false,
            ],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }

        $this->command->info('Customer data seeded successfully!');
        $this->command->info('Total customers created: ' . count($customers));
    }
} 