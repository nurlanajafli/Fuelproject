<?php

namespace garage\forms;

use common\enums\ReportFlag;
use yii\base\Model;

class ReportSign extends Model
{
    public $file;
    public $flags;

    public function rules()
    {
        return [
            [['flags'], 'default', 'value' => []],
            [['flags'], 'each', 'rule' => ['string']],
            [['flags'], 'each', 'rule' => ['in', 'range' => ReportFlag::getEnums()]],
        ];
    }
}

/**
 * @OA\RequestBody(
 *     request="ReportSign",
 *     required=true,
 *     @OA\MediaType(
 *         mediaType="multipart/form-data",
 *         encoding={
 *             "ReportSign[flags][]": {
 *                 "explode": true
 *             }
 *         },
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="ReportSign[file]",
 *                 type="string",
 *                 format="binary"
 *             ),
 *             @OA\Property(
 *                 property="ReportSign[flags][]",
 *                 type="array",
 *                 @OA\Items(
 *                     type="string"
 *                 ),
 *                 example={"IFTA", "NY Sticker"}
 *             )
 *         )
 *     )
 * )
 */
