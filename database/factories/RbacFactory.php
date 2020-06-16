<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use HuangYi\Rbac\Permission;
use HuangYi\Rbac\Role;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});
