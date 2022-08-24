<?php

namespace v1\templates\documentCode;

use common\models\DocumentCode;
use TRS\RestResponse\templates\BaseTemplate;

/**
 *
 * @OA\Schema(
 *     schema="DocumentCodeSmall",
 *     @OA\Property(
 *         property="code",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string"
 *     )
 * )
 *
 */
class Small extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var DocumentCode $model */
        $model = $this->model;

        $this->result = [
            'code' => $model->code,
            'description' => $model->description
        ];
    }
}
