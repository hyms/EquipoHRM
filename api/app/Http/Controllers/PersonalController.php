<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonalController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $personals = Personal::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $personals,
                        'count' => $personals->count(),
                    ]]);
            }
            $personal = Personal::find($request->get('id'));
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
                'ci' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $Personal = new Personal;
            if (!empty($request['id'])) {
                $Personal = Personal::find($request['id']);
            }
            if (empty($request['id_usuario'])) {
                unset($request['id_usuario']);
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
            $Personal = Personal::find($request['id'])->delete();
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

    public function postUsuario(Request $request)
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
            $Usuarios = new Usuarios();
            $Personal = Personal::find($request['empleado']);
            if (!empty($request['id'])) {
                $Usuarios = Usuarios::find($request['id']);
            }
            unset($request['empleado']);
            $Usuarios->fill($request->all());
            $Usuarios->save();
            if (empty($request['id'])) {
                $Personal->id_usuario = $Usuarios->id;
                $Personal->save();
            }
            //$Usuarios = $Usuarios->toArray();
            $Usuarios['empleado'] = $Personal->id;
            return response()->json([
                'status' => 0,
                'data' => $Usuarios
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
