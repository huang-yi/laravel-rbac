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

$factory->define(HuangYi\Rbac\Tests\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(HuangYi\Rbac\Models\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->unique()->slug,
        'description' => 'This is a role.',
    ];
});

$factory->define(HuangYi\Rbac\Models\Permission::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->unique()->slug,
        'description' => 'This is a permission .',
    ];
});
