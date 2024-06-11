<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can registser all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/platos', 'PlatoController@crearPlato');
$router->get('/platos', 'PlatoController@listarPlatos');

$router->post('/recetas', 'PlatoController@crearReceta');
$router->get('/despensa', 'PlatoController@showAvailableIngredients');


$router->get('/recetas/aleatoria', 'PlatoController@recetaAleatoria');
$router->post('/cocina/pedir', 'PlatoController@pedirIngredientes');

