<?php

namespace garage\templates\report;

use common\helpers\Hosts;
use common\models\Report;
use common\models\ReportMedia;
use garage\templates\reportMedia\Large as ReportMediaLarge;
use TRS\RestResponse\templates\BaseTemplate;
use Yii;

/**
 *
 * @OA\Schema(
 *     schema="ReportLarge",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="driver_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="def_level",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="fuel_level",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="truck_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="trailer_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="mileage",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="media",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ReportMediaLarge")
 *     ),
 *     @OA\Property(
 *         property="posted_at",
 *         type="string",
 *         example="2021-11-18T11:15:47Z"
 *     ),
 *     @OA\Property(
 *         property="signed_at",
 *         type="string",
 *         example="2021-11-18T11:15:47Z"
 *     ),
 *     @OA\Property(
 *         property="driver",
 *         type="object",
 *         ref="#/components/schemas/DriverSmall"
 *     ),
 *     @OA\Property(
 *         property="flags",
 *         type="object",
 *     ),
 *     @OA\Property(
 *         property="sign",
 *         type="string",
 *     ),
 *
 * )
 */
class Large extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var Report $model */
        $model = $this->model;
        $this->result = [
            'id' => $model->id,
            'status' => $model->status,
            'driver_id' => $model->driver_id,
            'type' => $model->type,
            'def_level' => $model->def_level,
            'fuel_level' => $model->fuel_level,
            'truck_id' => $model->truck_id,
            'trailer_id' => $model->trailer_id,
            'mileage' => $model->mileage,
            'media' => array_map(function (ReportMedia $reportMedia) {
                return $reportMedia->getAsArray(ReportMediaLarge::class);
            }, $model->reportMedia),
            'posted_at' => Yii::$app->formatter->asDatetime($model->created_at),
            'signed_at' => $model->signed_at ? Yii::$app->formatter->asDatetime($model->signed_at) : null,
            'driver' => $model->driver ? $model->driver->getAsArray(\garage\templates\driver\Small::class) : null,
            'flags' => $model->reportFlags,
            'sign' => $model->getReportMedia()->primaryModel->signature_file ? Hosts::getImageCdn()."/".$model->getReportMedia()->primaryModel->signature_file : null,
        ];
    }
}