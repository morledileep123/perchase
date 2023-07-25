<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\vendor;
use Faker\Generator as Faker;

$factory->define(Vendor::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'mobile' => $faker->phoneNumber,
        'address' => $faker->address,
    ];
});