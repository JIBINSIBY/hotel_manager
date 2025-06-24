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
    $routes->get('/', 'Dashboard::index');
    
    // Guest management routes
    $routes->get('guests', 'Guests::index');
    $routes->post('guests/add', 'Guests::add');
    $routes->get('guests/edit/(:num)', 'Guests::edit/$1');
    $routes->post('guests/edit/(:num)', 'Guests::edit/$1');
    $routes->get('guests/delete/(:num)', 'Guests::delete/$1');
    
    // Room management routes
    $routes->get('rooms', 'Rooms::index');
    $routes->post('rooms/add', 'Rooms::add');
    $routes->get('rooms/edit/(:num)', 'Rooms::edit/$1');
    $routes->post('rooms/edit/(:num)', 'Rooms::edit/$1');
    $routes->get('rooms/delete/(:num)', 'Rooms::delete/$1');
    
    // Room availability routes
    $routes->get('rooms/available', 'Dashboard::getAvailableRooms');
    $routes->get('rooms/booked-dates', 'Dashboard::getBookedDates');

    // Service request routes
    $routes->group('requests', function($routes) {
        $routes->get('/', 'Requests::index');
        $routes->post('create', 'Requests::create');
        $routes->post('update/(:num)', 'Requests::update/$1');
        $routes->get('delete/(:num)', 'Requests::delete/$1');
    });
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
});