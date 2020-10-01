<?php

use Illuminate\Database\Seeder;

class DeviceDescriptorClient extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\DeviceDescriptorClient::class, 10)->create();
    }
}
