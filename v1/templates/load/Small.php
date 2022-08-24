<?php

namespace v1\templates\load;

use common\models\Load;
use common\models\LoadMovement;
use common\models\LoadStop;
use TRS\RestResponse\templates\BaseTemplate;
use yii\helpers\ArrayHelper;

/**
 *
 * @OA\Schema(
 *     schema="LoadSmall",
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
 *         property="miles_to_destination",
 *         type="integer"
 *     )
 * )
 *
 */
class Small extends BaseTemplate
{
    public $config;

    protected function prepareResult()
    {
        /** @var Load $model */
        $model = $this->model;

        $formatter = ArrayHelper::getValue($this->config, 'formatter');
        $pickup = $delivery = $deliveryStopNumber = null;
        /** @var LoadStop[] $array1 */
        $array1 = [];
        foreach ($model->loadStops as $stop) {
            if (($stop->stop_number == 1) && $stop->available_from) {
                $pickup = $formatter->asDate($stop->available_from, 'MM/dd');
            } elseif (($stop->stop_number != 1) && $stop->available_from && (is_null($deliveryStopNumber) || ($stop->stop_number < $deliveryStopNumber))) {
                $delivery = $formatter->asDate($stop->available_from, 'MM/dd');
                $deliveryStopNumber = $stop->stop_number;
            }
            $array1[$stop->id] = $stop;
        }
        $milesToDestination = intval($model->dispatchAssignment->driver_empty_miles);
        $array2 = array_map(function (LoadMovement $m) use ($array1) {
            return $m->load_stop_id && ArrayHelper::keyExists($m->load_stop_id, $array1) ? [
                'stop_number' => $array1[$m->load_stop_id]->stop_number,
                'arrived_time_out' => $m->arrived_time_out,
                'miles_to_next' => $array1[$m->load_stop_id]->miles_to_next
            ] : [];
        }, $model->loadMovements);
        ArrayHelper::multisort($array2, 'stop_number', SORT_ASC);
        foreach ($array2 as $value) {
            if (empty($value)) {
                continue;
            }
            if (empty($value['arrived_time_out'])) {
                break;
            }
            $milesToDestination = intval($value['miles_to_next']);
        }
        $this->result = [
            'id' => $model->id,
            'status' => $model->status,
            'pu' => $pickup,
            'del' => $delivery,
            'miles_to_destination' => $milesToDestination
        ];
    }
}
