<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Client::login');
$routes->get('/client/login', 'Client::login');
$routes->post('/client/doLogin', 'Client::doLogin');
$routes->get('/client/logout', 'Client::logout');
$routes->get('/client/dashboard', 'Client::dashboard');
$routes->post('/client/executerOperation', 'Client::executerOperation');
$routes->get('/', 'Home::index');


// Partie opérateur

$routes->get('operateur', 'Operateur::dashboard');

$routes->get('operateur/prefixes', 'Operateur::prefixes');
$routes->post('operateur/prefixes/add', 'Operateur::ajouterPrefixe');

$routes->get('operateur/baremes', 'Operateur::baremes');
$routes->post('operateur/baremes/update/(:num)', 'Operateur::modifierFrais/$1');
