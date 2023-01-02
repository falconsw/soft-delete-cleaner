<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use FalconSW\SoftDeleteCleaner\Tests\database\Models\SampleModel;
use Illuminate\Database\Eloquent\Factory;

$factory->define(SampleModel::class, function (Faker $faker) {
    return [
        'title' => $faker->text,
        'text' => $faker->text,
    ];
});
