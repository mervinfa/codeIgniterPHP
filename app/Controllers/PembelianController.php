<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class PembelianController extends BaseController
{
    protected $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        // VALIDASI AMAN: Jika yang mengakses BUKAN admin, lempar ke home
        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('/'));
        }

        // PANGGIL HELPER NUMBER DI SINI AGAR TIDAK ERROR
        helper('number');

        // Ambil seluruh history transaksi pembelian
        $transaksi = $this->transactionModel->findAll();

        $data = [
            'title' => 'Pembelian',
            'transaksi' => $transaksi
        ];

        return view('v_pembelian', $data);
    }

    public function ubah_status($id)
    {
        // VALIDASI AMAN: Jika yang mengubah BUKAN admin, lempar ke home
        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('/'));
        }

        // Cari data transaksi berdasarkan ID
        $transaksi = $this->transactionModel->find($id);

        if ($transaksi) {
            // Ubah status: Jika 0 jadi 1, jika 1 jadi 0
            $status_baru = ($transaksi['status'] == 0) ? 1 : 0;
            
            // Simpan perubahan ke database
            $this->transactionModel->update($id, [
                'status' => $status_baru
            ]);
        }

        // Kembali ke halaman pembelian
        return redirect()->to(base_url('pembelian'));
    }
}