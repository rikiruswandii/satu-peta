<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\RegionalAgency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agencies = [
            'DINKES',
            'DISDIK',
            'DPMPTSP',
            'SETDA',
            'DLH',
            'DISKANNAK',
            'DISHUB',
            'SEKWAN',
            'BKAD',
            'DISPANGTAN',
            'DKUPP',
            'DPMD',
            'DISKOMINFO',
            'INSPEKTORAT',
            'DPUTR',
            'BAPENDA',
            'KESBANGPOL',
        ];

        foreach ($agencies as $agency) {
            RegionalAgency::create([
                'name' => $agency,
                'slug' => Str::slug($agency),
            ]);
        }
    }
}