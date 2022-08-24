<?php

namespace garage\templates\driver;

use common\models\Driver;
use TRS\RestResponse\templates\BaseTemplate;

/**
 *
 * @OA\Schema(
 *     schema="DriverSmall",
 *     @OA\Property(
 *         property="driver_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="full_name",
 *         type="string"
 *     )
 * )
 *
 */
class Small extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var Driver $model */
        $model = $this->model;
        $this->result = [
            'driver_id' => $model->id,
            'full_name' => $model->fullName
        ];
    }
}
