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
Route::post('/logout', 'AuthController@logout');
//usuarios
Route::get('/usuarios/get', 'UsuariosController@getAll');
Route::post('/usuarios/post', 'UsuariosController@post');
Route::delete('/usuarios/delete', 'UsuariosController@delete');
//roles
Route::get('/roles/get', 'RolesController@getAll');
Route::post('/roles/post', 'RolesController@post');
Route::delete('/roles/delete', 'RolesController@delete');
//diasFestivos
Route::get('/diasfestivos/get', 'DiasfestivosController@getAll');
Route::post('/diasfestivos/post', 'DiasfestivosController@post');
Route::delete('/diasfestivos/delete', 'DiasfestivosController@delete');
//empresa
Route::get('/empresa/get', 'EmpresaController@get');
Route::post('/empresa/post', 'EmpresaController@post');
//unidadesNegocio
Route::get('/unidadesnegocio/get', 'UnidadesnegocioController@getAll');
Route::post('/unidadesnegocio/post', 'UnidadesnegocioController@post');
Route::delete('/unidadesnegocio/delete', 'UnidadesnegocioController@delete');
//areasTrabajo
Route::get('/areastrabajo/get', 'AreastrabajoController@getAll');
Route::post('/areastrabajo/post', 'AreastrabajoController@post');
Route::delete('/areastrabajo/delete', 'AreastrabajoController@delete');
//cargos
Route::get('/cargos/get', 'CargosController@getAll');
Route::post('/cargos/post', 'CargosController@post');
Route::delete('/cargos/delete', 'CargosController@delete');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
