<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'room_number',
        'room_type',
        'ac_type',
        'bed_type',
        'rate_per_day',
        'status',
        'description'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero',
        'room_number' => 'required|min_length[1]|max_length[10]|is_unique[rooms.room_number,id,{id}]',
        'room_type' => 'required|in_list[Standard,Deluxe,Suite,Family]',
        'ac_type' => 'required|in_list[AC,NON-AC]',
        'bed_type' => 'required|in_list[Single,Double,Twin,King]',
        'rate_per_day' => 'required|numeric',
        'status' => 'required|in_list[available,occupied,maintenance]'
    ];

    protected $validationMessages = [
        'id' => [
            'is_natural_no_zero' => 'Invalid room ID'
        ],
        'room_number' => [
            'required' => 'Room number is required',
            'is_unique' => 'This room number is already in use'
        ]
    ];

    protected $skipValidation = false;

    // Get available rooms with their details
    public function getAvailableRooms()
    {
        return $this->where('status', 'available')
                    ->orderBy('room_number', 'ASC')
                    ->findAll();
    }

    // Check if a room is available for specific dates
    public function isRoomAvailable($roomNumber, $checkInDate, $checkOutDate)
    {
        // First check if the room exists and is available
        $room = $this->where('room_number', $roomNumber)->first();
        if (!$room || $room['status'] !== 'available') {
            return false;
        }

        // Then check if there are any overlapping bookings
        $db = \Config\Database::connect();
        $query = $db->table('guests')
            ->where('room_number', $roomNumber)
            ->where('status !=', 'checked_out')
            ->groupStart()
                ->where('check_in_date <=', $checkOutDate)
                ->where('check_out_date >=', $checkInDate)
            ->groupEnd();

        return $query->countAllResults() === 0;
    }

    // Get available room numbers for dropdown
    public function getAvailableRoomNumbers()
    {
        $rooms = $this->getAvailableRooms();
        $roomList = [];
        foreach ($rooms as $room) {
            $roomList[$room['room_number']] = "Room {$room['room_number']} - {$room['room_type']} ({$room['bed_type']}, {$room['ac_type']})";
        }
        return $roomList;
    }
} 