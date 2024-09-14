<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'user_id' => 3,
                'pembeli' => 'Budi Santoso',
                'penjualan_kode' => 'TRX001',
                'penjualan_tanggal' => '2024-09-01 10:15:00',
            ],
            [
                'penjualan_id' => 2,
                'user_id' => 3,
                'pembeli' => 'Siti Aminah',
                'penjualan_kode' => 'TRX002',
                'penjualan_tanggal' => '2024-09-01 12:45:00',
            ],
            [
                'penjualan_id' => 3,
                'user_id' => 3,
                'pembeli' => 'Andi Saputra',
                'penjualan_kode' => 'TRX003',
                'penjualan_tanggal' => '2024-09-02 09:30:00',
            ],
            [
                'penjualan_id' => 4,
                'user_id' => 3,
                'pembeli' => 'Rina Supriyadi',
                'penjualan_kode' => 'TRX004',
                'penjualan_tanggal' => '2024-09-02 14:20:00',
            ],
            [
                'penjualan_id' => 5,
                'user_id' => 3,
                'pembeli' => 'Doni Kusuma',
                'penjualan_kode' => 'TRX005',
                'penjualan_tanggal' => '2024-09-03 11:00:00',
            ],
            [
                'penjualan_id' => 6,
                'user_id' => 3,
                'pembeli' => 'Ahmad Ramdani',
                'penjualan_kode' => 'TRX006',
                'penjualan_tanggal' => '2024-09-03 15:30:00',
            ],
            [
                'penjualan_id' => 7,
                'user_id' => 3,
                'pembeli' => 'Lestari Widodo',
                'penjualan_kode' => 'TRX007',
                'penjualan_tanggal' => '2024-09-04 10:45:00',
            ],
            [
                'penjualan_id' => 8,
                'user_id' => 3,
                'pembeli' => 'Agus Suryanto',
                'penjualan_kode' => 'TRX008',
                'penjualan_tanggal' => '2024-09-04 13:10:00',
            ],
            [
                'penjualan_id' => 9,
                'user_id' => 3,
                'pembeli' => 'Eko Prasetyo',
                'penjualan_kode' => 'TRX009',
                'penjualan_tanggal' => '2024-09-05 09:40:00',
            ],
            [
                'penjualan_id' => 10,
                'user_id' => 3,
                'pembeli' => 'Dewi Kartika',
                'penjualan_kode' => 'TRX010',
                'penjualan_tanggal' => '2024-09-05 17:00:00',
            ],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}
