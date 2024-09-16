<?php

namespace Database\Seeders;

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
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'BRG1', 'barang_nama' => 'Barang 1', 'harga_beli' => 10000, 'harga_jual' => 15000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'BRG2', 'barang_nama' => 'Barang 2', 'harga_beli' => 20000, 'harga_jual' => 25000],
            ['barang_id' => 3, 'kategori_id' => 2, 'barang_kode' => 'BRG3', 'barang_nama' => 'Barang 3', 'harga_beli' => 30000, 'harga_jual' => 35000],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'BRG4', 'barang_nama' => 'Barang 4', 'harga_beli' => 40000, 'harga_jual' => 45000],
            ['barang_id' => 5, 'kategori_id' => 3, 'barang_kode' => 'BRG5', 'barang_nama' => 'Barang 5', 'harga_beli' => 50000, 'harga_jual' => 55000],
            ['barang_id' => 6, 'kategori_id' => 1, 'barang_kode' => 'BRG6', 'barang_nama' => 'Barang 6', 'harga_beli' => 60000, 'harga_jual' => 65000],
            ['barang_id' => 7, 'kategori_id' => 1, 'barang_kode' => 'BRG7', 'barang_nama' => 'Barang 7', 'harga_beli' => 70000, 'harga_jual' => 75000],
            ['barang_id' => 8, 'kategori_id' => 2, 'barang_kode' => 'BRG8', 'barang_nama' => 'Barang 8', 'harga_beli' => 80000, 'harga_jual' => 85000],
            ['barang_id' => 9, 'kategori_id' => 2, 'barang_kode' => 'BRG9', 'barang_nama' => 'Barang 9', 'harga_beli' => 90000, 'harga_jual' => 95000],
            ['barang_id' => 10, 'kategori_id' => 3, 'barang_kode' => 'BRG10', 'barang_nama' => 'Barang 10', 'harga_beli' => 100000, 'harga_jual' => 105000],
            ['barang_id' => 11, 'kategori_id' => 1, 'barang_kode' => 'BRG11', 'barang_nama' => 'Barang 11', 'harga_beli' => 110000, 'harga_jual' => 115000],
            ['barang_id' => 12, 'kategori_id' => 1, 'barang_kode' => 'BRG12', 'barang_nama' => 'Barang 12', 'harga_beli' => 120000, 'harga_jual' => 125000],
            ['barang_id' => 13, 'kategori_id' => 2, 'barang_kode' => 'BRG13', 'barang_nama' => 'Barang 13', 'harga_beli' => 130000, 'harga_jual' => 135000],
            ['barang_id' => 14, 'kategori_id' => 2, 'barang_kode' => 'BRG14', 'barang_nama' => 'Barang 14', 'harga_beli' => 140000, 'harga_jual' => 145000],
            ['barang_id' => 15, 'kategori_id' => 3, 'barang_kode' => 'BRG15', 'barang_nama' => 'Barang 15', 'harga_beli' => 150000, 'harga_jual' => 155000],
        ];

        DB::table('m_barang')->insert($data);
    }
}
