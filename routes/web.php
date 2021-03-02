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

$router->get('/', function() {
    return "Hello, this is the FISCAL PROXY";
});

$router->get('/proxy/ibpt/taxes', 'API\IBPTController@calculateProductTaxes');
$router->post('/proxy/ibpt/message', 'API\IBPTController@prepareMessageIBPT');
