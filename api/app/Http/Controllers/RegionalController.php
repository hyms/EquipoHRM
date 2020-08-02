<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegionalController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $Regional = Regional::all();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $Regional,
                        'count' => $Regional->count(),
                    ]]);
            }
            $rol = Regional::find($request->get('id'));
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
                'nombre' => 'required|min:5',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $regional = new Regional;
            if (!empty($request['id'])) {
                $regional = Regional::find($request['id']);
            }
            $regional->fill($request->all());
            $regional->save();
            return response()->json([
                'status' => 0,
                'data' => $regional
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
            $regional = Regional::find($request['id'])->delete();
            return response()->json([
                'status' => ($regional ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
