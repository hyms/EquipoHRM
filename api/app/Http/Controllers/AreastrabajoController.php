<?php

namespace App\Http\Controllers;

use App\Models\Areastrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AreastrabajoController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $Areastrabajo = Areastrabajo::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $Areastrabajo,
                        'count' => $Areastrabajo->count(),
                    ]]);
            }
            $row = Areastrabajo::find($request->get('id'));
            return response()->json([
                'status' => (empty($row) ? -1 : 0),
                'data' => $row
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
                'nombre' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors(),
                    'message' => "Error al ingresar los datos"
                ]);
            }
            $areas = new Areastrabajo;
            if (!empty($request['id'])) {
                $areas = Areastrabajo::find($request['id']);
            }
            $areas->fill($request->all());
            $areas->save();
            return response()->json([
                'status' => 0,
                'data' => $areas
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error], 500);
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
            $areas = Areastrabajo::find($request['id'])->delete();
            if ($areas) {
                Areastrabajo::history($request['id']);
            }
            return response()->json([
                'status' => ($areas ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
