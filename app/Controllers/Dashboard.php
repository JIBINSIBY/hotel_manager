<?php

namespace App\Controllers;

use App\Models\GuestModel;

class Dashboard extends BaseController
{
    protected $guestModel;
    
    public function __construct()
    {
        $this->guestModel = new GuestModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Dashboard - Hotel Manager',
            'user' => session()->get(),
            'total_guests' => $this->guestModel->getTotalCheckedInGuests()
        ];
        
        return view('dashboard/index', $data);
    }
    
    public function guests()
    {
        $data = [
            'title' => 'Guest Management - Hotel Manager',
            'user' => session()->get(),
            'guests' => $this->guestModel->findAll()
        ];
        
        return view('dashboard/guests', $data);
    }
    
    public function addGuest()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('dashboard/guests');
        }

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'required|min_length[10]|max_length[20]',
            'room_number' => 'required|max_length[10]',
            'check_in_date' => 'required|valid_date',
            'check_out_date' => 'required|valid_date',
            'status' => 'required|in_list[checked_in,checked_out,reserved]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', $this->validator->listErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'room_number' => $this->request->getPost('room_number'),
            'check_in_date' => $this->request->getPost('check_in_date'),
            'check_out_date' => $this->request->getPost('check_out_date'),
            'status' => $this->request->getPost('status')
        ];

        try {
            $this->guestModel->insert($data);
            return redirect()->to('dashboard/guests')
                           ->with('success', 'Guest added successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to add guest. Please try again.');
        }
    }
    
    public function updateGuest($id = null)
    {
        if (!$this->request->is('post') || !$id) {
            return redirect()->to('dashboard/guests');
        }

        // Check if guest exists
        $guest = $this->guestModel->find($id);
        if (!$guest) {
            return redirect()->to('dashboard/guests')
                           ->with('error', 'Guest not found');
        }

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'required|min_length[10]|max_length[20]',
            'room_number' => 'required|max_length[10]',
            'check_in_date' => 'required|valid_date',
            'check_out_date' => 'required|valid_date',
            'status' => 'required|in_list[checked_in,checked_out,reserved]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', $this->validator->listErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'room_number' => $this->request->getPost('room_number'),
            'check_in_date' => $this->request->getPost('check_in_date'),
            'check_out_date' => $this->request->getPost('check_out_date'),
            'status' => $this->request->getPost('status')
        ];

        try {
            $this->guestModel->update($id, $data);
            return redirect()->to('dashboard/guests')
                           ->with('success', 'Guest updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update guest. Please try again.');
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
    
    public function requests()
    {
        $data = [
            'title' => 'Service Requests - Hotel Manager',
            'user' => session()->get()
        ];
        
        return view('dashboard/requests', $data);
    }
} 