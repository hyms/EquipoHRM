<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function getAll(Request $request)
    {
        $usuarios = Usuarios::GetAll();
        return response()->json([
            'status' => 0,
            'data' => [
                'all' => $usuarios,
                'count' => count($usuarios),
            ]]);
    }
}
