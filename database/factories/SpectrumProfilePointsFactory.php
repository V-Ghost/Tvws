<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\SpectrumProfilePoints::class, function (Faker $faker) {
    return [
        'dbm' => $faker->randomFloat(2,0,100),  
        'hz' => $faker->randomFloat(2,0,100),  
    ];
});
