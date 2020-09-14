<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PermisosController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $permisos = [];
                $funciones = DB::table('funciones')
                    ->get();
                $roles = DB::table('roles')
                    ->whereNull('deleted_at')
                    ->get();
                //armado de la tabla permisos
                foreach ($funciones as $funcion) {
                    $item = [];
                    $item['id'] = $funcion->id;
                    $item['name'] = $funcion->funcion;
                    $item['type'] = $funcion->type; //0listas, 1crear .....
                    $roles_tmp = DB::table('funciones_roles')
                        ->select('rol_id')
                        ->where('funcion_id', '=', $funcion->id)
                        ->get()
                        ->toArray();
                    foreach ($roles as $rol) {
                        $item[$rol->name] = null;
                        foreach ($roles_tmp as $tmp) {
                            if ($tmp->rol_id === $rol->id) {
                                $item[$rol->name] = $rol->id;
                                break;
                            }
                        }
                    }
                    array_push($permisos, $item);
                }
                return response()->json([
                    'status' => 0,
                    'data' => $permisos
                ]);
            }
            $validator = Validator::make($request->all(), [
                'funcion' => 'required',
                'rol' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }

            $rol = DB::table('funciones_roles')
                ->where('funcion_id', '=', $request['funcion'])
                ->where('rol_id', '=', $request['rol'])
                ->get();
            return response()->json([
                'status' => 0,
                'data' => [empty($rol)]
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    public function post(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'funcion' => 'required',
                'rol' => 'required',
                'check' => 'required|bool',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $result = false;
            $permission = DB::table('funciones_roles');
            if ($request['check']) {
                $permission = $permission
                    ->insertGetId([
                        'funcion_id' => $request['funcion'],
                        'rol_id' => $request['rol'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
            } else {
                $permission = $permission
                    ->where('funcion_id', '=', $request['funcion'])
                    ->where('rol_id', '=', $request['rol'])
                    ->delete();
            }
            if ($permission) {
                $result = true;
            }
            return response()->json([
                'status' => ($result) ? 0 : -1,
                'data' => $result,
                'message' => (!$result) ? 'No se pudo realizar la operacion' : ''
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
