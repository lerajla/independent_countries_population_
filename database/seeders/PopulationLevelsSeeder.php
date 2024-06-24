<?php

namespace Database\Seeders;
use DB;

use Illuminate\Database\Seeder;

class PopulationLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = [
        [
          'name' => 'low',
          'threshold' => 20000000
        ],
        [
          'name' => 'medium',
          'threshold' => 70000000
        ],
        [
          'name' => 'high',
          'threshold' => null
        ]
      ];
      DB::table('population_levels')->insert($data);
    }
}
