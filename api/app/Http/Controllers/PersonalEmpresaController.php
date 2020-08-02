<?php

namespace App\Http\Controllers;

use App\Models\PersonalEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonalEmpresaController extends Controller
{
    public function get(Request $request)
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
            $personal = PersonalEmpresa::firstWhere('empleado', $request->get('id'));
            return response()->json([
                'status' => (empty($personal) ? -1 : 0),
                'data' => $personal
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
                'empleado' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $Personal = new PersonalEmpresa;
            if (!empty($request['id'])) {
                $Personal = PersonalEmpresa::find($request->get('id'));
            }
            $Personal->fill($request->all());
            $Personal->save();
            return response()->json([
                'status' => 0,
                'data' => $Personal
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

}
