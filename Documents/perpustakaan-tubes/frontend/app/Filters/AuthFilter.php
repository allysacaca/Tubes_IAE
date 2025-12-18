<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to('/login');
        }
        
        // Jika ada role/argument tambahan
        if (!empty($arguments)) {
            $userRole = session()->get('role');
            
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/')->with('error', 'Anda tidak memiliki akses!');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}