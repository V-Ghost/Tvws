<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\DeviceDescriptorClient::class, function (Faker $faker) {
    return [
    
        'serialNumber' => $faker->uuid,
        'manufacturerId' => $faker->uuid,
        'modelId' => $faker->uuid,
        'deviceType' => $faker->word,
        'phoneNumber' => $faker->phoneNumber,
        'password' => $faker->word,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'username' => $faker->firstName,
        'region' => $faker->state,
        'district' => $faker->city,
        'operator' => $faker->company,
        'radiatedpower' => $faker->randomFloat(2,0,100),
        'conductedpower' => $faker->randomFloat(2,0,100),
        'antennaheight' => $faker->randomFloat(2,0,100),
        'antennaheighttype' => $faker->randomFloat(2,0,100),
        'deviceId' => $faker->uuid,
    ];
});
