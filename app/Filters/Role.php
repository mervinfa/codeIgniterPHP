<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Role implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah user sudah login
        if (!session()->has('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        // 2. Jika sudah login, cek apakah role-nya admin
        if (session()->get('role') == 'admin') {
            // Jika admin, redirect paksa ke halaman Home
            return redirect()->to(site_url('/')); 
        }
        
        // (Jika guest, biarkan saja agar bisa mengakses halaman contact)
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan saja, tidak ada yang perlu dilakukan setelah halaman dimuat
    }
}