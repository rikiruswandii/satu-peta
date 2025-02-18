<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Sector;

class DatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agencies = [
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

        foreach ($agencies as $agency) {
            Sector::create([
                'user_id' => 2,
                'name' => $agency,
                'slug' => Str::slug($agency),
            ]);
        }
    }
}
