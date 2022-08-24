<?php

use common\models\User;
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define(User::class)->setDefinitions([
    'auth_key' => 'auth_key',
    'password' => 'password',
    'email' => Faker::email(),
    'status' => User::STATUS_ACTIVE,
    'last_name' => Faker::lastName(),
    'first_name' => Faker::firstName(),
]);