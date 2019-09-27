<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'content' => $faker->text,
        'created_at' => $faker->dateTimeBetween('-3 months'),
        'user_id' => User::all()->random()->id,
    ];
});
