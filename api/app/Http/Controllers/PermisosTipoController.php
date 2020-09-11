<?php

namespace App\Http\Controllers;

use App\Models\PersonalPermisosTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PermisosTipoController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $PersonalPermisosTipo = PersonalPermisosTipo::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $PersonalPermisosTipo,
                        'count' => $PersonalPermisosTipo->count(),
                    ]]);
            }
            $rol = PersonalPermisosTipo::find($request->get('id'));
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
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $PersonalPermisosTipo = new PersonalPermisosTipo;
            if (!empty($request['id'])) {
                $PersonalPermisosTipo = PersonalPermisosTipo::find($request['id']);
            }
            $PersonalPermisosTipo->fill($request->all());
            $PersonalPermisosTipo->save();
            return response()->json([
                'status' => 0,
                'data' => $PersonalPermisosTipo]);
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
            $PersonalPermisosTipo = PersonalPermisosTipo::find($request['id'])->delete();
            if ($PersonalPermisosTipo) {
                PersonalPermisosTipo::history($request['id']);
            }
            return response()->json([
                'status' => ($PersonalPermisosTipo ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
