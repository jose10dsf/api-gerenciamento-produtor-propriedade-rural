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

use App\Models\User;
use App\Models\Producer;
use App\Models\Property;
use App\Helpers\DefaultResponseHelper;


$router->get('/', function () use ($router) {
    //return $router->app->version();
    $response = DefaultResponseHelper::success(['message' => 'Bem-vindo a api do sistema de cadastramento de produtores e propriedades rurais!']);
    return response()->json($response);
});

$router->group([
    //'middleware' => 'auth',
    'prefix' => 'api/v1/auth',
    'namespace' => 'V1\Auth'

], function () use ($router) {

    $router->post('login', 'AuthController@login');
    $router->group(['middleware' => 'JwtMiddleware'], function () use ($router) {
	    $router->get('logout', 'AuthController@logout');
	    $router->get('refresh', 'AuthController@refresh');
	    $router->get('me', 'AuthController@me');
    });

});

$router->group(['prefix' => 'api/v1', 'middleware' => ['JwtMiddleware', 'IsSuperAdminMiddleware'], 'namespace' => 'V1\User', 'as' => User::class], function () use ($router) {
	 $router->post('/users', [
	       'uses' => 'UserController@create',
	       'middleware' => ['ValidateDataMiddleware']
	   ]);
	   $router->get('/users', [
	       'uses' => 'UserController@findAll'
	   ]);
	   /*$router->post('/users/name-already-exists[/{id}]', [
	       'uses' => 'UserController@nameAlreadyExists'
	   ]);*/
	   $router->get('/users/{id}', [
	       'uses' => 'UserController@findOneById'
	   ]);
	   $router->put('/users/{id}', [
	       'uses' => 'UserController@editById',
	       'middleware' => ['ValidateDataMiddleware']
	   ]);
	   $router->patch('/users/{id}', [
	       'uses' => 'UserController@editById',
	       'middleware' => ['ValidateDataMiddleware']
	   ]);
	   $router->delete('/users/{id}', [
	       'uses' => 'UserController@delete',
	       'middleware' => ['IsSuperAdminMiddleware']
	   ]);
});

$router->group(['prefix' => 'api/v1', 'middleware' => ['JwtMiddleware'],'namespace' => 'V1\Producer', 'as' => Producer::class], function () use ($router) {
	 $router->post('/producers', [
	       'uses' => 'ProducerController@create',
	       'middleware' => ['ValidateDataMiddleware']
	   ]);
	   $router->get('/producers', [
	       'uses' => 'ProducerController@findAll'
	   ]);
	   $router->get('/producers/{id}', [
	       'uses' => 'ProducerController@findOneById'
	   ]);
	   $router->put('/producers/{id}', [
	       'uses' => 'ProducerController@editById',
	       'middleware' => ['ValidateDataMiddleware']
	   ]);
	   $router->patch('/producers/{id}', [
	       'uses' => 'ProducerController@editById',
	       'middleware' => [ 'ValidateDataMiddleware']
	   ]);
	   $router->delete('/producers/{id}', [
	       'uses' => 'ProducerController@delete',
	       'middleware' => []
	   ]);
});
