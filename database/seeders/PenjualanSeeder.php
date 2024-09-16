<?php

namespace Database\Seeders;

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
            ['penjualan_id' => 1, 'user_id' => 1, 'pembeli' => 'Pembeli 1', 'penjualan_kode' => 'PJL1', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 2, 'user_id' => 2, 'pembeli' => 'Pembeli 2', 'penjualan_kode' => 'PJL2', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 3, 'user_id' => 3, 'pembeli' => 'Pembeli 3', 'penjualan_kode' => 'PJL3', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 4, 'user_id' => 1, 'pembeli' => 'Pembeli 4', 'penjualan_kode' => 'PJL4', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 5, 'user_id' => 2, 'pembeli' => 'Pembeli 5', 'penjualan_kode' => 'PJL5', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 6, 'user_id' => 3, 'pembeli' => 'Pembeli 6', 'penjualan_kode' => 'PJL6', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 7, 'user_id' => 1, 'pembeli' => 'Pembeli 7', 'penjualan_kode' => 'PJL7', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 8, 'user_id' => 2, 'pembeli' => 'Pembeli 8', 'penjualan_kode' => 'PJL8', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 9, 'user_id' => 3, 'pembeli' => 'Pembeli 9', 'penjualan_kode' => 'PJL9', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 10, 'user_id' => 1, 'pembeli' => 'Pembeli 10', 'penjualan_kode' => 'PJL10', 'penjualan_tanggal' => now()],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}
