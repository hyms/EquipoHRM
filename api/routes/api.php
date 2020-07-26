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

//auth control
//Route::group(['middleware' => 'auth:sanctum'], function () {
//usuarios
Route::group(['prefix' => 'usuarios'], function () {
    Route::get('/', 'UsuariosController@getAll');
    Route::post('/', 'UsuariosController@post');
    Route::delete('/', 'UsuariosController@delete');
});
//roles
Route::group(['prefix' => 'roles'], function () {
    Route::get('/', 'RolesController@getAll');
    Route::post('/', 'RolesController@post');
    Route::delete('/', 'RolesController@delete');
});
//diasFestivos
Route::group(['prefix' => 'diasfestivos'], function () {
    Route::get('/', 'DiasfestivosController@getAll');
    Route::post('/', 'DiasfestivosController@post');
    Route::delete('/', 'DiasfestivosController@delete');
});
//empresa
Route::group(['prefix' => 'empresa'], function () {
    Route::get('/', 'EmpresaController@get');
    Route::post('/', 'EmpresaController@post');
});
//unidadesNegocio
Route::group(['prefix' => 'unidadesnegocio'], function () {
    Route::get('/', 'UnidadesnegocioController@getAll');
    Route::post('/', 'UnidadesnegocioController@post');
    Route::delete('/', 'UnidadesnegocioController@delete');
});
//areasTrabajo
Route::group(['prefix' => 'areastrabajo'], function () {
    Route::get('/', 'AreastrabajoController@getAll');
    Route::post('/', 'AreastrabajoController@post');
    Route::delete('/', 'AreastrabajoController@delete');
});
//cargos
Route::group(['prefix' => 'cargos'], function () {
    Route::get('/', 'CargosController@getAll');
    Route::post('/', 'CargosController@post');
    Route::delete('/', 'CargosController@delete');
});
//regional
Route::group(['prefix' => 'regional'], function () {
    Route::get('/', 'RegionalController@getAll');
    Route::post('/', 'RegionalController@post');
    Route::delete('/', 'RegionalController@delete');
});
//gerencial
Route::group(['prefix' => 'gerencia'], function () {
    Route::get('/', 'GerenciaController@getAll');
    Route::post('/', 'GerenciaController@post');
    Route::delete('/', 'GerenciaController@delete');
});
//carrera
Route::group(['prefix' => 'carrera'], function () {
    Route::get('/', 'CarreraController@getAll');
    Route::post('/', 'CarreraController@post');
    Route::get('/historia', 'CarreraController@getHistoria');
});
//personal
Route::group(['prefix' => 'personal'], function () {
    Route::get('/', 'PersonalController@getAll');
    Route::post('/', 'PersonalController@post');
    Route::delete('/', 'PersonalController@delete');
    Route::get('/carrera', 'PersonalController@getAllCarrera');
});

//tipo Vacaciones/permisos
Route::group(['prefix' => 'tipovacaciones'], function () {
    Route::get('/', 'TipoVacacionController@getAll');
    Route::post('/', 'TipoVacacionController@post');
    Route::delete('/', 'TipoVacacionController@delete');
});
//Vacaciones
Route::group(['prefix' => 'vacaciones'], function () {
    Route::get('/', 'VacacionesController@getAll');
    Route::post('/', 'VacacionesController@post');
    Route::delete('/', 'VacacionesController@delete');
});

//});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
