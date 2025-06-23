<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGuestCounts extends Migration
{
    public function up()
    {
        // First check if columns don't exist
        $fields = [];
        
        if (!$this->db->fieldExists('number_of_adults', 'guests')) {
            $fields['number_of_adults'] = [
                'type' => 'INT',
                'constraint' => 2,
                'default' => 1,
                'after' => 'room_number'
            ];
        }
        
        if (!$this->db->fieldExists('number_of_children', 'guests')) {
            $fields['number_of_children'] = [
                'type' => 'INT',
                'constraint' => 2,
                'default' => 0,
                'after' => 'number_of_adults'
            ];
        }

        // Only add columns if they don't exist
        if (!empty($fields)) {
            $this->forge->addColumn('guests', $fields);
        }
    }

    public function down()
    {
        // Check if columns exist before dropping
        if ($this->db->fieldExists('number_of_adults', 'guests')) {
            $this->forge->dropColumn('guests', 'number_of_adults');
        }
        
        if ($this->db->fieldExists('number_of_children', 'guests')) {
            $this->forge->dropColumn('guests', 'number_of_children');
        }
    }
} 