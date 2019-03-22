<?php

use Illuminate\Http\Request;
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
    return 'Tracking api is online';
});
$router->post('/trackings', ['middleware' => ['api','tracking:insert'], 'uses' => 'TrackingController@store']);
$router->post('/trackings/bulk', ['middleware' => ['api','tracking:bulk_insert'], 'uses' => 'TrackingController@bulkStore']);
$router->put('/trackings/{ols_key}', ['middleware' => ['api','tracking:update'], 'uses' => 'TrackingController@update']);
$router->delete('/trackings/{ols_key}', ['middleware' => ['api','tracking:delete'], 'uses' => 'TrackingController@destroy']);
$router->get('/trackings/{ols_key}', ['middleware' => ['api','tracking:select'], 'uses' => 'TrackingController@show']);
//$router->get('/trackings/{ols_key}', ['middleware' => ['access', 'tracking:select'], 'uses' => 'TrackingController@show']);w
//$router->get('/trackings/{ols_key}', ['uses' => 'TrackingController@show']);
$router->post('/test/{ols_key}', ['uses' => 'TrackingController@test']);

$router->post('/checkpoints', ['middleware' => ['api','checkpoint:insert'], 'uses' => 'CheckpointController@store']);
$router->post('/checkpoints/bulk', ['middleware' => ['api','checkpoint:bulk_insert'], 'uses' => 'CheckpointController@bulkStore']);
$router->delete('/checkpoints/{id}', ['middleware' => ['api','checkpoint:delete'], 'uses' => 'CheckpointController@destroy']);


$router->get('/2', function () {
    return view('views.app');
});

$router->get('/views', function () {
    return view('view.index');
});
$router->get('/test',['middleware' => 'api'],function() {
    return 1;
});