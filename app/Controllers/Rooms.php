<?php

namespace App\Controllers;

use App\Models\RoomModel;

class Rooms extends BaseController
{
    protected $roomModel;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Room Management',
            'rooms' => $this->roomModel->findAll()
        ];

        return view('dashboard/rooms', $data);
    }

    public function add()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('dashboard/rooms');
        }

        $data = $this->request->getPost();
        
        if ($this->roomModel->save($data)) {
            return redirect()->to('dashboard/rooms')
                ->with('success', 'Room added successfully');
        } else {
            return redirect()->to('dashboard/rooms')
                ->with('error', 'Failed to add room. Please check the form.')
                ->withInput()
                ->with('validation', $this->roomModel->errors());
        }
    }

    public function edit($id = null)
    {
        // Handle POST request (update)
        if ($this->request->is('post')) {
            // Validate room exists
            $room = $this->roomModel->find($id);
            if (!$room) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => ['Room not found']
                    ]);
                }
                return redirect()->to('dashboard/rooms')->with('error', 'Room not found');
            }

            // Get POST data
            $data = $this->request->getPost();
            
            // Add the ID for validation
            $data['id'] = $id;
            
            // Log the received data
            log_message('debug', 'Received update data for room ' . $id . ': ' . json_encode($data));

            try {
                // Use update instead of save to ensure we're updating
                if ($this->roomModel->update($id, $data)) {
                    log_message('debug', 'Successfully updated room ' . $id);
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Room updated successfully'
                        ]);
                    }
                    return redirect()->to('dashboard/rooms')
                        ->with('success', 'Room updated successfully');
                } else {
                    log_message('error', 'Failed to update room ' . $id . '. Model errors: ' . json_encode($this->roomModel->errors()));
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'errors' => $this->roomModel->errors()
                        ]);
                    }
                    return redirect()->to('dashboard/rooms')
                        ->with('error', 'Failed to update room')
                        ->withInput()
                        ->with('validation', $this->roomModel->errors());
                }
            } catch (\Exception $e) {
                log_message('error', 'Error updating room ' . $id . ': ' . $e->getMessage());
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => ['Failed to update room: ' . $e->getMessage()]
                    ]);
                }
                return redirect()->to('dashboard/rooms')
                    ->with('error', 'Failed to update room');
            }
        }

        // Handle GET request (fetch room data)
        if (!$id) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => ['Room ID not provided']
                ]);
            }
            return redirect()->to('dashboard/rooms');
        }

        $room = $this->roomModel->find($id);
        if (!$room) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => ['Room not found']
                ]);
            }
            return redirect()->to('dashboard/rooms')
                ->with('error', 'Room not found');
        }

        return $this->response->setJSON($room);
    }

    public function delete($id = null)
    {
        if ($this->roomModel->delete($id)) {
            return redirect()->to('dashboard/rooms')
                ->with('success', 'Room deleted successfully');
        }
        
        return redirect()->to('dashboard/rooms')
            ->with('error', 'Failed to delete room');
    }
} 