<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Author;
use Faker\Generator as Faker;

$factory->define(Author::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->afterCreating(Author::class, function($author)
{
    $author->profile()->save(factory(App\Profile::class)->make());
});

// $factory->afterMaking(Author::class, function($author)
// {
//     $author->profile()->save(factory(App\Profile::class)->make());
// });