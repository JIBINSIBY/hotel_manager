<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GuestSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'room_number' => '101',
            'number_of_adults' => 2,
            'number_of_children' => 1,
            'check_in_date' => date('Y-m-d', strtotime('+1 day')),
            'check_out_date' => date('Y-m-d', strtotime('+3 days')),
            'status' => 'reserved',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('guests')->insert($data);
    }
} 