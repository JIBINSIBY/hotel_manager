<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGuestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'room_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'number_of_adults' => [
                'type'       => 'INT',
                'constraint' => 2,
                'default'    => 1,
            ],
            'number_of_children' => [
                'type'       => 'INT',
                'constraint' => 2,
                'default'    => 0,
            ],
            'check_in_date' => [
                'type' => 'DATE',
            ],
            'check_out_date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['checked_in', 'checked_out', 'reserved'],
                'default'    => 'reserved',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('email');
        $this->forge->addKey('room_number');
        $this->forge->createTable('guests');
    }

    public function down()
    {
        $this->forge->dropTable('guests');
    }
} 