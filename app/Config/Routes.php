<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('produk', ['filter' => 'auth'], function ($routes) { 
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
    $routes->get('download', 'ProdukController::download');
});

$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');

});

$routes->get('checkout', 'TransaksiController::checkout', ['filter' => 'auth']);
$routes->post('checkout/buy', 'TransaksiController::buy', ['filter' => 'auth']);$routes->get('history', 'TransaksiController::history', ['filter' => 'auth']);

$routes->get('ajax/destinations','TransaksiController::destinations');
$routes->get('ajax/costs','TransaksiController::costs', ['filter' => 'auth']);

$routes->resource('api/products', ['controller' => 'Api\ProdukController']);
$routes->resource('api/discounts', ['controller' => 'Api\DiskonController']);

$routes->get('api/transactions', 'Api\TransaksiController::index');

// Tulis terpisah di bagian bawah file Routes.php kamu:
$routes->get('diskon', 'DiskonController::index');
$routes->post('diskon/store', 'DiskonController::store');
$routes->post('diskon/update/(:num)', 'DiskonController::update/$1');
$routes->get('diskon/delete/(:num)', 'DiskonController::delete/$1');

$routes->group('pembelian', ['filter' => 'auth'], function ($routes) { // <-- Ganti jadi auth
    $routes->get('', 'PembelianController::index');
    $routes->post('ubah-status/(:num)', 'PembelianController::ubah_status/$1');
});

$routes->get('keranjang', 'TransaksiController::index', ['filter' => 'auth']);
$routes->get('contact', 'Home::contact', ['filter' => 'role']);