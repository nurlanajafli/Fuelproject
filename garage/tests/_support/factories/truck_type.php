<?php

use common\models\TruckType;
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define(TruckType::class)->setDefinitions([
    'type' => Faker::word(),
    'max_weight' => Faker::randomNumber(4, true),
    'axles' => Faker::randomDigitNotNull(),
    'height' => Faker::randomNumber(2, true),
]);