<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$0WYN.TWI67yPHeTs5ocAM.VsPwdVQ.BFpFAAWJ2IGIkVFlyswli8K', // password
        'api_token' => Str::random(80),
        'remember_token' => Str::random(10),
        'is_admin' => false,
    ];
});

$factory->state(App\User::class, 'john-doe', function(){
    return [
        'name' => 'John Doe',
        'email' => 'john@laravel.test',
        'password' => '$2y$10$0WYN.TWI67yPHeTs5ocAM.VsPwdVQ.BFpFAAWJ2IGIkVFlyswli8K',
        'is_admin' => true,
    ];
});
