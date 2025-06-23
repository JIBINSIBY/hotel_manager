<?php

namespace App\Controllers;

use App\Models\GuestModel;
use App\Models\RoomModel;

class Dashboard extends BaseController
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
            'title' => 'Dashboard - Hotel Manager',
            'user' => session()->get(),
            'total_guests' => $this->guestModel->getTotalCheckedInGuests(),
            'total_rooms' => $this->roomModel->countAll(),
            'available_rooms' => $this->roomModel->where('status', 'available')->countAllResults(),
            'occupied_rooms' => $this->roomModel->where('status', 'occupied')->countAllResults(),
            'maintenance_rooms' => $this->roomModel->where('status', 'maintenance')->countAllResults()
        ];
        
        return view('dashboard/index', $data);
    }
    
    public function guests()
    {
        try {
            // Debug log before query
            log_message('debug', 'Starting guests method');
            
            // Get database connection
            $db = \Config\Database::connect();
            log_message('debug', 'Database connected: ' . print_r($db->getDatabase(), true));
            
            // Get all guests using the new method
            $guests = $this->guestModel->getAllGuests();
            
            // Debug log the results
            log_message('debug', 'Guests data: ' . print_r($guests, true));
            
            if (empty($guests)) {
                log_message('warning', 'No guests found in database');
            }
            
            $data = [
                'title' => 'Guest Management - Hotel Manager',
                'user' => session()->get(),
                'guests' => $guests,
                'available_rooms' => $this->roomModel->getAvailableRoomNumbers()
            ];
            
            // Debug logging
            log_message('debug', 'View data: ' . print_r($data, true));
            
            // Add debug information to the view
            $data['debug_info'] = [
                'database' => $db->getDatabase(),
                'guest_count' => count($guests),
                'is_logged_in' => session()->get('isLoggedIn'),
                'user_id' => session()->get('id')
            ];
            
            return view('dashboard/guests', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in guests method: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Return view with error message
            return view('dashboard/guests', [
                'title' => 'Guest Management - Hotel Manager',
                'user' => session()->get(),
                'guests' => [],
                'available_rooms' => $this->roomModel->getAvailableRoomNumbers(),
                'error' => 'Error loading guest data: ' . $e->getMessage()
            ]);
        }
    }
    
    public function getGuest($id = null)
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
                'message' => 'Guest ID is required'
            ]);
        }

        $guest = $this->guestModel->find($id);
        if (!$guest) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Guest not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'guest' => $guest
        ]);
    }
    
    public function addGuest()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $data = $this->request->getPost();

        // Validate booking availability
        if (!$this->guestModel->validateBooking($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'The selected room is not available for these dates. Please choose different dates or room.'
            ]);
        }

        if ($this->guestModel->save($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Guest added successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error adding guest: ' . implode(', ', $this->guestModel->errors())
            ]);
        }
    }
    
    public function updateGuest($id = null)
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
                'message' => 'Guest ID is required'
            ]);
        }

        // Check if guest exists
        $guest = $this->guestModel->find($id);
        if (!$guest) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Guest not found'
            ]);
        }

        $data = $this->request->getPost();

        // Set default value for number_of_children if not provided
        if (!isset($data['number_of_children']) || $data['number_of_children'] === '') {
            $data['number_of_children'] = 0;
        }

        // Validate booking availability
        if (!$this->guestModel->validateBooking($data, $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'The selected room is not available for these dates. Please choose different dates or room.'
            ]);
        }

        try {
            if ($this->guestModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Guest updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error updating guest: ' . implode(', ', $this->guestModel->errors())
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to update guest: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update guest. Please try again.'
            ]);
        }
    }
    
    public function deleteGuest($id = null)
    {
        if (!$id) {
            return redirect()->to('dashboard/guests')
                           ->with('error', 'Invalid guest ID');
        }

        // Check if guest exists
        $guest = $this->guestModel->find($id);
        if (!$guest) {
            return redirect()->to('dashboard/guests')
                           ->with('error', 'Guest not found');
        }

        try {
            $this->guestModel->delete($id);
            return redirect()->to('dashboard/guests')
                           ->with('success', 'Guest deleted successfully');
        } catch (\Exception $e) {
            return redirect()->to('dashboard/guests')
                           ->with('error', 'Failed to delete guest. Please try again.');
        }
    }

    public function getAvailableRooms()
    {
        $roomModel = new RoomModel();
        $rooms = $roomModel->getAvailableRooms();
        return $this->response->setJSON($rooms);
    }

    public function getBookedDates()
    {
        $room_number = $this->request->getGet('room_number');
        $guest_id = $this->request->getGet('guest_id');

        if (!$room_number) {
            return $this->response->setJSON(['error' => 'Room number is required']);
        }

        $guestModel = new GuestModel();
        $bookedDates = $guestModel->getBookedDates($room_number, $guest_id);
        
        return $this->response->setJSON(['bookedDates' => $bookedDates]);
    }
} 