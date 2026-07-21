<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Client::login');
$routes->get('/client/login', 'Client::login');
$routes->post('/client/doLogin', 'Client::doLogin');
$routes->get('/client/logout', 'Client::logout');
$routes->get('/client/dashboard', 'Client::dashboard');
$routes->match(['get', 'post'], 'client/executerOperation', 'Client::executerOperation');

// Ajout de la route pour le transfert groupé / V2
$routes->post('transaction/processTransfert', 'Transaction::processTransfert');

// Partie opérateur

$routes->get('operateur', 'Operateur::dashboard');

$routes->get('operateur/prefixes', 'Operateur::prefixes');
$routes->post('operateur/prefixes/add', 'Operateur::ajouterPrefixe');

$routes->get('operateur/baremes', 'Operateur::baremes');
$routes->post('operateur/baremes/update/(:num)', 'Operateur::modifierFrais/$1');
