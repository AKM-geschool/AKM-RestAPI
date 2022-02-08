<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

//AuthController
$router->post('api/register', 'AuthController@register');
$router->post('api/login', 'AuthController@login');
$router->post('api/logout', 'AuthController@logout');

//UserController
$router->get('api/user', 'UserController@index');
$router->post('api/updatePhoto', 'UserController@updatePhoto');

//Pdf2imgController
$router->get('pdf2img', 'Pdf2imgController@index');
