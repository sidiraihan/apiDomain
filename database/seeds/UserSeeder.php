<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'reseller1',
            'password' => Hash::make('password'),
            'idkey' => Str::random(20).'token',
        ]);
    }
}
