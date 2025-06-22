<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Home::index');

// Authentication routes
$routes->get('login', 'Auth::login');
$routes->post('auth/authenticate', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');

// Admin Panel routes (protected)
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('', 'Dashboard::index');
    
    // Guest management routes
    $routes->get('guests', 'Dashboard::guests');
    $routes->post('guests/add', 'Dashboard::addGuest');
    $routes->post('guests/update/(:num)', 'Dashboard::updateGuest/$1');
    $routes->get('guests/delete/(:num)', 'Dashboard::deleteGuest/$1');
    
    // Service request routes
    $routes->get('requests', 'Dashboard::requests');
});

// API routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    // Authentication API
    $routes->post('login', 'Auth::login');
    
    // Guests API (protected)
    $routes->group('guests', ['filter' => 'auth'], function($routes) {
        $routes->get('', 'Guests::index');
        $routes->get('(:num)', 'Guests::show/$1');
        $routes->post('', 'Guests::create');
        $routes->put('(:num)', 'Guests::update/$1');
        $routes->delete('(:num)', 'Guests::delete/$1');
    });
    
    // Service Requests API (protected)
    $routes->group('requests', ['filter' => 'auth'], function($routes) {
        $routes->get('', 'Requests::index');
        $routes->post('', 'Requests::create');
        $routes->put('(:num)/status', 'Requests::updateStatus/$1');
    });
});