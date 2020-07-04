<?php

namespace App\Http\Controllers;

use App\Models\Unidadesnegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UnidadesnegocioController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            Log::debug("Init Get Unidadesnegocio user:" . Auth::guard('api')->user()->id);
            Log::info("IP: " . $request->getClientIp());
            Log::info('user-agent:' . $request->userAgent());

            if (empty($request->all())) {
                $Unidadesnegocio = Unidadesnegocio::GetAll();
                Log::debug("Success get all");
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $Unidadesnegocio,
                        'count' => count($Unidadesnegocio),
                    ]]);
            }
            $row = Unidadesnegocio::Get($request->get('id'));
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
            Log::debug("Init Post Unidadesnegocio user:" . Auth::guard('api')->user()->id);
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
            $id = Unidadesnegocio::Save($request->all());
            if (empty($id)) {
                return response()->json([
                    'status' => -1,
                    'data' => [
                        'message' => 'Ocurrio un error al guardar los datos.'
                    ]]);
            }
            return response()->json([
                'status' => 0,
                'data' => Unidadesnegocio::Get($id)]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            Log::debug("Init Delete Unidadesnegocio user:" . Auth::guard('api')->user()->id);
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
            $Unidadesnegocio = Unidadesnegocio::Delete($request->all());
            return response()->json([
                'status' => ($Unidadesnegocio ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
