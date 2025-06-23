<?php

namespace App\Controllers;

use App\Models\ServiceRequestModel;
use App\Models\ServiceTypeModel;
use App\Models\RoomModel;

class Requests extends BaseController
{
    protected $serviceRequestModel;
    protected $serviceTypeModel;
    protected $roomModel;
    
    public function __construct()
    {
        $this->serviceRequestModel = new ServiceRequestModel();
        $this->serviceTypeModel = new ServiceTypeModel();
        $this->roomModel = new RoomModel();
    }
    
    public function index()
    {
        try {
            $filters = [
                'status' => $this->request->getGet('status'),
                'service_type_id' => $this->request->getGet('service_type_id'),
                'room_number' => $this->request->getGet('room_number'),
            ];

            // Get service types
            $serviceTypes = $this->serviceTypeModel->findAll();
            
            // Debug logging
            log_message('debug', 'Service Types: ' . print_r($serviceTypes, true));

            $data = [
                'title' => 'Service Requests - Hotel Manager',
                'user' => session()->get(),
                'requests' => $this->serviceRequestModel->getRequests($filters),
                'rooms' => $this->roomModel->findAll(),
                'service_types' => $serviceTypes
            ];
            
            return view('dashboard/requests', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in Requests::index: ' . $e->getMessage());
            return view('dashboard/requests', [
                'title' => 'Service Requests - Hotel Manager',
                'user' => session()->get(),
                'requests' => [],
                'rooms' => $this->roomModel->findAll(),
                'service_types' => [],
                'error' => 'Error loading service requests: ' . $e->getMessage()
            ]);
        }
    }
    
    public function create()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $data = $this->request->getPost();
        $data['requested_by'] = session()->get('id');
        $data['status'] = 'pending';

        if ($this->serviceRequestModel->save($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Service request created successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error creating service request: ' . implode(', ', $this->serviceRequestModel->errors())
        ]);
    }
    
    public function update($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Service request ID is required'
            ]);
        }

        $data = $this->request->getPost();
        
        if ($this->serviceRequestModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Service request updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error updating service request: ' . implode(', ', $this->serviceRequestModel->errors())
        ]);
    }
    
    public function delete($id = null)
    {
        if (!$id) {
            return redirect()->to('dashboard/requests')
                           ->with('error', 'Service request ID is required');
        }

        if ($this->serviceRequestModel->delete($id)) {
            return redirect()->to('dashboard/requests')
                           ->with('success', 'Service request deleted successfully');
        }

        return redirect()->to('dashboard/requests')
                       ->with('error', 'Error deleting service request');
    }
} 