<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Tags\Tag;

class DatasetSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi dataset ke dalam tags dengan type 'map' dan 'article'.
     */
    public function run(): void
    {
        // Tags untuk tipe 'map'
        $mapDatasets = [
            'Lingkungan Terbangun',
            'Hipsografi',
            'Toponimi',
            'Utilitas',
            'Vegetasi',
            'Batas Wilayah',
            'Dataset Khusus',
            'Transportasi',
            'Kadaster',
            'Kebencanaan',
            'Referensi Spasial',
            'Hidrologi',
            'Geologi',
            'Tanah',
            'Jalan',
            'Sungai',
        ];

        foreach ($mapDatasets as $dataset) {
            Tag::findOrCreate($dataset, 'map'); // ✅ Menyimpan dengan type 'map'
        }

        // Tags untuk tipe 'article'
        $articleCategories = [
            'Berita Pemetaan',
            'Teknologi GIS',
            'Kebijakan Geospasial',
            'Tutorial Peta Digital',
            'Analisis Data Spasial',
            'Remote Sensing',
            'Sistem Informasi Geografis',
            'Data Geospasial Terbuka',
            'Tautan',
        ];

        foreach ($articleCategories as $category) {
            Tag::findOrCreate($category, 'article'); // ✅ Menyimpan dengan type 'article'
        }
    }
}
