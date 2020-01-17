<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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



// $factory->define(config('roles.models.defaultUser'), function (Faker $faker) {
//     return [
//         'name' => $faker->name,
//         'email' => $faker->unique()->safeEmail,
//         'email_verified_at' => now(),
//         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
//         'remember_token' => Str::random(10),
//     ];
// });

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'author_id' => App\User::all()->random()->id,
        'title'   => $faker->sentence(),
        'content' => $faker->paragraph(),
    ];
});

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'author_id' => App\User::all()->random()->id,
        'parent_id' => App\Article::all()->random()->id,
        // 'commentable_id' => 'App\Article',
        // 'author_id' => $faker->randomElement($users->pluck('id')->all()),
        // 'parent_id' => $faker->randomElement($articles->pluck('id')->all())
        'title'   => $faker->sentence,
        'content' => $faker->paragraph,
    ];
});

$factory->define(App\Tag::class, function (Faker $faker) {
    $name = ucfirst($faker->optional(0.9, 'Laravel')->word);

    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});

$factory->define(App\Attachment::class, function (Faker $faker) {
    return [
        'name' => sprintf("%s.%s", str_random(), $faker->randomElement(['png', 'jpg'])),
    ];
});