<?php

// Dashboard (untuk pemilik akun)
$routes->group('dashboard/digital-products', ['namespace' => 'Altum\Addons\DigitalProducts\Controllers'], function($routes) {
    $routes->get('/', 'Products::index');
    $routes->get('create', 'Products::create');
    $routes->post('store', 'Products::store');
    $routes->get('edit/(:num)', 'Products::edit/$1');
    $routes->post('update/(:num)', 'Products::update/$1');
    $routes->get('delete/(:num)', 'Products::delete/$1');
    
    $routes->get('orders', 'Orders::index');
});

// Public (untuk pembeli)
$routes->get('p/(:any)', 'Products::view/$1');
$routes->post('p/checkout/(:num)', 'Orders::checkout/$1');
$routes->get('download/(:any)', 'Orders::download/$1');

