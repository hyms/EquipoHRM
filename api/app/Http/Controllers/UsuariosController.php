<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $Usuarios = Usuarios::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $Usuarios,
                        'count' => $Usuarios->count(),
                    ]]);
            }
            $usuario = Usuarios::find($request->get('id'));
            return response()->json([
                'status' => (empty($usuario) ? -1 : 0),
                'data' => $usuario
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
                'username' => 'required|min:5',
                'estado' => 'required',
                'rol' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors(),
                    'message' => "Error al ingresar los datos"
                ]);
            }
            $usuarios = new Usuarios;
            if (!empty($request['id'])) {
                $usuarios = Usuarios::find($request['id']);
            }
            if (!empty($request['password']) && strcmp($usuarios->password, $request['password']) !== 0) {
                $request['password'] = Hash::make($request['password']);
            } else {
                unset($request['password']);
            }
            $usuarios->fill($request->all());
            $usuarios->save();
            return response()->json([
                'status' => 0,
                'data' => $usuarios]);
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
            $Usuarios = Usuarios::find($request['id'])->delete();
            if ($Usuarios) {
                Usuarios::history($request['id']);
            }
            return response()->json([
                'status' => ($Usuarios ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
