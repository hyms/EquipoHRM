<?php

namespace App\Http\Controllers;

use App\Models\Gerencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GerenciaController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $Gerencia = Gerencia::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $Gerencia,
                        'count' => $Gerencia->count(),
                    ]]);
            }
            $rol = Gerencia::find($request->get('id'));
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
                'nombre' => 'required|min:5',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $gerencia = new Gerencia;
            if (!empty($request['id'])) {
                $gerencia = Gerencia::find($request['id']);
            }
            $gerencia->fill($request->all());
            $gerencia->save();
            return response()->json([
                'status' => 0,
                'data' => $gerencia]);
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
            $gerencia = Gerencia::find($request['id'])->delete();
            if ($gerencia) {
                Gerencia::history($request['id']);
            }
            return response()->json([
                'status' => ($gerencia ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
