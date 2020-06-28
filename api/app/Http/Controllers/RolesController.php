<?php

namespace App\Http\Controllers;

use App\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function getAll(Request $request)
    {
        if (empty($request->all())) {
            $roles = Roles::GetAll();
            return response()->json([
                'status' => 0,
                'data' => [
                    'all' => $roles,
                    'count' => count($roles),
                ]]);
        }
        $rol = Roles::Get($request->get('id'));
        return response()->json([
            'status' => (empty($rol) ? -1 : 0),
            'data' => $rol
        ]);
    }

    public function post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:5',
            'estado' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => -1,
                'data' => $validator->errors()]);
        }
        $id = Roles::Save($request->all());
        if (empty($id)) {
            return response()->json([
                'status' => -1,
                'data' => [
                    'message' => 'Ocurrio un error al guardar los datos.'
                ]]);
        }
        return response()->json([
            'status' => 0,
            'data' => Roles::Get($id)]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => -1,
                'data' => $validator->errors()]);
        }
        $roles = Roles::Delete($request->all());
        return response()->json([
            'status' => ($roles ? 0 : -1),
            'data' => []
        ]);
    }
}
