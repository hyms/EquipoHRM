<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            if (empty($request->all())) {
                $roles = Roles::all();
                Log::debug("Success get all");
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $roles,
                        'count' => count($roles),
                    ]]);
            }
            $rol = Roles::where('id', $request->get('id'))->first();
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
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:5',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $roles = new Roles;
            if (!empty($request['id'])) {
                $roles = Roles::find($request['id']);
            }
            $roles->fill($request->all());
            $roles->save();
            if ($roles->wasChanged()) {
                Roles::history($roles->id);
            }
            return response()->json([
                'status' => 0,
                'data' => $roles]);
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
            $roles = Roles::find($request['id'])->delete();
            Log::debug($roles);
            if ($roles) {
                Roles::history($request['id']);
            }
            return response()->json([
                'status' => ($roles ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
