<?php

namespace App\Http\Controllers;

use App\Models\Areastrabajo;
use App\Models\Cargo;
use App\Models\Gerencia;
use App\Models\PersonalEmpresa;
use App\Models\Regional;
use App\Models\Unidadesnegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonalEmpresaController extends Controller
{
    public function get(Request $request)
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
            $personal = PersonalEmpresa::where('empleado', $request->get('id'));
            if ($personal->count() > 0) {
                $personal = $personal->orderBy('updated_at', 'desc')->first()->toArray();
            }
            return response()->json([
                'status' => (empty($personal) ? -1 : 0),
                'data' => $personal
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
                'empleado' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $Personal = new PersonalEmpresa;
            if (!empty($request['id'])) {
                $Personal = PersonalEmpresa::find($request->get('id'));
            }
            $Personal->fill($request->all());
            $Personal->save();
            return response()->json([
                'status' => 0,
                'data' => $Personal
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    public function getData()
    {
        try {
            $data = [];
            $data['cargo'] = Cargo::all('id', 'nombre as name');
            $data['area_trabajo'] = Areastrabajo::all('id', 'nombre as name');
            $data['unidad_negocio'] = Unidadesnegocio::all('id', 'nombre as name');
            $data['regional'] = Regional::all('id', 'nombre as name');
            $data['gerencia'] = Gerencia::all('id', 'nombre as name');
            return response()->json([
                'status' => 0,
                'data' => $data
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

}
