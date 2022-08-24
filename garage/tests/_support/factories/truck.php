<?php

use common\enums\TruckStatus;
use common\models\Truck;
use common\models\TruckType;
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define(Truck::class)->setDefinitions([
    'type' => function ($object, $saved) use ($fm) {
        $truckType = $fm->create(TruckType::class);
        return $truckType->type;
    },
    'status' => TruckStatus::AVAILABLE,
    'truck_no' => Faker::numerify(),
]);