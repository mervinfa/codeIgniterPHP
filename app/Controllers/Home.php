<?php

namespace App\Controllers;

use App\Models\ProductModel; 
use App\Models\DiscountModel;

class Home extends BaseController
{
    protected $productModel;

    function __construct(){
        helper(['number', 'form']);
    $this->productModel = new ProductModel();
    }

    public function index()
{
    // 1. WAJIB SET TIMEZONE BIAR SAMA DENGAN JAM LAPTOPMU (WIB)
    date_default_timezone_set('Asia/Jakarta');

    $productModel  = new ProductModel();
    $discountModel = new DiscountModel();

    // 2. Mengambil tanggal hari ini (Akan menghasilkan '2026-07-09')
    $today = date('Y-m-d'); 

    // 3. Cari diskon yang cocok dengan tanggal hari ini
    $activeDiscount = $discountModel->where('tanggal', $today)->first();

    // 4. Kirim datanya ke view v_home
    $data = [
        'products'       => $productModel->findAll(),
        'activeDiscount' => $activeDiscount 
    ];

    return view('v_home', $data);
}

     public function contact(): string
    {
       return view('v_contact');
    }
}
