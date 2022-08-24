<?php

namespace v1\templates\load;

use common\models\Load;
use common\models\LoadStop;
use common\models\State;
use TRS\RestResponse\templates\BaseTemplate;
use v1\templates\loadStop\Small as LoadStopSmall;
use v1\templates\trailer\Small as TrailerSmall;
use v1\templates\truck\Small as TruckSmall;
use yii\helpers\ArrayHelper;

/**
 *
 * @OA\Schema(
 *     schema="LoadLarge",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="miles",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="pcs",
 *         type="number",
 *         format="float"
 *     ),
 *     @OA\Property(
 *         property="wgt",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="haz",
 *         type="boolean"
 *     ),
 *     @OA\Property(
 *         property="truck",
 *         type="object",
 *         ref="#/components/schemas/TruckSmall"
 *     ),
 *     @OA\Property(
 *         property="trailer",
 *         type="object",
 *         ref="#/components/schemas/TrailerSmall"
 *     ),
 *     @OA\Property(
 *         property="trailer2",
 *         type="object",
 *         ref="#/components/schemas/TrailerSmall"
 *     ),
 *     @OA\Property(
 *         property="notes",
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
class Large extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var Load $model */
        $model = $this->model;

        $commodity = $model->commodityCommodity;
        $states = ArrayHelper::map(State::find()->all(), 'id', 'state_code');
        $this->result = [
            'id' => $model->id,
            'miles' => $model->bill_miles,
            'pcs' => $model->commodity_pieces,
            'wgt' => $model->commodity_weight,
            'haz' => ($commodity && $commodity->hazmat_code),
            'truck' => $model->dispatchAssignment->truck ? $model->dispatchAssignment->truck->getAsArray(TruckSmall::class) : null,
            'trailer' => $model->dispatchAssignment->trailer ? $model->dispatchAssignment->trailer->getAsArray(TrailerSmall::class) : null,
            'trailer2' => $model->dispatchAssignment->trailer2 ? $model->dispatchAssignment->trailer2->getAsArray(TrailerSmall::class) : null,
            'notes' => $model->notes,
            'load_stops' => array_map(function (LoadStop $stop) use ($states) {
                return $stop->getAsArray(LoadStopSmall::class, ['states' => $states]);
            }, $model->getLoadStops()->joinWith('company')->orderBy(['stop_number' => SORT_ASC])->all())
        ];
    }
}
