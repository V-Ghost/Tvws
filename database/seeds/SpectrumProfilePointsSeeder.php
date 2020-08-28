<?php

use Illuminate\Database\Seeder;

class SpectrumProfilePointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\SpectrumProfilePoints::class, 6)->create(); 
    }
}
