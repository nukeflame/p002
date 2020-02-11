<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'body' => $faker->paragraph,
        'user_id' => 1,
        'project_id' => 1,
    ];
});
