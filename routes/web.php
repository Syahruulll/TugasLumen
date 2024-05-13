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

$router->group(['middleware' => 'cors'],function ($router){


$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/profile', 'AuthController@me');
// stuff
// sturktur : $router->get('/stuff','namacontroller@namafunction')
 


$router->get('/users','UserController@index');//user
$router->post('/users/store','UserController@store');
$router->get('/users/trash','UserController@trash');


$router->get('/stuffs','StuffController@index');//stuff
$router->post('/stuffs/store','StuffController@store');
$router->get('/stuffs/trash','StuffController@trash');

$router->post('/inbound-stuffs/store','InboundStuffController@store');//inbound
$router->get('/inbound-stuffs/trash', 'InboundStuffController@trash');

$router->get('/lendings','LendingController@index');
$router->post('/lendings/store','LendingController@store');//lending



 
//dinamis


$router->get('/users/{id}','UserController@show');//user
$router->patch('/users/update/{id}','UserController@update');
$router->delete('/users/delete/{id}','UserController@destroy');


$router->get('/stuffs/{id}','StuffController@show');//stuff
$router->patch('/stuffs/update/{id}','StuffController@update');
$router->delete('/stuffs/delete/{id}','StuffController@destroy');

$router->delete('/inbound-stuffs/delete/{id}','InboundStuffController@delete');//inbound

$router->post('/restorations/{lending_id}','Restoration Controller@store');//restorations

$router->delete('/lendings/delete/{id}','LendingController@destroy');




// softdeletes : trash,restore,undo
$router->get('/users/trash/restore/{id}','UserController@restore');//user
$router->get('/users/trash/permanent/{id}','UserController@permanentDelete');


$router->get('/stuffs/trash/restore/{id}','StuffController@restore');//stuff
$router->get('/stuffs/trash/permanent/{id}','StuffController@permanentDelete');

$router->get('/inbound-stuffs/trash/restore/{id}','InboundStuffController@restore');
$router->get('/inbound-stuffs/trash/permanent/{id}','InboundStuffController@deletePermanent');//inbound



});
