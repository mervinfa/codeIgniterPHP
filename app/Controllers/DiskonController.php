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

    public function index()
{
    $data = [
        'discounts' => $this->discountModel->findAll(),
        'title'     => 'Kelola Data Diskon'
    ];
    
    return view('v_diskon', $data); 
}

    public function store()
    {
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

    public function update($id)
    {
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

    public function delete($id)
    {
        $this->discountModel->delete($id);
        return redirect()->to('/diskon')->with('success', 'Data diskon berhasil dihapus!');
    }
}