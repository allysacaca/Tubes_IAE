<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // PERBAIKAN: Cek session dengan benar
        $isLoggedIn = session()->get('logged_in');
        if ($isLoggedIn === true) {
            return redirect()->to('/dashboard');
        }
        
        $data = [
            'title' => 'Login - Perpustakaan Online'
        ];
        return view('auth/login', $data);
    }
    
    public function process_login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        // Validasi input
        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Email dan password harus diisi!');
        }
        
        try {
            // Gunakan model dengan try-catch
            $model = new UserModel();
            $user = $model->where('email', $email)->first();
            
            if ($user) {
                // Verifikasi password
                if (password_verify($password, $user['password'])) {
                    // Set session
                    $sessionData = [
                        'user_id'   => $user['id'],
                        'name'      => $user['name'],
                        'email'     => $user['email'],
                        'role'      => $user['role'] ?? 'member',
                        'logged_in' => true
                    ];
                    session()->set($sessionData);
                    
                    return redirect()->to('/dashboard')->with('success', 'Login berhasil!');
                }
            }
            
            return redirect()->back()->withInput()->with('error', 'Email atau password salah!');
            
        } catch (\Exception $e) {
            // Debug: Tampilkan error
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function register()
    {
        $isLoggedIn = session()->get('logged_in');
        if ($isLoggedIn === true) {
            return redirect()->to('/dashboard');
        }
        
        $data = [
            'title' => 'Register - Perpustakaan Online'
        ];
        return view('auth/register', $data);
    }
    
    public function process_register()
    {
        $rules = [
            'name'      => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        try {
            $model = new UserModel();
            
            $data = [
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'     => 'member'
            ];
            
            $model->save($data);
            
            return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
            
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function logout()
    {
        // Hapus semua data session
        session()->destroy();
        
        // Redirect ke home
        return redirect()->to('/')->with('success', 'Logout berhasil!');
    }
}