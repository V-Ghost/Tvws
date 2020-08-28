<?php

use Illuminate\Database\Seeder;

class SpectrumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Spectrums::class, 6)->create(); 
    }
}
