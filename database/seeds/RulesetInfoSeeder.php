<?php

use Illuminate\Database\Seeder;

class RulesetInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\RulesetInfo::class, 6)->create();
    }
}
