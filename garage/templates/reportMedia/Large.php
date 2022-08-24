<?php

namespace garage\templates\reportMedia;

use common\enums\ReportMediaThumb;
use common\models\ReportMedia;
use TRS\RestResponse\templates\BaseTemplate;
use yii\imagine\Image;

/**
 *
 * @OA\Schema(
 *     schema="ReportMediaLarge",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="side",
 *         type="string",
 *         example="Front"
 *     ),
 *     @OA\Property(
 *         property="is_major",
 *         type="boolean",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="is_interior",
 *         type="boolean",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="mime_type",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="damage_type",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="original",
 *         type="object",
 *         @OA\Property(
 *             property="url",
 *             type="string"
 *         ),
 *         @OA\Property(
 *             property="width",
 *             type="integer"
 *         ),
 *         @OA\Property(
 *             property="height",
 *             type="integer"
 *         ),
 *         @OA\Property(
 *             property="size",
 *             type="integer"
 *         )
 *     ),
 *     @OA\Property(
 *         property="thumbnails",
 *         type="object",
 *         @OA\Property(
 *             property="preview",
 *             type="object",
 *             @OA\Property(
 *                 property="url",
 *                 type="string"
 *             ),
 *             @OA\Property(
 *                 property="width",
 *                 type="integer"
 *             ),
 *             @OA\Property(
 *                 property="height",
 *                 type="integer"
 *             ),
 *             @OA\Property(
 *                 property="size",
 *                 type="integer"
 *             )
 *         )
 *     ),
 *     @OA\Property(
 *         property="category",
 *         type="object",
 *         ref="#/components/schemas/ReportMediaCategorySmall"
 *     )
 * )
 */
class Large extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var ReportMedia $model */
        $model = $this->model;
        $thumbnails = [];
        $sizeMap = ReportMediaThumb::getSizeMap();
        foreach ($sizeMap as $key => $options) {
            $filePath = $model->getThumbUploadPath($model->getImageAttribute(), $key);
            $image = Image::getImagine()->open($filePath);
            $imageBox = $image->getSize();
            $thumbnails[$key] = [
                'url' => $model->getThumbUploadUrl($model->getImageAttribute(), $key),
                'width' => $imageBox->getWidth(),
                'height' => $imageBox->getHeight(),
                'size' => filesize($filePath),
            ];
        }
        $this->result = [
            'id' => $model->id,
            'side' => $model->side,
            'is_major' => $model->is_major,
            'is_interior' => $model->is_interior,
            'description' => $model->description,
            'mime_type' => $model->mime_type,
            'damage_type' => $model->damage_type,
            'original' => [
                'url' => $model->getUploadUrl($model->getImageAttribute()),
                'width' => $model->width,
                'height' => $model->height,
                'size' => $model->size,
            ],
            'thumbnails' => $thumbnails,
            'category' => $model->category ? $model->category->getAsArray(\garage\templates\reportMediaCategory\Small::class) : null,
        ];
    }
}
