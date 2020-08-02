<?php

namespace App\Http\Controllers;

use App\Models\DiasFestivos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DiasFestivosController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $DiasFestivos = DiasFestivos::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $DiasFestivos,
                        'count' => $DiasFestivos->count(),
                    ]]);
            }
            $rol = DiasFestivos::find($request->get('id'));
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
                'fecha' => 'required|unique:dias_festivos',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $DiasFestivos = new DiasFestivos;
            if (!empty($request['id'])) {
                $DiasFestivos = DiasFestivos::find($request['id']);
            }
            $DiasFestivos->fill($request->all());
            $DiasFestivos->save();
            return response()->json([
                'status' => 0,
                'data' => $DiasFestivos]);
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
            $DiasFestivos = DiasFestivos::find($request['id'])->delete();
            if ($DiasFestivos) {
                DiasFestivos::history($request['id']);
            }
            return response()->json([
                'status' => ($DiasFestivos ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
