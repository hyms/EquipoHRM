<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
                Log::warning('Invalid data user:' . $request->email . ' fails:' . $validator->errors()->toJson());
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }

            $user = Usuarios::VerificarUsuario($request->email, $request->password);
            if (!$user) {
                Log::warning('Invalid Dates user:' . $request->email);
                return response()->json([
                    'status' => -1,
                    'data' => [
                        'message' => 'Datos invalidos'
                    ]]);
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            $user->api_token = $tokenResult;
            $user->save();
            Log::debug("Success login user:" . $request->email);
            return response()->json([
                'status' => 0,
                'data' => [
                    'user' => $user,
                    'token' => $tokenResult,
                ]]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([
                //'message' => $error->getMessage(),
                'error' => $error,
            ], 500);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                'status' => 0,
                'data' => []]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([
                //'message' => $error->getMessage(),
                'error' => $error,
            ], 500);
        }
    }
}
