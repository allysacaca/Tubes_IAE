<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

// ========== AUTHENTICATION ROUTES ==========
// Login
$routes->get('/login', 'Auth::login');
$routes->post('/login/process', 'Auth::process_login');

// Register  
$routes->get('/register', 'Auth::register');
$routes->post('/register/process', 'Auth::process_register');

// Logout
$routes->get('/logout', 'Auth::logout');

// ========== DASHBOARD ROUTES ==========
// Dashboard routes (controller akan handle auth sendiri)
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/dashboard/profile', 'Dashboard::profile');
$routes->post('/dashboard/profile/update', 'Dashboard::updateProfile');
$routes->get('/dashboard/transactions', 'Dashboard::transactions');
$routes->get('/dashboard/fines', 'Dashboard::fines');

// ========== BOOKS ROUTES ==========
$routes->get('/books', 'Books::index');
$routes->get('/books/(:num)', 'Books::show/$1');

// Sederhanakan dulu, nonaktifkan route yang bermasalah
// $routes->post('/books/(:num)/borrow', 'Books::borrow/$1');
// $routes->get('/books/search', 'Books::search');
// $routes->get('/books/recommendations', 'Books::recommendations');

// ========== DEBUG ROUTES ==========
// Nonaktifkan dulu semua debug route
// $routes->get('/books/debug', 'Books::debug');
// $routes->get('/books/test', 'Books::test');
// $routes->get('/books/testBooks', 'Books::testBooks');
// $routes->get('/api/test', 'Books::test');