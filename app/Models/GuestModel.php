<?php

namespace App\Models;

use CodeIgniter\Model;

class GuestModel extends Model
{
    protected $table = 'guests';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'room_number',
        'number_of_adults',
        'number_of_children',
        'check_in_date',
        'check_out_date',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[50]',
        'last_name' => 'required|min_length[2]|max_length[50]',
        'email' => 'required|valid_email|max_length[100]',
        'phone' => 'required|min_length[10]|max_length[20]',
        'room_number' => 'required|numeric|max_length[10]',
        'number_of_adults' => 'required|integer|greater_than[0]|less_than[10]',
        'number_of_children' => 'permit_empty|integer|greater_than_equal_to[0]|less_than[10]',
        'check_in_date' => 'required|valid_date',
        'check_out_date' => 'required|valid_date',
        'status' => 'required|in_list[checked_in,checked_out,reserved]'
    ];

    protected $validationMessages = [
        'first_name' => [
            'required' => 'First name is required',
            'min_length' => 'First name must be at least 2 characters long'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'min_length' => 'Last name must be at least 2 characters long'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address'
        ],
        'phone' => [
            'required' => 'Phone number is required',
            'min_length' => 'Phone number must be at least 10 characters long'
        ],
        'room_number' => [
            'required' => 'Room number is required',
            'numeric' => 'Room number must be numeric'
        ],
        'number_of_adults' => [
            'required' => 'Number of adults is required',
            'integer' => 'Number of adults must be a whole number',
            'greater_than' => 'Number of adults must be at least 1',
            'less_than' => 'Number of adults cannot exceed 9'
        ],
        'number_of_children' => [
            'integer' => 'Number of children must be a whole number',
            'greater_than_equal_to' => 'Number of children cannot be negative',
            'less_than' => 'Number of children cannot exceed 9'
        ],
        'check_in_date' => [
            'required' => 'Check-in date is required',
            'valid_date' => 'Please enter a valid check-in date'
        ],
        'check_out_date' => [
            'required' => 'Check-out date is required',
            'valid_date' => 'Please enter a valid check-out date'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Get total number of checked-in guests
    public function getTotalCheckedInGuests()
    {
        return $this->where('status', 'checked_in')->countAllResults();
    }

    // Get guests with pagination
    public function getGuests($page = 1, $perPage = 10)
    {
        try {
            log_message('debug', 'Getting guests with pagination - Page: ' . $page . ', PerPage: ' . $perPage);
            $result = $this->paginate($perPage, 'default', $page);
            log_message('debug', 'Guests query result: ' . print_r($result, true));
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error getting guests: ' . $e->getMessage());
            return [];
        }
    }

    // Get all guests without pagination
    public function getAllGuests()
    {
        try {
            log_message('debug', 'Getting all guests');
            $result = $this->findAll();
            log_message('debug', 'Found ' . count($result) . ' guests');
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error getting all guests: ' . $e->getMessage());
            return [];
        }
    }

    // Search guests
    public function searchGuests($search)
    {
        return $this->like('first_name', $search)
                    ->orLike('last_name', $search)
                    ->orLike('email', $search)
                    ->orLike('room_number', $search)
                    ->findAll();
    }

    // Get booked dates for a room
    public function getBookedDates($room_number, $exclude_guest_id = null)
    {
        $builder = $this->where('room_number', $room_number)
                       ->where('status !=', 'checked_out')
                       ->where('check_out_date >=', date('Y-m-d'));
        
        if ($exclude_guest_id) {
            $builder->where('id !=', $exclude_guest_id);
        }

        $bookings = $builder->findAll();
        
        $bookedDates = [];
        foreach ($bookings as $booking) {
            $start = strtotime($booking['check_in_date']);
            $end = strtotime($booking['check_out_date']);
            
            // Add all dates between check-in and check-out
            for ($date = $start; $date <= $end; $date = strtotime('+1 day', $date)) {
                $bookedDates[] = date('Y-m-d', $date);
            }
        }
        
        return array_unique($bookedDates);
    }

    // Check if dates are available for a room
    public function areDatesAvailable($room_number, $check_in_date, $check_out_date, $exclude_guest_id = null)
    {
        // Convert dates to Y-m-d format for consistent comparison
        $check_in_date = date('Y-m-d', strtotime($check_in_date));
        $check_out_date = date('Y-m-d', strtotime($check_out_date));

        $builder = $this->where('room_number', $room_number)
                       ->where('status !=', 'checked_out')
                       ->groupStart()
                            // Check if any existing booking overlaps with the requested dates
                            ->groupStart()
                                ->where('check_in_date <=', $check_out_date)
                                ->where('check_out_date >=', $check_in_date)
                            ->groupEnd()
                       ->groupEnd();
        
        if ($exclude_guest_id) {
            $builder->where('id !=', $exclude_guest_id);
        }

        $overlappingBookings = $builder->countAllResults();
        return $overlappingBookings === 0;
    }

    // Validate booking before insert or update
    public function validateBooking($data, $guest_id = null)
    {
        // Check if the dates are available
        if (!$this->areDatesAvailable(
            $data['room_number'],
            $data['check_in_date'],
            $data['check_out_date'],
            $guest_id
        )) {
            return false;
        }
        return true;
    }
} 