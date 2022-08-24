<?php

namespace v1\templates\location;

use common\models\Location;
use TRS\RestResponse\templates\BaseTemplate;
use yii\helpers\ArrayHelper;

/**
 *
 * @OA\Schema(
 *     schema="LocationSmall",
 *     @OA\Property(
 *        property="name",
 *        type="string"
 *     ),
 *     @OA\Property(
 *        property="address",
 *        type="string"
 *     ),
 *     @OA\Property(
 *        property="city",
 *        type="string"
 *     ),
 *     @OA\Property(
 *        property="state_abbreviation",
 *        type="string"
 *     ),
 *     @OA\Property(
 *        property="zip",
 *        type="string"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string"
 *     )
 * )
 *
 */
class Small extends BaseTemplate
{
    public $config;

    protected function prepareResult()
    {
        /** @var Location $model */
        $model = $this->model;

        $this->result = [
            'name' => $model->company_name,
            'address' => $model->address,
            'city' => $model->city,
            'state_abbreviation' => ArrayHelper::getValue($this->config, 'state_abbreviation', null),
            'zip' => $model->zip,
            'phone' => $model->main_phone,
        ];
    }
}
