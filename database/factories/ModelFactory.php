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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Style::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'description' => $faker->lastName,
        'short_code' => strtoupper($faker->lexify('????')),
        'is_active' => $faker->randomElement(['yes', 'no'])
    ];
});

$factory->define(App\Models\Option::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'description' => $faker->lastName,
        'unit_price' => $faker->randomNumber(3),
        'is_active' => $faker->randomElement(['yes', 'no'])
    ];
});

$factory->define(App\Models\BuildingModel::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->numerify('Model ###'),
        'description' => $faker->sentence,
        'width' => $faker->randomNumber(2),
        'wall_height' => $faker->randomNumber(2),
        'length' => $faker->randomNumber(2),
        'shell_price' => $faker->randomNumber(4),
        'is_active' => $faker->randomElement(['yes', 'no'])
    ];
});

$factory->define(App\Models\BuildingStatus::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'priority' => $faker->randomNumber(1),
        'is_active' => $faker->randomElement(['yes', 'no'])
    ];
});

$factory->define(App\Models\Location::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'address' => $faker->streetAddress,
        'country' => $faker->countryCode,
        'state' => $faker->stateAbbr,
        'city' => $faker->city,
        'zip' => $faker->postcode,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'is_geocoded' => $faker->randomElement(['yes', 'no']),
    ];
});

$factory->define(App\Models\Dealer::class, function (Faker\Generator $faker) {
    return [
        'business_name' => $faker->company,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
        'tax_rate' => $faker->randomNumber(2),
        'cash_sale_deposit_rate' => $faker->randomNumber(2),
        'location_id' => $faker->randomNumber(2)
    ];
});

$factory->define(App\Models\Setting::class, function (Faker\Generator $faker) {
    return [
        'id' => 'x',
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'value' => $faker->randomNumber(2)
    ];
});

$factory->define(App\Models\Color::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->colorName,
        'hex' => $faker->hexColor,
        'url' => $faker->imageUrl,
        'use_body' => $faker->randomNumber(1),
        'use_trim' => $faker->randomNumber(1),
        'use_shingle' => $faker->randomNumber(1)
    ];
});

$factory->define(App\Models\PriceGroup::class, function (Faker\Generator $faker) {
    return [
        'name'       => 'Price Group 4',
        'category'   => 'options',
        'status'     => 'archived',
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});
