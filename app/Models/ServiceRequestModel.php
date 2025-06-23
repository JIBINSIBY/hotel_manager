<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceRequestModel extends Model
{
    protected $table = 'service_requests';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'service_type_id',
        'room_number',
        'description',
        'status',
        'requested_by',
        'assigned_to'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'service_type_id' => 'required|integer',
        'room_number' => 'required|max_length[10]',
        'description' => 'permit_empty|max_length[1000]',
        'status' => 'required|in_list[pending,in_progress,completed]',
        'requested_by' => 'required|integer',
        'assigned_to' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'service_type_id' => [
            'required' => 'Service type is required',
            'integer' => 'Invalid service type'
        ],
        'room_number' => [
            'required' => 'Room number is required',
            'max_length' => 'Room number cannot exceed 10 characters'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Invalid status'
        ]
    ];

    public function getRequests($filters = [])
    {
        $builder = $this->select('service_requests.*, service_types.name as service_name, 
                                requester.name as requester_name, assignee.name as assignee_name')
            ->join('service_types', 'service_types.id = service_requests.service_type_id')
            ->join('users as requester', 'requester.id = service_requests.requested_by')
            ->join('users as assignee', 'assignee.id = service_requests.assigned_to', 'left');

        // Apply filters
        if (!empty($filters['status'])) {
            $builder->where('service_requests.status', $filters['status']);
        }
        if (!empty($filters['service_type_id'])) {
            $builder->where('service_requests.service_type_id', $filters['service_type_id']);
        }
        if (!empty($filters['room_number'])) {
            $builder->where('service_requests.room_number', $filters['room_number']);
        }

        return $builder->orderBy('service_requests.created_at', 'DESC')->findAll();
    }
} 