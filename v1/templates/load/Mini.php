<?php

namespace v1\templates\load;

use common\models\Load;
use common\models\LoadStop;
use TRS\RestResponse\templates\BaseTemplate;
use v1\templates\loadStop\Small;
use yii\helpers\ArrayHelper;

/**
 *
 * @OA\Schema(
 *     schema="LoadMini",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="pu",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="del",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="load_stops",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/LoadStopSmall")
 *     )
 * )
 *
 */
class Mini extends BaseTemplate
{
    public $config;

    protected function prepareResult()
    {
        /** @var Load $model */
        $model = $this->model;

        $formatter = ArrayHelper::getValue($this->config, 'formatter');
        $pickup = $delivery = $deliveryStopNumber = null;
        foreach ($model->loadStops as $stop) {
            if (($stop->stop_number == 1) && $stop->available_from) {
                $pickup = $formatter->asDate($stop->available_from, 'MM/dd');
            } elseif (($stop->stop_number != 1) && $stop->available_from && (is_null($deliveryStopNumber) || ($stop->stop_number < $deliveryStopNumber))) {
                $delivery = $formatter->asDate($stop->available_from, 'MM/dd');
                $deliveryStopNumber = $stop->stop_number;
            }
        }
        $states = ArrayHelper::getValue($this->config, 'states', []);
        $this->result = [
            'id' => $model->id,
            'status' => $model->status,
            'pu' => $pickup,
            'del' => $delivery,
            'load_stops' => array_map(function (LoadStop $stop) use ($states) {
                return $stop->getAsArray(Small::class, ['states' => $states]);
            }, $model->loadStops)
        ];
    }
}
