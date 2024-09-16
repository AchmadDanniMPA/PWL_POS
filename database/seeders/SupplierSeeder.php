<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['supplier_id' => 1, 'supplier_kode' => 'SUP1', 'supplier_nama' => 'Supplier 1', 'supplier_alamat' => 'Alamat 1'],
            ['supplier_id' => 2, 'supplier_kode' => 'SUP2', 'supplier_nama' => 'Supplier 2', 'supplier_alamat' => 'Alamat 2'],
            ['supplier_id' => 3, 'supplier_kode' => 'SUP3', 'supplier_nama' => 'Supplier 3', 'supplier_alamat' => 'Alamat 3'],
        ];

        DB::table('m_supplier')->insert($data);
    }
}
