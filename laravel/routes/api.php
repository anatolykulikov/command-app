<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RoleFilter;
use Illuminate\Routing\Router;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/** @var Router $router */

$router->get('health', [Controller::class, 'healthCheck']);

$router->post('login', [AuthController::class, 'login']);

$router->group(['middleware' => [Authenticate::class]], function ($router) {

    /* Роуты пользователя */
    // TODO: Обновление пароля
    $router->group(['prefix' => 'user'], function ($router) {

        $router->group(['prefix' => 'current'], function ($router) {
            $router->get('', [UserController::class, 'getCurrent']);
            $router->post('meta', [UserController::class, 'updateMeta']);
        });

        $router->group(['prefix' => 'profile'], function ($router) {
            $router->get('{userId}', [UserController::class, 'getUser']);
            $router->post('{userId}/meta', [UserController::class, 'updateUserMeta']);
        });


        $router->get('logout', [UserController::class, 'logout']);
        $router->get('terminate-sessions', [UserController::class, 'terminateOtherSessions']);
        $router->get('full-logout', [UserController::class, 'fullLogout']);

        $router->group(['middleware' => 'role:admin'], function ($router) {
            $router->post('create', [UserController::class, 'createUser']);
        });
    });
});
