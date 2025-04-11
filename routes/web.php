<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post('/register', 'Auth\AuthController@register');
$router->post('/login', 'Auth\AuthController@login');
$router->group([
    'middleware' => 'auth',
], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/me', 'Auth\AuthController@me');
        $router->post('/refresh', 'Auth\AuthController@refresh');
        $router->post('/logout', 'Auth\AuthController@logout');
    });
});
