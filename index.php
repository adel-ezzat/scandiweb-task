<?php

require_once 'autoload.php';

use controllers\ProductsController;
use router\Router;

$router = new Router;
$router->get('/', [ProductsController::class, 'template']);
$router->get('/api/products', [ProductsController::class, 'index']);
$router->post('/api/products/store', [ProductsController::class, 'store']);
$router->post('/api/products/delete', [ProductsController::class, 'delete']);
