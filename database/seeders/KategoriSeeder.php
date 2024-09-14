<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1,
                'kategori_kode' => 'SNC',
                'kategori_nama' => 'Snack',
            ],
            [
                'kategori_id' => 2,
                'kategori_kode' => 'BEV',
                'kategori_nama' => 'Beverage',
            ],
            [
                'kategori_id' => 3,
                'kategori_kode' => 'FRS',
                'kategori_nama' => 'Frozen Food',
            ],
            [
                'kategori_id' => 4,
                'kategori_kode' => 'ELEC',
                'kategori_nama' => 'Electronics',
            ],
            [
                'kategori_id' => 5,
                'kategori_kode' => 'CLTH',
                'kategori_nama' => 'Clothing',
            ]
        ];
        DB::table('m_kategori')->insert($data);
    }
}
