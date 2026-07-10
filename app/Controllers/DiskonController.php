<?php

namespace App\Controllers;

use App\Models\DiscountModel;

class DiskonController extends BaseController
{
    protected $discountModel;

    public function __construct()
    {
        helper('number'); 
        
        $this->discountModel = new DiscountModel();
    }

    // 1. Tampilkan Semua Data
    public function index()
{
    $data = [
        'discounts' => $this->discountModel->findAll(),
        'title'     => 'Kelola Data Diskon'
    ];
    
    // Karena filternya langsung di Views, panggil seperti ini:
    return view('v_diskon', $data); 
}

    // 2. Simpan Diskon Baru (Tambah)
    public function store()
    {
        // Validasi: tanggal wajib diisi dan tidak boleh kembar di tabel discounts
        $rules = [
            'tanggal' => 'required|valid_date|is_unique[discounts.tanggal]',
            'nominal' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->discountModel->save([
            'tanggal' => $this->request->getPost('tanggal'),
            'nominal' => $this->request->getPost('nominal'),
        ]);

        return redirect()->to('/diskon')->with('success', 'Data diskon berhasil ditambahkan!');
    }

    // 3. Update Nominal Diskon (Edit)
    public function update($id)
    {
        // Hanya memvalidasi nominal karena tanggal dikunci (readonly)
        $rules = [
            'nominal' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $this->discountModel->update($id, [
            'nominal' => $this->request->getPost('nominal')
        ]);

        return redirect()->to('/diskon')->with('success', 'Nominal diskon berhasil diperbarui!');
    }

    // 4. Hapus Data Diskon
    public function delete($id)
    {
        $this->discountModel->delete($id);
        return redirect()->to('/diskon')->with('success', 'Data diskon berhasil dihapus!');
    }
}