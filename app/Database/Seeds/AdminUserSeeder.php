<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Check if admin user exists
        $adminUser = $this->db->table('users')
                             ->where('username', 'admin')
                             ->get()
                             ->getRowArray();

        if (!$adminUser) {
            // Add default admin user
            $data = [
                'username' => 'admin',
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'is_admin' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->table('users')->insert($data);
            echo "Admin user created successfully!\n";
        } else {
            // Update existing admin user to ensure it has admin privileges
            $this->db->table('users')
                     ->where('username', 'admin')
                     ->update([
                         'role' => 'admin',
                         'is_admin' => 1,
                         'updated_at' => date('Y-m-d H:i:s')
                     ]);
            echo "Admin user updated successfully!\n";
        }
    }
} 