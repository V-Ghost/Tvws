<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RulesetInfoSeeder::class);
        $this->call(DeviceDescriptorSeeder::class);
        $this->call(DeviceDescriptorClient::class);
        // $this->call(SpectrumSeeder::class);
    }
}
