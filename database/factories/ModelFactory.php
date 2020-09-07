<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Illuminate\Support\Str;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $name = $faker->name;
    return [
        'name'              => $name,
        'email'             => 'fakerExample@temso.com',
        'email_verified_at' => date('Y-m-d H:i:s'),
        'password'          => bcrypt('sample'),
        'remember_token'    => Str::random(10),
        'created_at'        => date('Y-m-d H:i:s'),
        'updated_at'        => date('Y-m-d H:i:s'),
    ];
});
