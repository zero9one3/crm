<?php
session_start();

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config/app.php';

$router = new Router(BASE_URL);

// общие
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// только мастер
$router->get('/my-salary', [MasterSalaryController::class, 'index'], ['master']);

// только админ
$router->get('/masters', [MasterController::class, 'index'], ['admin']);
$router->get('/masters/create', [MasterController::class, 'create'], ['admin']);
$router->post('/masters/create', [MasterController::class, 'create'], ['admin']);
$router->post('/orders/delete', [OrderController::class, 'delete'], ['admin']);
$router->get('/clients', [ClientController::class, 'index'], ['admin']);
$router->get('/masters/edit', [MasterController::class, 'edit'], ['admin']);
$router->post('/masters/edit', [MasterController::class, 'edit'], ['admin']);
$router->post('/masters/delete', [MasterController::class, 'delete'], ['admin']);


// админ + мастер
$router->get('/', [DashboardController::class, 'index'], ['admin','master']);
$router->get('/orders', [OrderController::class, 'index'], ['admin','master']);
$router->get('/orders/create', [OrderController::class, 'create'], ['admin','master']);
$router->post('/orders/create', [OrderController::class, 'create'], ['admin','master']);
$router->get('/orders/edit', [OrderController::class, 'edit'], ['admin','master']);
$router->post('/orders/edit', [OrderController::class, 'edit'], ['admin','master']);
$router->get('/salary', [SalaryController::class, 'index'], ['admin','master']);
$router->get('/salary/details', [SalaryController::class, 'details'], ['admin', 'master']);
$router->get('/orders/receipt', [OrderController::class, 'receipt'], ['admin','master']);
$router->get('/clients/view', [ClientController::class, 'show'], ['admin','master']);
$router->get('/clients/edit', [ClientController::class, 'edit'], ['admin','master']);
$router->post('/clients/edit', [ClientController::class, 'edit'], ['admin','master']);



$router->dispatch($_SERVER['REQUEST_URI']);
