<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\PersonalPermisos;
use App\Models\PersonalPermisosEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonalPermisosController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                $PersonalPermisos = DB::table('empleado_permisos')
                    ->select('empleado_permisos.*', 'empleado.nombres', 'empleado.apellidos')
                    ->leftJoin('empleado', 'empleado_id', '=', 'empleado.id')
                    ->whereNull('empleado_permisos.deleted_at')
                    ->get();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $PersonalPermisos,
                        'count' => $PersonalPermisos->count(),
                    ]]);
            }
            $vacaciones = PersonalPermisos::find($request->get('id'));
            return response()->json([
                'status' => (empty($vacaciones) ? -1 : 0),
                'data' => $vacaciones
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
                'empleado_id' => 'required',
                'fecha' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $PersonalPermisos = new PersonalPermisos;
            if (!empty($request['id'])) {
                $PersonalPermisos = PersonalPermisos::find($request['id']);
            }
            $PersonalPermisos->fill($request->all());
            $tmp = PersonalPermisos::where('empleado_id', $request['empleado_id'])
                ->where('estado', 2)
                ->orderBy('id', 'desc');
            if (!empty($request['id'])) {
                $tmp = $tmp->where('id', '!=', $request['id']);
            }
            $PersonalPermisos->save();
            return response()->json([
                'status' => 0,
                'data' => $PersonalPermisos
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
            $PersonalPermisos = PersonalPermisos::where('id', $request['id'])
                ->where('estado', 0);
            if ($PersonalPermisos->count() == 0) {
                return response()->json([
                    'status' => -1,
                    'data' => [],
                    'mensaje' => 'No se puede eliminar'
                ]);
            }
            $PersonalPermisos->delete();
            return response()->json([
                'status' => ($PersonalPermisos ? 0 : -1),
                'data' => []
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    public function empleado(Request $request)
    {
        try {
            if (!($request['ci']) && !($request['id'])) {
                return response()->json([
                    'status' => -1,
                    'message' => 'datos incompletos'
                ]);
            }
            $empleado = DB::table('empleado');
            if ($request['ci']) {
                $empleado->where('ci', $request['ci']);
            } elseif ($request['id']) {
                $empleado->where('id', $request['id']);
            }
            $empleado = $empleado->whereNull('deleted_at')
                ->first();

            $result = [];
            if ($empleado) {
                $empleado_empresa = DB::table('empleado_empresa')
                    ->where('empleado', $empleado->id)
                    ->first();
                $unidad = DB::table('unidadesNegocio')
                    ->find($empleado_empresa->unidad_negocio);
                $cargo = DB::table('cargos')
                    ->find($empleado_empresa->cargo);
                //calcular dias de vacacion
                $result = array(
                    "nombre" => $empleado->nombres . " " . $empleado->apellidos,
                    "ci" => $empleado->ci,
                    "empleado_id" => $empleado->id,
                    "unidad" => $unidad->nombre,
                    "cargo" => $cargo->nombre,
                    "fecha_ingreso" => $empleado->fecha_ingreso,
                );
            }
            return response()->json([
                'status' => ($empleado ? 0 : -1),
                'data' => $result
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    //estados de las vacaciones
    public function approve(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors(),
                    'message' => 'datos incompletos'
                ]);
            }
            $employe = PersonalPermisos::find($request['id']);
            if ($employe->estado != 0) {
                return response()->json([
                    'status' => -1,
                    'data' => [],
                    'message' => 'solo se puede aprobar una reserva'
                ]);
            }
            $employe->estado = 1; //1 aprobar, 2 en curso, 3 finalizado
            $employe->save();
            $estado = PersonalPermisos::where('empleado_id', $employe->id)->count();
            if ($estado == 0) {
                $estado = new PersonalPermisosEstado;
                $estado->empleado_id = $employe->id;
                $estado->total_disponible = 0;
                $estado->total_usado = 0;
                $estado->save();
            }
            return response()->json([
                'status' => 0,
                'data' => [],
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    public function decline(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors(),
                    'message' => 'datos incompletos'
                ]);
            }
            $employe = PersonalPermisos::find($request['id']);
            if ($employe->estado != 0) {
                return response()->json([
                    'status' => -1,
                    'data' => [],
                    'message' => 'solo se puede declinar una reserva'
                ]);
            }
            $employe->estado = 10; //
            $employe->save();
            return response()->json([
                'status' => 0,
                'data' => [],
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }

    public function registro(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors(),
                    'message' => 'datos incompletos'
                ]);
            }
            $employe = PersonalPermisos::find($request['id']);
            if ($employe->estado != 1) {
                return response()->json([
                    'status' => -1,
                    'data' => [],
                    'message' => 'solo se puede registrar una aprobacion'
                ]);
            }
            $employe->estado = 2; //
            $tmp = PersonalPermisos::where('empleado_id', $employe->empleado_id)
                ->where('estado', 2)
                ->where('id', '!=', $employe->id)
                ->orderBy('id', 'desc');
            $employe->total_disponible = PersonalPermisos::remmaining_days($employe->empleado_id) - $employe->numero_dias;
            $employe->total_usado = $employe->numero_dias;
            if ($tmp->count() > 0) {
                $tmp = $tmp->first();
                $employe->total_disponible = $tmp->total_disponible - $employe->numero_dias;
                $employe->total_usado = $tmp->total_usado + $employe->numero_dias;
            }
            $employe->save();
            return response()->json([
                'status' => 0,
                'data' => [],
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}