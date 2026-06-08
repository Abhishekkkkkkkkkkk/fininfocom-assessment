<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('CartController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Cart Routes
$routes->get('/', 'CartController::index');
$routes->get('/cart', 'CartController::index');
$routes->get('/cart/add/(:num)', 'CartController::add/$1');
$routes->get('/cart/increase/(:num)', 'CartController::increase/$1');
$routes->get('/cart/decrease/(:num)', 'CartController::decrease/$1');
$routes->get('/cart/remove/(:num)', 'CartController::remove/$1');
$routes->get('/cart/clear', 'CartController::clear');

// API Orders Route (Task 2)
$routes->get('/api/orders', 'ApiController::index');
$routes->options('/api/orders', 'ApiController::index'); // CORS Support
