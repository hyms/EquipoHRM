<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FuncionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funciones')->insert([
            [
                'funcion' => 'usuarios',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'organizacion',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'personal',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'tipopermisos',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'permisos',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'permisos',
                'type' => '1',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'permisos',
                'type' => '2',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'permisos',
                'type' => '3',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'diasfestivos',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'vacaciones',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'vacaciones',
                'type' => '1',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'vacaciones',
                'type' => '2',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'vacaciones',
                'type' => '3',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'rules',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'regional',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'gerencia',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'areastrabajo',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'unidadesnegocio',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'cargos',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'personal',
                'type' => '0',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
