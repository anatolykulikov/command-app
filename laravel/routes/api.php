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
    $router->group(['prefix' => 'user'], function ($router) {

        $router->group(['prefix' => 'current'], function ($router) {
            $router->get('', [UserController::class, 'getCurrent']);
            $router->post('meta', [UserController::class, 'updateMeta']);
            $router->post('password', [UserController::class, 'updatePassword']);
        });

        $router->get('profile/{userId}', [UserController::class, 'getUser']);

        $router->group(['middleware' => 'role:admin', 'prefix' => 'manage'], function ($router) {
            $router->post('create', [UserController::class, 'createUser']);
            $router->post('{userId}/meta', [UserController::class, 'updateUserMeta']);
            $router->post('{userId}/password', [UserController::class, 'updateUserPassword']);

            $router->post('set-active', [UserController::class, 'setUserAction']);
            $router->delete('delete', [UserController::class, 'deleteUser']);
            $router->post('resurrection', [UserController::class, 'resurrectionUser']);
        });

        $router->get('logout', [UserController::class, 'logout']);
        $router->get('terminate-sessions', [UserController::class, 'terminateOtherSessions']);
        $router->get('full-logout', [UserController::class, 'fullLogout']);
        $router->delete('delete', [UserController::class, 'deleteCurrent']);
    });

    $router->group(['prefix' => 'users'], function ($router) {
        $router->get('roles', [UserController::class, 'getUserRoles']);
    });
});
