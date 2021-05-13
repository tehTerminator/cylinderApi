<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Models\OxygenRequest;
use App\Models\Patient;
use App\Models\Ward;
use Carbon\Carbon;

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

$router->group(['prefix' => 'select', 'middleware'=>'auth'], function () use ($router) {
    $router->get('wards', function() {
        return response()->json(Ward::all());
    });
    $router->get('patients', ['uses' => 'PatientController@select']);
    $router->get('patients/admittedToday', function() {
        $count = Patient::whereDate('created_at', Carbon::now())->count();
        return response()->json(['value' => $count]);
    });
    $router->get('patients/dischargedToday', function() {
        $count = Patient::whereDate('date_of_discharge', Carbon::now())->count();
        return response()->json(['value' => $count]);
    });
    $router->get('oxygen_request/patient/{id:[0-9]+}', function($id) {
        $req = OxygenRequest::where('patient_id', $id)->get();
        return response()->json($req);
    });
    $router->get('oxygen_request/pending', function() {
        $req = OxygenRequest::where('state', 'ACTIVE')
        ->with(['ward', 'patient'])->get();
        return response()->json($req);
    });
    $router->get('oxygen_request/approvedToday', function() {
        $count = OxygenRequest::whereDate('updated_at', Carbon::now())
        ->where('state', 'APPROVED')->count();
        return response()->json(['value'=>$count]);
    });
});

$router->group(['prefix' => 'create', 'middleware'=>'auth'], function () use ($router){
    $router->post('user', ['uses' => 'UserController@create']);
    $router->post('ward', ['uses' => 'WardController@create']);
    $router->post('patient', ['uses' => 'PatientController@create']);
    $router->post('oxygen_request', ['uses' => 'OxygenRequestController@create']);
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
    $router->put('oxygen_request', ['uses' => 'OxygenRequestController@update']);
    $router->put('patient/discharge', ['uses' => 'PatientController@discharge']);
});