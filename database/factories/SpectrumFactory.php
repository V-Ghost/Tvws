<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Spectrums::class, function (Faker $faker) {
    return [
        
        'resolutionBwHz' => $faker->randomFloat(2,0,100),  
         
    ];
});
