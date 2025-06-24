<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class GuestController extends ResourceController
{
    use ResponseTrait;
    
    protected $modelName = 'App\Models\GuestModel';
    protected $format    = 'json';

    // GET /api/guests
    public function index()
    {
        $guests = $this->model->getAllGuests();
        return $this->respond(['data' => $guests], 200, 'Guests retrieved successfully');
    }

    // GET /api/guests/{id}
    public function show($id = null)
    {
        $guest = $this->model->find($id);
        
        if ($guest === null) {
            return $this->failNotFound('Guest not found');
        }

        return $this->respond(['data' => $guest], 200, 'Guest retrieved successfully');
    }

    // POST /api/guests
    public function create()
    {
        $rules = [
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

        $json = $this->request->getJSON();
        if (empty($json)) {
            return $this->failValidationErrors('No data received');
        }

        // Convert JSON object to array
        $data = json_decode(json_encode($json), true);

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Validate booking availability
        if (!$this->model->validateBooking($data)) {
            return $this->fail('Room is not available for the selected dates', 400);
        }
        
        try {
            if ($this->model->insert($data)) {
                $guest = $this->model->find($this->model->getInsertID());
                return $this->respondCreated(['data' => $guest], 'Guest created successfully');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating guest: ' . $e->getMessage());
            return $this->failServerError('An error occurred while creating the guest: ' . $e->getMessage());
        }

        return $this->failServerError('Failed to create guest');
    }

    // PUT /api/guests/{id}
    public function update($id = null)
    {
        if ($id === null) {
            return $this->failNotFound('No guest ID provided');
        }

        $rules = [
            'first_name' => 'permit_empty|min_length[2]|max_length[50]',
            'last_name' => 'permit_empty|min_length[2]|max_length[50]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'phone' => 'permit_empty|min_length[10]|max_length[20]',
            'room_number' => 'permit_empty|numeric|max_length[10]',
            'number_of_adults' => 'permit_empty|integer|greater_than[0]|less_than[10]',
            'number_of_children' => 'permit_empty|integer|greater_than_equal_to[0]|less_than[10]',
            'check_in_date' => 'permit_empty|valid_date',
            'check_out_date' => 'permit_empty|valid_date',
            'status' => 'permit_empty|in_list[checked_in,checked_out,reserved]'
        ];

        $json = $this->request->getJSON();
        if (empty($json)) {
            return $this->failValidationErrors('No data received');
        }

        // Convert JSON object to array
        $data = json_decode(json_encode($json), true);

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Validate booking availability if dates or room number is being updated
        if (isset($data['room_number']) || isset($data['check_in_date']) || isset($data['check_out_date'])) {
            // Get current guest data to merge with updates
            $currentGuest = $this->model->find($id);
            $bookingData = array_merge($currentGuest, $data);
            
            if (!$this->model->validateBooking($bookingData, $id)) {
                return $this->fail('Room is not available for the selected dates', 400);
            }
        }
        
        try {
            if ($this->model->update($id, $data)) {
                $guest = $this->model->find($id);
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Guest updated successfully',
                    'data' => $guest
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating guest: ' . $e->getMessage());
            return $this->failServerError('An error occurred while updating the guest: ' . $e->getMessage());
        }

        return $this->failServerError('Failed to update guest');
    }

    // DELETE /api/guests/{id}
    public function delete($id = null)
    {
        if ($id === null) {
            return $this->failNotFound('No guest ID provided');
        }

        try {
            if ($this->model->delete($id)) {
                return $this->respondDeleted([
                    'status' => 'success',
                    'message' => 'Guest deleted successfully'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting guest: ' . $e->getMessage());
            return $this->failServerError('An error occurred while deleting the guest: ' . $e->getMessage());
        }

        return $this->failServerError('Failed to delete guest');
    }
} 