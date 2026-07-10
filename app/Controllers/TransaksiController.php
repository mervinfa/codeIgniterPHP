<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\DiscountModel;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $discountModel;

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = service('cart');
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel(); 
        $this->discountModel = new DiscountModel();
    }

    // Menampilkan isi keranjang belanja asli tokomu
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');

        $items = $this->cart->contents();
        $total = $this->cart->total(); 

        // Ambil diskon aktif hari ini berdasarkan tanggal server
        $today = date('Y-m-d');
        $activeDiscount = $this->discountModel->where('tanggal', $today)->first();

        $data = [
            'items'          => $items,
            'total'          => $total,
            'activeDiscount' => $activeDiscount,
            'title'          => 'Keranjang Belanja'
        ];

        return view('v_keranjang', $data);
    }

    // SOAL 4: Fungsi Utama Proses Checkout dari Tombol "Selesai Belanja"
// Fungsi ini CUMA menampilkan halaman formulir checkout & ringkasan belanja
    public function checkout()
    {
        date_default_timezone_set('Asia/Jakarta');
        $items = $this->cart->contents();
        $total = $this->cart->total();

        if (empty($items)) {
            return redirect()->to(base_url('keranjang'))->with('error', 'Keranjang belanja kosong');
        }

        // Ambil data diskon aktif hari ini
        $today = date('Y-m-d');
        $activeDiscount = $this->discountModel->where('tanggal', $today)->first();

        $data = [
            'items'          => $items,
            'total'          => $total,
            'activeDiscount' => $activeDiscount,
            'title'          => 'Checkout'
        ];

        // Membuka file view checkout milikmu (pastikan nama filenya sesuai, misal v_checkout)
        return view('v_checkout', $data); 
    }

    // Fungsi ini yang memproses data ke database saat tombol "Buat Pesanan" diklik
    public function buy()
    {
        $items = $this->cart->contents();
        if (empty($items)) {
            return redirect()->to(base_url('keranjang'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Ambil nominal diskon hari ini
        date_default_timezone_set('Asia/Jakarta');
        $today = date('Y-m-d');
        $activeDiscount = $this->discountModel->where('tanggal', $today)->first();
        $nominalDiskon = isset($activeDiscount) ? $activeDiscount['nominal'] : 0;

        // 2. Hitung total harga belanja yang sudah dipotong diskon per item
        $subtotalSetelahDiskon = 0;
        foreach ($items as $item) {
            $hargaDiskon = $item['price'] - $nominalDiskon;
            if ($hargaDiskon < 0) $hargaDiskon = 0;
            $subtotalSetelahDiskon += ($hargaDiskon * $item['qty']);
        }

        // 3. Tambahkan ongkir dari inputan form
        $ongkir = (int) $this->request->getPost('ongkir');
        $grandTotal = $subtotalSetelahDiskon + $ongkir;

        // 4. Masukkan ke tabel transaksi sesuai kolom database aslimu
        $transaction = [
            'username'    => $this->request->getPost('username'),
            'alamat'      => $this->request->getPost('alamat'),
            'ongkir'      => $ongkir,
            'total_harga' => $grandTotal, 
            'status'      => 0, // 0 = Belum Bayar
        ];

        $this->transactionModel->insert($transaction);
        $transactionId = $this->transactionModel->getInsertID();

        // 5. Masukkan ke detail transaksi
        foreach ($items as $item) {
            $this->transactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'product_id'     => $item['id'],
                'jumlah'         => $item['qty'],
                'diskon'         => $nominalDiskon, 
                'subtotal_harga' => ($item['qty'] * $item['price']) - ($nominalDiskon * $item['qty']) 
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('checkout'))->with('error', 'Gagal memproses checkout');
        }

        // Kosongkan keranjang setelah pesanan resmi dibuat
        $this->cart->destroy();

        return redirect()->to(base_url('history'))->with('success', 'Transaksi berhasil dibuat!');
    }

    public function cart_add()
    {
        $this->cart->insert([
            'id'      => $this->request->getPost('id'),
            'qty'     => 1,
            'price'   => $this->request->getPost('harga'),
            'name'    => $this->request->getPost('nama'),
            'options' => [
                'foto' => $this->request->getPost('foto')
            ]
        ]);
        
        return redirect()->to(base_url('/'));
    } 

    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $item) {
            $qty = $this->request->getPost('qty' . $i++);
            $this->cart->update([
                'rowid' => $item['rowid'],
                'qty'   => $qty
            ]);
        }
        return redirect()->to(base_url('keranjang'))->with('success', 'Keranjang berhasil diperbarui');
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
        return redirect()->to(base_url('keranjang'))->with('success', 'Produk dihapus');
    }

    public function cart_clear()
    {
        $this->cart->destroy();
        return redirect()->to(base_url('keranjang'));
    }

    public function history()
    {
        $username = session()->get('username'); 
        $transactions = $this->transactionModel->where('username', $username)->findAll();
        
        $transactionIds = array_column($transactions, 'id');
        $products = [];
        if (!empty($transactionIds)) {
            $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);
        }

        $data = [
            'username'      => $username,
            'transactions'  => $transactions,
            'products'      => $products
        ]; 

        return view('v_history', $data);
    }

    // SOAL 5: Aksi Admin mengubah status transaksi pembayaran
    public function updateStatus($id)
    {
        $statusBaru = $this->request->getPost('status_pembayaran');
        $this->transactionModel->update($id, [
            'status' => $statusBaru
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    // =================================================================
    // FUNGSI API RAJAONGKIR (Yang Sempat Hilang)
    // =================================================================
    
    public function destinations()
{
    $search = $this->request->getGet('search'); // mengambil apa yang diketik user
    $service = new \App\Services\RajaOngkirService();
    
    // Panggil service API
    $response = $service->getDestination($search ?? '');

    // API Komerce biasanya membungkus datanya di dalam key 'data'
    $dataFromApi = $response['data'] ?? [];

    $formattedResults = [];
    foreach ($dataFromApi as $item) {
        $formattedResults[] = [
            'id'   => $item['id'], // ID destinasi untuk disimpan ke database
            'text' => $item['label'] ?? $item['subdistrict_name'] ?? '' // Teks yang muncul di dropdown select2
        ];
    }

    // WAJIB dikembalikan dalam bentuk json dengan key 'results'
    return $this->response->setJSON([
        'results' => $formattedResults
    ]);
}

    public function costs()
    {
        $origin = '64999';
        $destination = $this->request->getGet('destination');
        $weight = 1000; // Asumsi berat default 1000 gram (1kg)
        $courier = 'jne'; 

        $service = new \App\Services\RajaOngkirService();
        $response = $service->getCost($origin, $destination, $weight, $courier);

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'service'     => $item['service'],
                'description' => $item['description'],
                'cost'        => $item['cost'],
                'etd'         => $item['etd']
            ];
        }

        return $this->response->setJSON($results);
    }
}