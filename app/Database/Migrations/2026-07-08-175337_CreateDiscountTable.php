<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiscountTable extends Migration
{
    public function up()
    {
        // Menyusun struktur field tabel discounts sesuai Soal 1
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type'       => 'DATE',
                'unique'     => true, // Mencegah data kembar untuk tanggal yang sama
            ],
            'nominal' => [
                'type'       => 'DOUBLE',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        // Menentukan Primary Key
        $this->forge->addKey('id', true);

        // Membuat tabel bernama 'discounts'
        $this->forge->createTable('discounts');
    }

    public function down()
    {
        // Menghapus tabel jika dilakukan rollback
        $this->forge->dropTable('discounts');
    }
}