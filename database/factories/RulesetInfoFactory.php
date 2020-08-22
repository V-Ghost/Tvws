<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RulesetInfo;
use Faker\Generator as Faker;

$factory->define(App\RulesetInfo::class, function (Faker $faker) {
    return [
        'authority' => $faker->text(50),
        'rulesetId' => $faker->uuid,
        'maxLocationChange' => $faker->randomFloat(2,0,100),
        'maxPollingSecs' => $faker->randomDigitNotNull,
    ];
});
