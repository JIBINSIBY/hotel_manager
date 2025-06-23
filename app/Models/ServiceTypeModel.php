<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceTypeModel extends Model
{
    protected $table = 'service_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'description'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'description' => 'permit_empty|max_length[255]'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Service type name is required',
            'min_length' => 'Service type name must be at least 3 characters long',
            'max_length' => 'Service type name cannot exceed 100 characters'
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    // Get all active service types
    public function getActiveTypes()
    {
        return $this->findAll();
    }
} 