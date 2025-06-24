<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\RoomModel;

class AutoUpdateRoomStatuses extends BaseCommand
{
    protected $group       = 'Hotel';
    protected $name        = 'room:auto-update';
    protected $description = 'Continuously updates room statuses in the background';
    protected $usage       = 'room:auto-update [interval_seconds]';
    protected $arguments   = ['interval_seconds' => 'Interval between updates in seconds (default: 60)'];

    public function run(array $params)
    {
        // Get interval from params or use default (60 seconds)
        $interval = isset($params[0]) ? (int)$params[0] : 60;
        
        CLI::write('Starting automatic room status updates...', 'yellow');
        CLI::write("Update interval: {$interval} seconds", 'yellow');
        
        while (true) {
            try {
                $roomModel = new RoomModel();
                $roomModel->updateAllRoomStatuses();
                CLI::write(date('Y-m-d H:i:s') . ' - Room statuses updated successfully!', 'green');
            } catch (\Exception $e) {
                CLI::error(date('Y-m-d H:i:s') . ' - Error updating room statuses: ' . $e->getMessage());
                log_message('error', 'Error in auto room status update: ' . $e->getMessage());
            }
            
            // Sleep for the specified interval
            sleep($interval);
        }
    }
} 