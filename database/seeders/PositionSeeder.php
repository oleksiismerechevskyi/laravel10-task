<?php

namespace Database\Seeders;

use Date;
use DB;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insert([
            [
                'name' => 'Lawyer',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Content manager',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Security',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Designer',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
        ]);
    }
}
