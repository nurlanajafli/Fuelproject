<?php

namespace garage\templates\reportMediaCategory;

use common\models\ReportMediaCategory;
use TRS\RestResponse\templates\BaseTemplate;

/**
 *
 * @OA\Schema(
 *     schema="ReportMediaCategorySmall",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="count",
 *         type="integer"
 *     )
 * )
 */
class Small extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var ReportMediaCategory $model */
        $model = $this->model;
        $this->result = [
            'id' => $model->id,
            'name' => $model->name,
            'description' => $model->description,
            'count' => $model->getReportMedia()->count() + 0,
        ];
    }
}
