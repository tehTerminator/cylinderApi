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

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('select/user', ['uses' => 'UserController@select']);

// $router->group(['prefix' => 'select', 'middleware'=>'auth'], function () use ($router) {
// });

$router->group(['prefix' => 'create', 'middleware'=>'auth'], function () use ($router){
    $router->post('user', ['uses' => 'UserController@create']);
    $router->post('depatment', ['uses' => 'UserContoller@createDepartment']);
    $router->post('designation', ['uses' => 'UserController@createDesignation']);
});

$router->group(['prefix' => 'update', 'middleware'=>'auth'], function () use ($router) {
    $router->put('user/updateBasicDetails', [
        'uses' => 'UserController@updateBasicDetails'
    ]);
    $router->put('user/resetPassword', [
        'uses' => 'UserController@resetPassword'
    ]);
    $router->put('user/updateBasicInfo', [
        'uses' => 'UserController@updateBasicInfo'
    ]);
});