<?php

namespace App\Http\Controllers;

use App\Models\PersonalVacacionesEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonalVacacionesEstadoController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {

                $PersonalVacaciones = DB::table('empleado_vacaciones')
                    ->select('empleado_vacaciones.*', 'empleado.nombres', 'empleado.apellidos', 'empleado_vacaciones_estado.total_disponible', 'empleado_vacaciones_estado.total_usado')
                    ->leftJoin('empleado', 'empleado_vacaciones.empleado_id', '=', 'empleado.id')
                    ->leftJoin('empleado_vacaciones_estado', 'empleado.id', '=', 'empleado_vacaciones_estado.empleado_id')
                    ->whereNull('empleado_vacaciones.deleted_at')
                    ->whereNull('empleado.deleted_at')
                    ->get();
                //$PersonalVacacionesEstado = PersonalVacacionesEstado::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $PersonalVacaciones,
                        'count' => $PersonalVacaciones->count(),
                    ]]);
            }
            $rol = PersonalVacacionesEstado::find($request->get('id'));
            return response()->json([
                'status' => (empty($rol) ? -1 : 0),
                'data' => $rol
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
                'empleado_id' => 'required',
                'total_usado' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $PersonalVacacionesEstado = new PersonalVacacionesEstado;
            if (!empty($request['id'])) {
                $PersonalVacacionesEstado = PersonalVacacionesEstado::find($request['id']);
            }
            $PersonalVacacionesEstado->fill($request->all());
            $PersonalVacacionesEstado->save();
            return response()->json([
                'status' => 0,
                'data' => $PersonalVacacionesEstado
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

}
