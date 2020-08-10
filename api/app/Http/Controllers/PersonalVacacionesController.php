<?php

namespace App\Http\Controllers;

use App\Models\PersonalVacaciones;
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
                    ->select('empleado_vacaciones.*', 'empleado.nombres', 'empleado.apellidos', 'vacaciones_tipo.tipo', 'vacaciones_tipo.tiempo_dias')
                    ->leftJoin('empleado', 'empleado_id', '=', 'empleado.id')
                    ->leftJoin('vacaciones_tipo', 'tipo_vacaciones_id', '=', 'vacaciones_tipo.id')
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
                'tipo_vacaciones_id' => 'required',
                'numero_dias' => 'required|numeric',
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
            $PersonalVacaciones = PersonalVacaciones::find($request['id'])->delete();
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
            $validator = Validator::make($request->all(), [
                'ci' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => -1,
                    'data' => $validator->errors(),
                    'message' => 'datos incompletos'
                ]);
            }
            $empleado = DB::table('empleado')
                ->where('ci', $request['ci'])
                ->whereNull('deleted_at')
                ->first();
            $result = [];
            if ($empleado) {
                $empleado_empresa = DB::table('empleado_empresa')
                    ->where('empleado', $empleado->id)
                    ->first();
                $unidad = DB::table('unidadesNegocio')
                    ->find($empleado_empresa->unidad_negocio);
                $config = days_leave_year();
                $diff_time = get_date_employe($empleado->fecha_ingreso);
                $days = 0;
                for ($i = 1; $i <= $diff_time['y']; $i++) {
                    foreach ($config as $item) {
                        if ($i >= $item['min'] && $i <= $item['max']) {
                            $days += $item['days'];
                            break;
                        }
                    }
                }

                $result = array(
                    "nombre" => $empleado->nombres . " " . $empleado->apellidos,
                    "ci" => $empleado->ci,
                    "empleado" => $empleado->id,
                    "unidad" => $unidad->nombre,
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
                'fecha_desde' => 'required',
                'fecha_hasta' => 'required',
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
            $days = getWorkdays($request['fecha_desde'], $request['fecha_hasta'], true, $daysh, $request['sabado']);
            return response()->json([
                'status' => 0,
                'data' => [
                    $days,
                ]
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([//'message' => $error->getMessage(),
                'error' => $error,], 500);
        }
    }
}
