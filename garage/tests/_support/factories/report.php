<?php

use common\enums\DefLevel;
use common\enums\FuelLevel;
use common\enums\ReportStatus;
use common\enums\ReportType;
use common\models\Driver;
use common\models\Report;
use common\models\Truck;
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define(Report::class)->setDefinitions([
    'type' => ReportType::CHECK_IN,
    'truck_id' => 'factory|' . Truck::class,
    'driver_id' => 'factory|' . Driver::class,
    'mileage' => Faker::randomNumber(5),
    'def_level' => DefLevel::EMPTY,
    'fuel_level' => FuelLevel::EMPTY,
    'status' => ReportStatus::DRAFT,
    'created_at' => Faker::iso8601(),
]);