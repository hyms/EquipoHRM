<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        DB::table('roles')->insert([
            [
                'name' => 'Administrador',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'RecursosHumanos',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Gerente',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Encargado/Jefe',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
        DB::table('usuarios')->insert([
            [
                'username' => 'userhrm',
                'password' => Hash::make('123456'),
                'alias' => 'EquipoHRM',
                'api_token' => '',
                'remember_token' => '',
                'estado' => 1,
                'rol' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
        /*DB::table('funciones')->insert([
            [
                'username' => 'usehrm',
                'password' => Hash::make('123456'),
                'alias' => 'EquipoHRM',
                'api_token' => '',
                'remember_token' => '',
                'estado' => 1,
                'rol' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);*/
    }
}
