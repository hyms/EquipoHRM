<?php

namespace App\Http\Controllers;

use App\Models\TipoPermisoVacacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TipoVacacionController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            if (empty($request->all())) {
                $TipoPermisoVacacion = TipoPermisoVacacion::GetAll();
                Log::debug("Success get all");
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $TipoPermisoVacacion,
                        'count' => count($TipoPermisoVacacion),
                    ]]);
            }
            $row = TipoPermisoVacacion::Get($request->get('id'));
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
                'nombre' => 'required',
                'estado' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $id = TipoPermisoVacacion::Save($request->all());
            if (empty($id)) {
                return response()->json([
                    'status' => -1,
                    'data' => [
                        'message' => 'Ocurrio un error al guardar los datos.'
                    ]]);
            }
            return response()->json([
                'status' => 0,
                'data' => TipoPermisoVacacion::Get($id)]);
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
            $TipoPermisoVacacion = TipoPermisoVacacion::Delete($request->all());
            return response()->json([
                'status' => ($TipoPermisoVacacion ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
