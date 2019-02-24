<?php

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

$router->get('/', function () use ($router) {
    return view('wellcome');
});

$router->get('/key', function () {
    return str_random(32);
});


$router->get('login', 'Auth\AuthController@showForm');
$router->post('login', 'Auth\AuthController@login');
$router->group(['middleware' => 'jwt.auth'], function ($router) {
    $router->get('profile/{token}', 'Auth\AuthController@profile');
    $router->get('logout', 'Auth\AuthController@logout');
    $router->post('admin/tasks/', 'Auth\AuthController@create');
    $router->get('admin/tasks/{id}/edit', 'Auth\AuthController@edit');
    $router->put('admin/tasks/{id}', 'Auth\AuthController@update');
    $router->delete('admin/tasks/{id}', 'Auth\AuthController@destroy');
});

//Password reset
$router->get('password-reset', 'Auth\PasswordController@showForm');
$router->post('password-reset', 'Auth\PasswordController@sendPasswordResetToken');
$router->get('password-reset/{token}', 'Auth\PasswordController@showPasswordResetForm');
$router->post('password-reset/{token}', 'Auth\PasswordController@resetPassword');

$router->post('api/login', 'API\AuthController@login');
$router->group(['prefix' => 'api', 'middleware' => 'jwt.auth'], function ($router) {
    $router->get('users', 'API\AdminController@index');
    $router->get('logs', 'API\AdminController@logs');
    $router->get('tasks/', 'API\TasksController@index');
    $router->post('tasks', 'API\TasksController@create');
    $router->get('tasks/{id}', 'API\TasksController@show');
    $router->put('tasks/{id}', 'API\TasksController@update');
    $router->delete('tasks/{id}', 'API\TasksController@destroy');
    $router->get('logout', ['uses' => 'API\AuthController@logout']);
});