<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $empresas = Empresa::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $empresas,
                        'count' => $empresas->count(),
                    ]]);
            }

            $row = Empresa::find($request->get('id'));
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
            $cargo = new Cargo;
            if (!empty($request['id'])) {
                $cargo = Cargo::find($request['id']);
            }
            $cargo->fill($request->all());
            $cargo->save();
            return response()->json([
                'status' => 0,
                'data' => $cargo]);
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
            $cargo = Cargo::find($request['id'])->delete();
            if ($cargo) {
                Cargo::history($request['id']);
            }
            return response()->json([
                'status' => ($cargo ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
