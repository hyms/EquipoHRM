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
                'type' => '1',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'organizacion',
                'type' => '1',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'personal',
                'type' => '1',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'tipopermisos',
                'type' => '1',
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
                'funcion' => 'diasfestivos',
                'type' => '1',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'funcion' => 'vacaciones',
                'type' => '1',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
