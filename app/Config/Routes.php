<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Client::login');
$routes->get('/client/login', 'Client::login');
$routes->post('/client/doLogin', 'Client::doLogin');
$routes->get('/client/logout', 'Client::logout');
$routes->get('/client/dashboard', 'Client::dashboard');
$routes->post('/client/executerOperation', 'Client::executerOperation');
