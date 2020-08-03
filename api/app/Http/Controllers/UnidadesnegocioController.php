<?php

namespace App\Http\Controllers;

use App\Models\Unidadesnegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UnidadesnegocioController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $Unidadesnegocio = Unidadesnegocio::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $Unidadesnegocio,
                        'count' => $Unidadesnegocio->count(),
                    ]]);
            }
            $row = Unidadesnegocio::find($request->get('id'));
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
                'nombre' => 'required|min:5',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors(),
                    'message' => "Error al ingresar los datos"
                ]);
            }
            $unidadesnegocio = new Unidadesnegocio;
            if (!empty($request['id'])) {
                $unidadesnegocio = Unidadesnegocio::find($request['id']);
            }
            $unidadesnegocio->fill($request->all());
            $unidadesnegocio->save();
            return response()->json([
                'status' => 0,
                'data' => $unidadesnegocio]);
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
            $unidadnegicio = Unidadesnegocio::find($request['id'])->delete();
            if ($unidadnegicio) {
                Unidadesnegocio::history($request['id']);
            }
            return response()->json([
                'status' => ($unidadnegicio ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
