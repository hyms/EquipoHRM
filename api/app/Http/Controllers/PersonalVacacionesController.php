<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\PersonalVacaciones;
use App\Models\PersonalVacacionesEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonalVacacionesController extends Controller
{
    public function get(Request $request)
    {
        try {
            if (empty($request->all())) {
                //$PersonalVacaciones = PersonalVacaciones::all();
                $PersonalVacaciones = DB::table('empleado_vacaciones')
                    ->select('empleado_vacaciones.*', 'empleado.nombres', 'empleado.apellidos')
                    ->leftJoin('empleado', 'empleado_id', '=', 'empleado.id')
                    ->whereNull('empleado_vacaciones.deleted_at')
                    ->get();
                return response()->json([
                    'status' => 0,
                    'data' => [
                        'all' => $PersonalVacaciones,
                        'count' => $PersonalVacaciones->count(),
                    ]]);
            }
            $vacaciones = PersonalVacaciones::find($request->get('id'));
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
                'numero_dias' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors()]);
            }
            $PersonalVacaciones = new PersonalVacaciones;
            if (!empty($request['id'])) {
                $PersonalVacaciones = PersonalVacaciones::find($request['id']);
            }
            $PersonalVacaciones->fill($request->all());
            $tmp = PersonalVacaciones::where('empleado_id', $request['empleado_id'])
                ->where('estado', 2)
                ->orderBy('id', 'desc');
            if (!empty($request['id'])) {
                $tmp = $tmp->where('id', '!=', $request['id']);
            }
            $PersonalVacaciones->total_disponible = PersonalVacaciones::remmaining_days($request['empleado_id']);
            $PersonalVacaciones->total_usado = 0;
            if ($tmp->count() > 0) {
                $tmp = $tmp->first();
                $PersonalVacaciones->total_disponible = $tmp->total_disponible - $request['numero_dias'];
                $PersonalVacaciones->total_usado = $tmp->total_usado + $request['numero_dias'];
            }
            $PersonalVacaciones->save();
            return response()->json([
                'status' => 0,
                'data' => $PersonalVacaciones
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
            $PersonalVacaciones = PersonalVacaciones::where('id', $request['id'])
                ->where('estado', 0);
            if ($PersonalVacaciones->count() == 0) {
                return response()->json([
                    'status' => -1,
                    'data' => [],
                    'mensaje' => 'No se puede eliminar'
                ]);
            }
            $PersonalVacaciones->delete();
            return response()->json([
                'status' => ($PersonalVacaciones ? 0 : -1),
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
                $days = PersonalVacaciones::remmaining_days($empleado->id);
                $diff_time = get_date_employe($empleado->fecha_ingreso);
                $result = array(
                    "nombre" => $empleado->nombres . " " . $empleado->apellidos,
                    "ci" => $empleado->ci,
                    "empleado_id" => $empleado->id,
                    "unidad" => $unidad->nombre,
                    "cargo" => $cargo->nombre,
                    "fecha_ingreso" => $empleado->fecha_ingreso,
                    "disponible" => $days,
                    "ano_cumplido" => $diff_time['y'],
                    "sabado" => type_employe($empleado_empresa->tipo_empleado),
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

    public function getDaysWork(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
                'sabado' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors(),
                    'message' => 'datos incompletos'
                ]);
            }
            $holidays = DB::table('dias_festivos')
                ->select('fecha')
                ->whereNull('deleted_at')
                ->get();
            $daysh = [];
            foreach ($holidays as $day) {
                array_push($daysh, $day->fecha);
            }
            $days = get_work_days($request['fecha_inicio'], $request['fecha_fin'], true, $daysh, $request['sabado']);
            return response()->json([
                'status' => 0,
                'data' => $days,
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
            $employe = PersonalVacaciones::find($request['id']);
            if ($employe->estado != 0) {
                return response()->json([
                    'status' => -1,
                    'data' => [],
                    'message' => 'solo se puede aprobar una reserva'
                ]);
            }
            $employe->estado = 1; //1 aprobar, 2 en curso, 3 finalizado
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
            $employe = PersonalVacaciones::find($request['id']);
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
            $employe = PersonalVacaciones::find($request['id']);
            if ($employe->estado != 1) {
                return response()->json([
                    'status' => -1,
                    'data' => [],
                    'message' => 'solo se puede registrar una aprobacion'
                ]);
            }
            $employe->estado = 2; //
            $tmp = PersonalVacaciones::where('empleado_id', $employe->empleado_id)
                ->where('estado', 2)
                ->where('id', '!=', $employe->id)
                ->orderBy('updated_at', 'desc');
            $employe->total_disponible = PersonalVacaciones::remmaining_days($employe->empleado_id) - $employe->numero_dias;
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
