<?php

use Illuminate\Database\Seeder;

class DeviceDescriptorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\DeviceDescriptor::class, 10)->create();
    }
}
