<?php

namespace App\Controllers;

use App\Models\GuestModel;
use App\Models\RoomModel;

class Guests extends BaseController
{
    protected $guestModel;
    protected $roomModel;

    public function __construct()
    {
        $this->guestModel = new GuestModel();
        $this->roomModel = new RoomModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Guest Management',
            'guests' => $this->guestModel->getAllGuests(),
            'rooms' => $this->roomModel->findAll()
        ];

        return view('dashboard/guests', $data);
    }

    public function add()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('dashboard/guests');
        }

        $data = $this->request->getPost();
        
        if ($this->guestModel->save($data)) {
            return redirect()->to('dashboard/guests')
                ->with('success', 'Guest added successfully');
        } else {
            return redirect()->to('dashboard/guests')
                ->with('error', 'Failed to add guest. Please check the form.')
                ->withInput()
                ->with('validation', $this->guestModel->errors());
        }
    }

    public function edit($id = null)
    {
        // Handle POST request (update)
        if ($this->request->is('post')) {
            // Validate guest exists
            $guest = $this->guestModel->find($id);
            if (!$guest) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => ['Guest not found']
                    ]);
                }
                return redirect()->to('dashboard/guests')->with('error', 'Guest not found');
            }

            // Get POST data
            $data = $this->request->getPost();
            
            // Add the ID for validation
            $data['id'] = $id;

            try {
                if ($this->guestModel->update($id, $data)) {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Guest updated successfully'
                        ]);
                    }
                    return redirect()->to('dashboard/guests')
                        ->with('success', 'Guest updated successfully');
                } else {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'errors' => $this->guestModel->errors()
                        ]);
                    }
                    return redirect()->to('dashboard/guests')
                        ->with('error', 'Failed to update guest')
                        ->withInput()
                        ->with('validation', $this->guestModel->errors());
                }
            } catch (\Exception $e) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => ['Failed to update guest: ' . $e->getMessage()]
                    ]);
                }
                return redirect()->to('dashboard/guests')
                    ->with('error', 'Failed to update guest');
            }
        }

        // Handle GET request (fetch guest data)
        if (!$id) {
            return redirect()->to('dashboard/guests');
        }

        $guest = $this->guestModel->find($id);
        if (!$guest) {
            return redirect()->to('dashboard/guests')
                ->with('error', 'Guest not found');
        }

        // If it's an AJAX request, return JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($guest);
        }

        // Otherwise, show the edit form view
        $data = [
            'title' => 'Edit Guest',
            'guest' => $guest,
            'rooms' => $this->roomModel->findAll()
        ];

        return view('dashboard/guests/edit', $data);
    }

    public function delete($id = null)
    {
        if ($this->guestModel->delete($id)) {
            return redirect()->to('dashboard/guests')
                ->with('success', 'Guest deleted successfully');
        }
        
        return redirect()->to('dashboard/guests')
            ->with('error', 'Failed to delete guest');
    }
} 