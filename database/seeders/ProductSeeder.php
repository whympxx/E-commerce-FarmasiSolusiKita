<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Obat Bebas (Category ID: 1)
            [
                'category_id' => 1,
                'name' => 'Paracetamol 500mg',
                'slug' => 'paracetamol-500mg',
                'description' => 'Obat pereda demam dan nyeri yang aman untuk seluruh keluarga. Efektif mengatasi sakit kepala, demam, dan nyeri ringan hingga sedang.',
                'short_description' => 'Pereda demam dan nyeri 500mg',
                'sku' => 'PCT-500-001',
                'price' => 15000,
                'sale_price' => null,
                'stock_quantity' => 150,
                'status' => 'active',
                'brand' => 'Kimia Farma',
                'manufacturer' => 'PT Kimia Farma',
                'prescription_required' => false,
                'dosage_instructions' => 'Dewasa: 1-2 tablet 3-4 kali sehari. Anak 6-12 tahun: 1/2-1 tablet 3-4 kali sehari.',
                'side_effects' => 'Jarang terjadi efek samping jika digunakan sesuai dosis. Overdosis dapat menyebabkan kerusakan hati.',
                'contraindications' => 'Hipersensitif terhadap paracetamol, gangguan fungsi hati berat.',
                'unit' => 'strip',
                'weight' => 0.05,
                'is_featured' => true
            ],
            [
                'category_id' => 1,
                'name' => 'OBH Combi Batuk Berdahak',
                'slug' => 'obh-combi-batuk-berdahak',
                'description' => 'Sirup obat batuk untuk mengatasi batuk berdahak. Mengandung ekspektoran yang membantu mengencerkan dahak.',
                'short_description' => 'Sirup obat batuk berdahak 100ml',
                'sku' => 'OBH-001-100',
                'price' => 25000,
                'sale_price' => 22000,
                'stock_quantity' => 80,
                'status' => 'active',
                'brand' => 'OBH Combi',
                'manufacturer' => 'PT Danafa',
                'prescription_required' => false,
                'dosage_instructions' => 'Dewasa: 3 sendok teh 3 kali sehari. Anak 6-12 tahun: 1 sendok teh 3 kali sehari.',
                'side_effects' => 'Mengantuk, mual, muntah (jarang).',
                'contraindications' => 'Anak di bawah 2 tahun, hipersensitif terhadap komponen obat.',
                'unit' => 'botol',
                'weight' => 0.15,
                'is_featured' => false
            ],
            [
                'category_id' => 1,
                'name' => 'Antimo Tablet',
                'slug' => 'antimo-tablet',
                'description' => 'Obat anti mabuk perjalanan yang efektif mencegah dan mengatasi mual, muntah akibat mabuk perjalanan.',
                'short_description' => 'Anti mabuk perjalanan',
                'sku' => 'ANT-TAB-001',
                'price' => 8000,
                'sale_price' => null,
                'stock_quantity' => 200,
                'status' => 'active',
                'brand' => 'Antimo',
                'manufacturer' => 'PT Kalbe Farma',
                'prescription_required' => false,
                'dosage_instructions' => 'Dewasa: 1-2 tablet 30 menit sebelum perjalanan. Anak 6-12 tahun: 1/2-1 tablet.',
                'side_effects' => 'Mengantuk, mulut kering.',
                'contraindications' => 'Anak di bawah 6 tahun, glaukoma sudut sempit.',
                'unit' => 'strip',
                'weight' => 0.03,
                'is_featured' => false
            ],

            // Obat Keras (Category ID: 2)
            [
                'category_id' => 2,
                'name' => 'Amoxicillin 500mg',
                'slug' => 'amoxicillin-500mg',
                'description' => 'Antibiotik untuk mengatasi infeksi bakteri. Efektif untuk infeksi saluran pernapasan, kulit, dan saluran kemih.',
                'short_description' => 'Antibiotik spektrum luas 500mg',
                'sku' => 'AMX-500-001',
                'price' => 45000,
                'sale_price' => null,
                'stock_quantity' => 50,
                'status' => 'active',
                'brand' => 'Kimia Farma',
                'manufacturer' => 'PT Kimia Farma',
                'prescription_required' => true,
                'dosage_instructions' => 'Dewasa: 500mg 3 kali sehari. Anak: sesuai berat badan dan petunjuk dokter.',
                'side_effects' => 'Diare, mual, ruam kulit, reaksi alergi.',
                'contraindications' => 'Alergi penisilin, gangguan fungsi ginjal berat.',
                'unit' => 'strip',
                'weight' => 0.08,
                'is_featured' => false
            ],

            // Vitamin & Suplemen (Category ID: 3)
            [
                'category_id' => 3,
                'name' => 'Vitamin C 1000mg',
                'slug' => 'vitamin-c-1000mg',
                'description' => 'Suplemen vitamin C dosis tinggi untuk meningkatkan daya tahan tubuh dan antioksidan.',
                'short_description' => 'Vitamin C 1000mg tablet effervescent',
                'sku' => 'VTC-1000-001',
                'price' => 35000,
                'sale_price' => 30000,
                'stock_quantity' => 120,
                'status' => 'active',
                'brand' => 'Redoxon',
                'manufacturer' => 'PT Bayer Indonesia',
                'prescription_required' => false,
                'dosage_instructions' => '1 tablet effervescent dilarutkan dalam segelas air, 1 kali sehari.',
                'side_effects' => 'Gangguan pencernaan jika dikonsumsi berlebihan.',
                'contraindications' => 'Batu ginjal, gangguan metabolisme besi.',
                'unit' => 'tube',
                'weight' => 0.12,
                'is_featured' => true
            ],
            [
                'category_id' => 3,
                'name' => 'Multivitamin Dewasa',
                'slug' => 'multivitamin-dewasa',
                'description' => 'Kombinasi lengkap vitamin dan mineral untuk memenuhi kebutuhan nutrisi harian dewasa.',
                'short_description' => 'Multivitamin lengkap untuk dewasa',
                'sku' => 'MVT-ADT-001',
                'price' => 55000,
                'sale_price' => null,
                'stock_quantity' => 90,
                'status' => 'active',
                'brand' => 'Centrum',
                'manufacturer' => 'PT Pfizer Indonesia',
                'prescription_required' => false,
                'dosage_instructions' => '1 tablet sehari setelah makan.',
                'side_effects' => 'Jarang, dapat menyebabkan gangguan pencernaan ringan.',
                'contraindications' => 'Hipervitaminosis, gangguan metabolisme besi.',
                'unit' => 'botol',
                'weight' => 0.25,
                'is_featured' => true
            ],

            // Perawatan Kulit (Category ID: 4)
            [
                'category_id' => 4,
                'name' => 'Betadine Antiseptik',
                'slug' => 'betadine-antiseptik',
                'description' => 'Antiseptik untuk membersihkan dan mencegah infeksi pada luka kecil, goresan, dan luka bakar ringan.',
                'short_description' => 'Antiseptik povidone iodine 60ml',
                'sku' => 'BTD-ANT-060',
                'price' => 28000,
                'sale_price' => null,
                'stock_quantity' => 75,
                'status' => 'active',
                'brand' => 'Betadine',
                'manufacturer' => 'PT Mahakam Beta Farma',
                'prescription_required' => false,
                'dosage_instructions' => 'Bersihkan area yang akan diobati, oleskan secukupnya 2-3 kali sehari.',
                'side_effects' => 'Iritasi kulit pada penggunaan berlebihan.',
                'contraindications' => 'Alergi iodine, gangguan tiroid.',
                'unit' => 'botol',
                'weight' => 0.08,
                'is_featured' => false
            ],

            // Kesehatan Bayi & Anak (Category ID: 5)
            [
                'category_id' => 5,
                'name' => 'Tempra Sirup Anak',
                'slug' => 'tempra-sirup-anak',
                'description' => 'Obat penurun demam khusus untuk anak dengan rasa yang disukai anak-anak.',
                'short_description' => 'Penurun demam anak rasa jeruk 60ml',
                'sku' => 'TMP-SRP-060',
                'price' => 32000,
                'sale_price' => null,
                'stock_quantity' => 65,
                'status' => 'active',
                'brand' => 'Tempra',
                'manufacturer' => 'PT Taisho Pharmaceutical',
                'prescription_required' => false,
                'dosage_instructions' => 'Sesuai berat badan anak. 2-3 tahun: 2.5ml, 4-5 tahun: 3.75ml, 3-4 kali sehari.',
                'side_effects' => 'Jarang terjadi jika sesuai dosis.',
                'contraindications' => 'Gangguan fungsi hati, alergi paracetamol.',
                'unit' => 'botol',
                'weight' => 0.12,
                'is_featured' => true
            ],

            // Alat Kesehatan (Category ID: 6)
            [
                'category_id' => 6,
                'name' => 'Termometer Digital',
                'slug' => 'termometer-digital',
                'description' => 'Termometer digital akurat untuk mengukur suhu tubuh dengan cepat dan tepat.',
                'short_description' => 'Termometer digital LCD',
                'sku' => 'TMR-DGT-001',
                'price' => 45000,
                'sale_price' => 40000,
                'stock_quantity' => 30,
                'status' => 'active',
                'brand' => 'Omron',
                'manufacturer' => 'PT Omron Healthcare',
                'prescription_required' => false,
                'dosage_instructions' => 'Letakkan di bawah lidah/ketiak selama 1-2 menit hingga berbunyi.',
                'side_effects' => 'Tidak ada.',
                'contraindications' => 'Tidak ada.',
                'unit' => 'pcs',
                'weight' => 0.05,
                'is_featured' => false
            ],

            // Herbal & Tradisional (Category ID: 7)
            [
                'category_id' => 7,
                'name' => 'Tolak Angin Cair',
                'slug' => 'tolak-angin-cair',
                'description' => 'Obat herbal tradisional untuk mengatasi masuk angin, perut kembung, dan mual.',
                'short_description' => 'Obat herbal masuk angin 15ml',
                'sku' => 'TLK-ANG-015',
                'price' => 8500,
                'sale_price' => null,
                'stock_quantity' => 180,
                'status' => 'active',
                'brand' => 'Tolak Angin',
                'manufacturer' => 'PT Sido Muncul',
                'prescription_required' => false,
                'dosage_instructions' => 'Dewasa: 1 sachet 2-3 kali sehari. Anak di atas 12 tahun: 1/2 sachet 2-3 kali sehari.',
                'side_effects' => 'Tidak ada efek samping yang diketahui.',
                'contraindications' => 'Anak di bawah 12 tahun.',
                'unit' => 'sachet',
                'weight' => 0.02,
                'is_featured' => false
            ],

            // Nutrisi & Diet (Category ID: 8)
            [
                'category_id' => 8,
                'name' => 'Protein Whey Vanilla',
                'slug' => 'protein-whey-vanilla',
                'description' => 'Suplemen protein whey untuk mendukung pembentukan otot dan recovery setelah olahraga.',
                'short_description' => 'Protein whey rasa vanilla 1kg',
                'sku' => 'PWY-VNL-1KG',
                'price' => 350000,
                'sale_price' => 320000,
                'stock_quantity' => 25,
                'status' => 'active',
                'brand' => 'Optimum Nutrition',
                'manufacturer' => 'Optimum Nutrition Inc',
                'prescription_required' => false,
                'dosage_instructions' => '1 scoop (30g) dicampur dengan 200ml air atau susu, 1-2 kali sehari.',
                'side_effects' => 'Gangguan pencernaan jika dikonsumsi berlebihan.',
                'contraindications' => 'Alergi susu, gangguan ginjal berat.',
                'unit' => 'tub',
                'weight' => 1.2,
                'is_featured' => true
            ]
        ];

        foreach ($products as $product) {
            // Generate expiry date (1-3 years from now)
            $product['expiry_date'] = now()->addMonths(rand(12, 36))->format('Y-m-d');
            
            // Generate batch number
            $product['batch_number'] = 'BT' . date('Y') . rand(1000, 9999);
            
            Product::create($product);
        }
    }
}
