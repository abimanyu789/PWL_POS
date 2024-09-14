<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Barang dari Supplier Maju Jaya
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 'BRG001',
                'barang_nama' => 'Keripik Kentang',
                'harga_beli' => 10000,
                'harga_jual' => 15000,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => 'BRG002',
                'barang_nama' => 'Biskuit Coklat',
                'harga_beli' => 12000,
                'harga_jual' => 17000,
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 2,
                'barang_kode' => 'BRG003',
                'barang_nama' => 'Teh Botol',
                'harga_beli' => 3000,
                'harga_jual' => 5000,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 2,
                'barang_kode' => 'BRG004',
                'barang_nama' => 'Air Mineral',
                'harga_beli' => 2000,
                'harga_jual' => 3500,
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 3,
                'barang_kode' => 'BRG005',
                'barang_nama' => 'Nugget Ayam',
                'harga_beli' => 20000,
                'harga_jual' => 25000,
            ],

            // Barang dari Supplier Sejahtera
            [
                'barang_id' => 6,
                'kategori_id' => 1,
                'barang_kode' => 'BRG006',
                'barang_nama' => 'Kacang Goreng',
                'harga_beli' => 9000,
                'harga_jual' => 14000,
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 2,
                'barang_kode' => 'BRG007',
                'barang_nama' => 'Susu Kotak',
                'harga_beli' => 6000,
                'harga_jual' => 9000,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 3,
                'barang_kode' => 'BRG008',
                'barang_nama' => 'Es Krim Vanilla',
                'harga_beli' => 15000,
                'harga_jual' => 20000,
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 4,
                'barang_kode' => 'BRG009',
                'barang_nama' => 'Setrika Listrik',
                'harga_beli' => 100000,
                'harga_jual' => 120000,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 4,
                'barang_kode' => 'BRG010',
                'barang_nama' => 'Blender',
                'harga_beli' => 150000,
                'harga_jual' => 180000,
            ],

            // Barang dari Supplier Abadi
            [
                'barang_id' => 11,
                'kategori_id' => 5,
                'barang_kode' => 'BRG011',
                'barang_nama' => 'Kaos Oblong',
                'harga_beli' => 30000,
                'harga_jual' => 45000,
            ],
            [
                'barang_id' => 12,
                'kategori_id' => 5,
                'barang_kode' => 'BRG012',
                'barang_nama' => 'Celana Jeans',
                'harga_beli' => 70000,
                'harga_jual' => 100000,
            ],
            [
                'barang_id' => 13,
                'kategori_id' => 5,
                'barang_kode' => 'BRG013',
                'barang_nama' => 'Jaket Hoodie',
                'harga_beli' => 90000,
                'harga_jual' => 130000,
            ],
            [
                'barang_id' => 14,
                'kategori_id' => 1,
                'barang_kode' => 'BRG014',
                'barang_nama' => 'Kerupuk Udang',
                'harga_beli' => 11000,
                'harga_jual' => 16000,
            ],
            [
                'barang_id' => 15,
                'kategori_id' => 2,
                'barang_kode' => 'BRG015',
                'barang_nama' => 'Kopi Instan',
                'harga_beli' => 5000,
                'harga_jual' => 8000,
            ],
        ];

        DB::table('m_barang')->insert($data);
    }
}
