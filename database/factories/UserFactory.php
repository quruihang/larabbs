<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

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

$factory->define(App\Models\User::class, function (Faker $faker) {
    // Faker 是一个假数据生成库
    static $password;
    // Carbon 是 PHP DateTime 的一个简单扩展
    // 这里使用 now() 和 toDateTimeString() 来创建格式如 2017-10-13 18:42:40 的时间戳。
    $now = Carbon::now()->toDateTimeString();

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('password'), // secret
        'remember_token' => str_random(10),
        // sentence() 是 faker 提供的 API ，随机生成『小段落』文本。
        'introduction' => $faker->sentence(),
        // 自行填充 created_at 和 updated_at 两个字段
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
