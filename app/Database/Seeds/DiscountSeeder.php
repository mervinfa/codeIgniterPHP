<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'tanggal' => '2026-07-03', 'nominal' => 100000, 'created_at' => '2026-07-03 02:57:52'],
            ['id' => 2, 'tanggal' => '2026-07-07', 'nominal' => 100000, 'created_at' => '2026-07-03 02:57:52'],
            ['id' => 3, 'tanggal' => '2026-07-08', 'nominal' => 200000, 'created_at' => '2026-07-03 02:57:52'],
            ['id' => 4, 'tanggal' => '2026-07-09', 'nominal' => 150000, 'created_at' => '2026-07-03 02:57:52'],
            ['id' => 5, 'tanggal' => '2026-07-10', 'nominal' => 250000, 'created_at' => '2026-07-03 02:57:52'],
            ['id' => 6, 'tanggal' => '2026-07-11', 'nominal' => 300000, 'created_at' => '2026-07-03 02:57:52'],
            ['id' => 7, 'tanggal' => '2026-07-12', 'nominal' => 300000, 'created_at' => '2026-07-03 02:57:52'],
            ['id' => 8, 'tanggal' => '2026-07-13', 'nominal' => 300000, 'created_at' => '2026-07-03 02:57:52'],
            ['id' => 9, 'tanggal' => '2026-07-14', 'nominal' => 300000, 'created_at' => '2026-07-03 02:57:52'],
            ['id' => 10, 'tanggal' => '2026-07-15', 'nominal' => 300000, 'created_at' => '2026-07-03 02:57:52'],
        ];

        // Memasukkan data langsung ke tabel discounts
        foreach ($data as $item) {
            $this->db->table('discounts')->insert($item);
        }
    }
}