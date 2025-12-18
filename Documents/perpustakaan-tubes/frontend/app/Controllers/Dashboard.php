<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        // PERBAIKAN: Cek session dengan benar
        if (!session()->has('logged_in') || session()->get('logged_in') !== true) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu!');
        }
        
        $data = [
            'title' => 'Dashboard',
            'user'  => [
                'name'  => session()->get('name'),
                'email' => session()->get('email'),
                'role'  => session()->get('role')
            ]
        ];
        
        return view('dashboard/index', $data);
    }
    
    public function profile()
    {
        if (!session()->has('logged_in') || session()->get('logged_in') !== true) {
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'Profile',
            'user'  => [
                'name'  => session()->get('name'),
                'email' => session()->get('email'),
                'role'  => session()->get('role')
            ]
        ];
        
        return view('dashboard/profile', $data);
    }
    
    public function updateProfile()
    {
        if (!session()->has('logged_in') || session()->get('logged_in') !== true) {
            return redirect()->to('/login');
        }
        
        // Logika update profile
        return redirect()->to('/dashboard/profile')->with('success', 'Profile updated!');
    }
    
    public function transactions()
    {
        if (!session()->has('logged_in') || session()->get('logged_in') !== true) {
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'Transactions',
            'transactions' => []
        ];
        
        return view('dashboard/transactions', $data);
    }
    
    public function fines()
    {
        if (!session()->has('logged_in') || session()->get('logged_in') !== true) {
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'Fines',
            'fines' => []
        ];
        
        return view('dashboard/fines', $data);
    }
}