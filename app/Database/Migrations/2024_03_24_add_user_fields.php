<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserFields extends Migration
{
    public function up()
    {
        // Add is_admin column if it doesn't exist
        if (!$this->db->fieldExists('is_admin', 'users')) {
            $this->forge->addColumn('users', [
                'is_admin' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'after' => 'role'
                ]
            ]);
        }

        // Add name column if it doesn't exist
        if (!$this->db->fieldExists('name', 'users')) {
            $this->forge->addColumn('users', [
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'after' => 'username'
                ]
            ]);
        }

        // Update existing admin user
        $this->db->table('users')
                 ->where('username', 'admin')
                 ->update([
                     'role' => 'admin',
                     'is_admin' => 1,
                     'name' => 'Administrator'
                 ]);
    }

    public function down()
    {
        // Remove the columns if they exist
        if ($this->db->fieldExists('is_admin', 'users')) {
            $this->forge->dropColumn('users', 'is_admin');
        }
        if ($this->db->fieldExists('name', 'users')) {
            $this->forge->dropColumn('users', 'name');
        }
    }
} 