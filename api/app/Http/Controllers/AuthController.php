<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }

            $user = Usuarios::VerificarUsuario($request->email, $request->password);
            if (!$user) {
                return response()->json([
                    'status' => -1,
                    'data' => [
                        'message' => 'Datos invalidos'
                    ]]);
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status' => 0,
                'data' => [
                    'user' => $user,
                    'token' => $tokenResult,
                ]]);
        } catch (\Exception $error) {
            return response()->json([
                //'message' => $error->getMessage(),
                'error' => $error,
            ], 500);
        }
    }
}
