<?php

namespace garage\templates\report;

use common\models\Report;
use TRS\RestResponse\templates\BaseTemplate;
use Yii;

/**
 *
 * @OA\Schema(
 *     schema="ReportSmall",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="Draft"
 *     ),
 *     @OA\Property(
 *         property="posted_at",
 *         type="string",
 *         example="2021-11-18T11:15:47Z"
 *     ),
 *     @OA\Property(
 *         property="signed_at",
 *         type="string",
 *         example=null
 *     )
 * )
 */
class Small extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var Report $model */
        $model = $this->model;
        $this->result = [
            'id' => $model->id,
            'status' => $model->status,
            'posted_at' => Yii::$app->formatter->asDatetime($model->created_at),
            'signed_at' => $model->signed_at ? Yii::$app->formatter->asDatetime($model->signed_at) : null,
        ];
    }
}
