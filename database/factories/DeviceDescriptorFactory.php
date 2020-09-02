<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\DeviceDescriptor::class, function (Faker $faker) {
    return [
        'serialNumber' => $faker->uuid,
        'manufacturerId' => $faker->uuid,
        'modelId' => $faker->uuid,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        
    ];
});
