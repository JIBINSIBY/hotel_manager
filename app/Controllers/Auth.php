<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        $data = [
            'title' => 'Hotel Manager - Login'
        ];
        
        return view('auth/login', $data);
    }
    
    public function authenticate()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $user = $this->userModel->where('username', $username)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            // Set session data with fallbacks for missing fields
            $sessionData = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'] ?? '',
                'role' => $user['role'] ?? 'staff',
                'is_admin' => isset($user['is_admin']) ? (bool)$user['is_admin'] : ($user['role'] ?? '') === 'admin',
                'name' => $user['name'] ?? $user['username'],
                'isLoggedIn' => true
            ];
            
            session()->set($sessionData);
            
            return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . ($user['name'] ?? $user['username']));
        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password');
        }
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully');
    }
}