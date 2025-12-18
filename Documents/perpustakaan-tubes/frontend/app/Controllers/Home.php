<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Perpustakaan Online'
        ];
        return view('welcome_message', $data);
    }
    
    public function about()
    {
        $data = [
            'title' => 'Tentang Kami'
        ];
        return view('about', $data);
    }
    
    public function contact()
    {
        $data = [
            'title' => 'Kontak'
        ];
        return view('contact', $data);
    }
}