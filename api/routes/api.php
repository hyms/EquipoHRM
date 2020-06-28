<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//auth
Route::post('/login', 'AuthController@login');
//usuarios
Route::get('/usuarios/get', 'UsuariosController@getAll');
//roles
Route::get('/roles/get', 'RolesController@getAll');
Route::post('/roles/post', 'RolesController@post');
Route::delete('/roles/delete', 'RolesController@delete');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
