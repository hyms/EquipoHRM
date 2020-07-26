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
                'name' => 'admin',
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
        DB::table('usuarios')->insert([
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
        ]);
    }
}
