<?php

namespace v1\templates\document;

use common\enums\DocumentThumb;
use common\models\Document;
use TRS\RestResponse\templates\BaseTemplate;

/**
 *
 * @OA\Schema(
 *     schema="DocumentSmall",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="url",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="string"
 *     )
 * )
 *
 */
class Small extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var Document $model */
        $model = $this->model;

        $this->result = [
            'id' => $model->id,
            'url' => $model->getThumbUploadUrl($model->getImageAttribute(), DocumentThumb::SMALL),
            'code' => $model->code
        ];
    }
}
