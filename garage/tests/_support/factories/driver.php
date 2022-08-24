<?php

use common\models\Driver;
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define(Driver::class)->setDefinitions([
    'last_name' => Faker::lastName(),
    'first_name' => Faker::firstName(),
    'state_id' => Faker::numberBetween(1, 63),
    'maintenance' => false,
    'mail_list' => false,
]);