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
    return $router->app->version();
});

$router->post('user/login', 'UserController@login');
$router->post('user/register', 'UserController@register');

$router->group(['middleware' => 'auth'], function () use ($router) {

	$router->get('user[/{id}]', 'UserController@index');

	$router->get('todo[/{id}]', 'TodoController@index');
	$router->post('todo', 'TodoController@store');
	$router->put('todo/{id}', 'TodoController@update');
	$router->delete('todo/{id}', 'TodoController@destroy');

	$router->post('todo-item', 'TodoItemController@store');
	$router->put('todo-item/{id}', 'TodoItemController@update');
	$router->delete('todo-item/{id}', 'TodoItemController@destroy');

});