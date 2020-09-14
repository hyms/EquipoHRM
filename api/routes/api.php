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
    Route::get('/', 'UsuariosController@get');
    Route::post('/', 'UsuariosController@post');
    Route::delete('/', 'UsuariosController@delete');
});
//roles
Route::group(['prefix' => 'roles'], function () {
    Route::get('/', 'RolesController@get');
    Route::post('/', 'RolesController@post');
    Route::delete('/', 'RolesController@delete');
});
//privilegios
Route::group(['prefix' => 'rules'], function () {
    Route::get('/', 'PermisosController@get');
    Route::post('/', 'PermisosController@post');
});
//areasTrabajo
Route::group(['prefix' => 'areastrabajo'], function () {
    Route::get('/', 'AreastrabajoController@get');
    Route::post('/', 'AreastrabajoController@post');
    Route::delete('/', 'AreastrabajoController@delete');
});
//gerencia
Route::group(['prefix' => 'gerencia'], function () {
    Route::get('/', 'GerenciaController@get');
    Route::post('/', 'GerenciaController@post');
    Route::delete('/', 'GerenciaController@delete');
});
//unidadesNegocio
Route::group(['prefix' => 'unidadesnegocio'], function () {
    Route::get('/', 'UnidadesnegocioController@get');
    Route::post('/', 'UnidadesnegocioController@post');
    Route::delete('/', 'UnidadesnegocioController@delete');
});
//regional
Route::group(['prefix' => 'regional'], function () {
    Route::get('/', 'RegionalController@get');
    Route::post('/', 'RegionalController@post');
    Route::delete('/', 'RegionalController@delete');
});
//cargos
Route::group(['prefix' => 'cargos'], function () {
    Route::get('/', 'CargosController@get');
    Route::post('/', 'CargosController@post');
    Route::delete('/', 'CargosController@delete');
});
//empleado
Route::group(['prefix' => 'personal'], function () {
    Route::get('/', 'PersonalController@get');
    Route::post('/', 'PersonalController@post');
    Route::delete('/', 'PersonalController@delete');
    Route::get('/carrera', 'PersonalEmpresaController@getData');
    Route::post('/usuario', 'PersonalController@postUsuario');
    Route::group(['prefix' => 'empresa'], function () {
        Route::get('/', 'PersonalEmpresaController@get');
        Route::post('/', 'PersonalEmpresaController@post');
    });
});
//diasFestivos
Route::group(['prefix' => 'diasfestivos'], function () {
    Route::get('/', 'DiasFestivosController@get');
    Route::post('/', 'DiasFestivosController@post');
    Route::delete('/', 'DiasFestivosController@delete');
});
//Vacaciones
Route::group(['prefix' => 'vacaciones'], function () {
    Route::get('/', 'PersonalVacacionesController@get');
    Route::post('/', 'PersonalVacacionesController@post');
    Route::delete('/', 'PersonalVacacionesController@delete');
    Route::group(['prefix' => 'tipo'], function () {
        Route::get('/', 'VacacionesTipoController@get');
        Route::post('/', 'VacacionesTipoController@post');
        Route::delete('/', 'VacacionesTipoController@delete');
    });
    Route::get('/empleado', 'PersonalVacacionesController@empleado');
    Route::get('/days', 'PersonalVacacionesController@getDaysWork');
    Route::post('/aprobar', 'PersonalVacacionesController@approve');
    Route::post('/declinar', 'PersonalVacacionesController@decline');
    Route::post('/registro', 'PersonalVacacionesController@registro');
});
//permisos
Route::group(['prefix' => 'permisos'], function () {
    Route::get('/', 'PersonalPermisosController@get');
    Route::post('/', 'PersonalPermisosController@post');
    Route::delete('/', 'PersonalPermisosController@delete');
    Route::get('/empleado', 'PersonalPermisosController@empleado');
    Route::group(['prefix' => 'tipo'], function () {
        Route::get('/', 'PermisosTipoController@get');
        Route::post('/', 'PermisosTipoController@post');
        Route::delete('/', 'PermisosTipoController@delete');
    });
});


//});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
