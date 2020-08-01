<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            if (empty($request->all())) {
                $Usuarios = Usuarios::all();
                Log::debug("Success get all");
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $Usuarios,
                        'count' => Usuarios::all()->count(),
                    ]]);
            }
            $usuario = Usuarios::where('id', $request->get('id'))->first();
            Log::debug("Success get id:" . $request->get('id'));
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
                'name' => 'required|min:5',
                'estado' => 'required',
                'rol' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $usuarios = new Usuarios;
            if (!empty($request['id'])) {
                $usuarios = Usuarios::find($request['id']);
            }
            if (!empty($request['password'])) {
                $request['password'] = Hash::make($request['password']);
            }
            $usuarios->fill($request->all());
            $usuarios->save();
            if ($usuarios->wasChanged()) {
                Usuarios::history($usuarios->id);
            }
            return response()->json([
                'status' => 0,
                'data' => Usuarios::where('id', $id)->fisrt()]);
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
            $Usuarios = Usuarios::where('id', $request['id'])->first()->delete();
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
