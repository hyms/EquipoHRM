<?php

namespace App\Http\Controllers;

use App\Models\Gerencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GerenciaController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            if (empty($request->all())) {
                $Gerencia = Gerencia::GetAll();
                Log::debug("Success get all");
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $Gerencia,
                        'count' => count($Gerencia),
                    ]]);
            }
            $rol = Gerencia::Get($request->get('id'));
            Log::debug("Success get id:" . $request->get('id'));
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
            Log::debug("Init Post Gerencia user:" . Auth::guard('api')->user()->id);
            Log::info("IP: " . $request->getClientIp());
            Log::info('user-agent:' . $request->userAgent());

            $validator = Validator::make($request->all(), [
                'nombre' => 'required|min:5',
                'estado' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $id = Gerencia::Save($request->all());
            if (empty($id)) {
                return response()->json([
                    'status' => -1,
                    'data' => [
                        'message' => 'Ocurrio un error al guardar los datos.'
                    ]]);
            }
            return response()->json([
                'status' => 0,
                'data' => Gerencia::Get($id)]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            Log::debug("Init Delete Gerencia user:" . Auth::guard('api')->user()->id);
            Log::info("IP: " . $request->getClientIp());
            Log::info('user-agent:' . $request->userAgent());

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $Gerencia = Gerencia::Delete($request->all());
            return response()->json([
                'status' => ($Gerencia ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
