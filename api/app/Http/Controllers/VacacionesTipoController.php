<?php

namespace App\Http\Controllers;

use App\Models\VacacionesTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VacacionesTipoController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $VacacionesTipo = VacacionesTipo::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $VacacionesTipo,
                        'count' => $VacacionesTipo->count(),
                    ]]);
            }
            $rol = VacacionesTipo::find($request->get('id'));
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
                'tipo' => 'required',
                'tiempo_dias' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $VacacionesTipo = new VacacionesTipo;
            if (!empty($request['id'])) {
                $VacacionesTipo = VacacionesTipo::find($request['id']);
            }
            $VacacionesTipo->fill($request->all());
            $VacacionesTipo->save();
            return response()->json([
                'status' => 0,
                'data' => $VacacionesTipo]);
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
            $VacacionesTipo = VacacionesTipo::find($request['id'])->delete();
            if ($VacacionesTipo) {
                VacacionesTipo::history($request['id']);
            }
            return response()->json([
                'status' => ($VacacionesTipo ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
