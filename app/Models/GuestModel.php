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
        return $this->paginate($perPage, 'default', $page);
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
} 