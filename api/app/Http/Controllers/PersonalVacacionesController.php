<?php

namespace App\Http\Controllers;

use App\Models\PersonalVacaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonalVacacionesController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $PersonalVacaciones = PersonalVacaciones::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $PersonalVacaciones,
                        'count' => $PersonalVacaciones->count(),
                    ]]);
            }
            $rol = PersonalVacaciones::find($request->get('id'));
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
                'tipo_vacaciones_id' => 'required',
                'numero_dias' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $PersonalVacaciones = new PersonalVacaciones;
            if (!empty($request['id'])) {
                $PersonalVacaciones = PersonalVacaciones::find($request['id']);
            }
            $PersonalVacaciones->fill($request->all());
            $PersonalVacaciones->save();
            return response()->json([
                'status' => 0,
                'data' => $PersonalVacaciones
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $PersonalVacaciones = PersonalVacaciones::find($request['id'])->delete();
            return response()->json([
                'status' => ($PersonalVacaciones ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
