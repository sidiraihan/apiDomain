<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 3) as $loop) {
            DB::table('domains')->insert([
                'name' => Str::random(3).'.com',
                'status' => 'active',
                'owner' => '1',
                'expiration_date' => Carbon::create('2020', '12', '01')
            ]);
        }
    }
}
