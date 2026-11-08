<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Obat Bebas',
                'slug' => 'obat-bebas',
                'description' => 'Obat-obatan yang dapat dibeli tanpa resep dokter untuk mengobati gejala ringan',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Obat Keras',
                'slug' => 'obat-keras',
                'description' => 'Obat-obatan yang memerlukan resep dokter untuk pembelian dan penggunaan',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Vitamin & Suplemen',
                'slug' => 'vitamin-suplemen',
                'description' => 'Vitamin, mineral, dan suplemen untuk menjaga kesehatan tubuh',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Perawatan Kulit',
                'slug' => 'perawatan-kulit',
                'description' => 'Produk perawatan kulit, krim, lotion, dan kosmetik kesehatan',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Kesehatan Bayi & Anak',
                'slug' => 'kesehatan-bayi-anak',
                'description' => 'Produk kesehatan khusus untuk bayi dan anak-anak',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Alat Kesehatan',
                'slug' => 'alat-kesehatan',
                'description' => 'Alat-alat kesehatan untuk monitoring dan perawatan',
                'is_active' => true,
                'sort_order' => 6
            ],
            [
                'name' => 'Herbal & Tradisional',
                'slug' => 'herbal-tradisional',
                'description' => 'Obat-obatan herbal dan tradisional untuk kesehatan alami',
                'is_active' => true,
                'sort_order' => 7
            ],
            [
                'name' => 'Nutrisi & Diet',
                'slug' => 'nutrisi-diet',
                'description' => 'Produk nutrisi, protein, dan suplemen diet',
                'is_active' => true,
                'sort_order' => 8
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
