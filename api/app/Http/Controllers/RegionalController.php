<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegionalController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            Log::debug("Init Get Regional user:" . Auth::guard('api')->user()->id);
            Log::info("IP: " . $request->getClientIp());
            Log::info('user-agent:' . $request->userAgent());

            if (empty($request->all())) {
                $Regional = Regional::GetAll();
                Log::debug("Success get all");
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $Regional,
                        'count' => count($Regional),
                    ]]);
            }
            $rol = Regional::Get($request->get('id'));
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
            Log::debug("Init Post Regional user:" . Auth::guard('api')->user()->id);
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
            $id = Regional::Save($request->all());
            if (empty($id)) {
                return response()->json([
                    'status' => -1,
                    'data' => [
                        'message' => 'Ocurrio un error al guardar los datos.'
                    ]]);
            }
            return response()->json([
                'status' => 0,
                'data' => Regional::Get($id)]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            Log::debug("Init Delete Regional user:" . Auth::guard('api')->user()->id);
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
            $Regional = Regional::Delete($request->all());
            return response()->json([
                'status' => ($Regional ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
