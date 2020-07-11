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
Route::get('/usuarios', 'UsuariosController@getAll')->middleware('auth:sanctum');
Route::post('/usuarios', 'UsuariosController@post');
Route::delete('/usuarios', 'UsuariosController@delete');
//roles
Route::get('/roles', 'RolesController@getAll');
Route::post('/roles', 'RolesController@post');
Route::delete('/roles', 'RolesController@delete');
//diasFestivos
Route::get('/diasfestivos', 'DiasfestivosController@getAll');
Route::post('/diasfestivos', 'DiasfestivosController@post');
Route::delete('/diasfestivos', 'DiasfestivosController@delete');
//empresa
Route::get('/empresa', 'EmpresaController@get');
Route::post('/empresa', 'EmpresaController@post');
//unidadesNegocio
Route::get('/unidadesnegocio', 'UnidadesnegocioController@getAll');
Route::post('/unidadesnegocio', 'UnidadesnegocioController@post');
Route::delete('/unidadesnegocio', 'UnidadesnegocioController@delete');
//areasTrabajo
Route::get('/areastrabajo', 'AreastrabajoController@getAll');
Route::post('/areastrabajo', 'AreastrabajoController@post');
Route::delete('/areastrabajo', 'AreastrabajoController@delete');
//cargos
Route::get('/cargos', 'CargosController@getAll');
Route::post('/cargos', 'CargosController@post');
Route::delete('/cargos', 'CargosController@delete');
//regional
Route::get('/regionales', 'RegionalController@getAll');
Route::post('/regionales', 'RegionalController@post');
Route::delete('/regionales', 'RegionalController@delete');
//gerencial
Route::get('/gerencia', 'RegionalController@getAll');
Route::post('/gerencia', 'RegionalController@post');
Route::delete('/gerencia', 'RegionalController@delete');
//personal
Route::get('/personal', 'PersonalController@getAll');
Route::post('/personal', 'PersonalController@post');
Route::delete('/personal', 'PersonalController@delete');
Route::delete('/personal/carrera', 'PersonalController@getAllCarrera');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
