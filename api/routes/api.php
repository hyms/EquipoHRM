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
Route::get('/regional', 'RegionalController@getAll');
Route::post('/regional', 'RegionalController@post');
Route::delete('/regional', 'RegionalController@delete');
//gerencial
Route::get('/gerencia', 'GerenciaController@getAll');
Route::post('/gerencia', 'GerenciaController@post');
Route::delete('/gerencia', 'GerenciaController@delete');
//personal
Route::get('/personal', 'PersonalController@getAll');
Route::post('/personal', 'PersonalController@post');
Route::delete('/personal', 'PersonalController@delete');
Route::get('/personal/carrera', 'PersonalController@getAllCarrera');
//carrera
Route::get('/carrera', 'CarreraController@getAll');
Route::post('/carrera', 'CarreraController@post');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
