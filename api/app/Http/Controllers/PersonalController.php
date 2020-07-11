<?php

namespace App\Http\Controllers;

use App\Models\Areastrabajo;
use App\Models\Cargo;
use App\Models\Gerencia;
use App\Models\Personal;
use App\Models\Regional;
use App\Models\Unidadesnegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonalController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            if (empty($request->all())) {
                $Personal = Personal::GetAll();
                Log::debug("Success get all");
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $Personal,
                        'count' => count($Personal),
                    ]]);
            }
            $row = Personal::Get($request->get('id'));
            Log::debug("Success get id:" . $request->get('id'));
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
                'nombres' => 'required',
                'estado' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $id = Personal::Save($request->all());
            if (empty($id)) {
                return response()->json([
                    'status' => -1,
                    'data' => [
                        'message' => 'Ocurrio un error al guardar los datos.'
                    ]]);
            }
            return response()->json([
                'status' => 0,
                'data' => Personal::Get($id)]);
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
            $Personal = Personal::Delete($request->all());
            return response()->json([
                'status' => ($Personal ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    public function getAllCarrera()
    {
        try {
            $data = array();
            $data['cargos'] = Cargo::GetAll();
            $data['unidades'] = Unidadesnegocio::GetAll();
            $data['areas'] = Areastrabajo::GetAll();
            $data['regionales'] = Regional::GetAll();
            $data['gerencias'] = Gerencia::GetAll();
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
