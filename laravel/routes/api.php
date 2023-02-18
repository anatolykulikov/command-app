<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Routing\Router;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/** @var Router $router */

$router->get('health', [Controller::class, 'healthCheck']);

$router->post('login', [AuthController::class, 'login']);

$router->group(['middleware' => [Authenticate::class]], function ($router) {

    /* Роуты пользователя */
    $router->group(['prefix' => 'user'], function ($router) {
        $router->get('current', [UserController::class, 'getCurrent']);
        $router->get('profile/{userId}', [UserController::class, 'getUser']);

        $router->get('logout', [UserController::class, 'logout']);
        $router->get('terminate-sessions', [UserController::class, 'terminateOtherSessions']);
        $router->get('full-logout', [UserController::class, 'fullLogout']);

    });
});
