<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Routing\Router;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorizedController;

/** @var Router $router */

$router->get('health', [Controller::class, 'healthCheck']);
$router->post('login', [AuthController::class, 'login']);

$router->group(['middleware' => [Authenticate::class]], function ($router) {
    $router->get('date', [AuthorizedController::class, 'date']);
});
