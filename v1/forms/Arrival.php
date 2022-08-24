<?php

namespace v1\forms;

use yii\base\Model;
use Yii;

/**
 * Class Arrival
 *
 * @OA\Schema(
 *     required={"date","timeIn"}
 * )
 */
class Arrival extends Model
{
    /**
     * @OA\Property(
     *     type="string",
     *     example="2021-03-23"
     * )
    */
    public $date;

    /**
     * @OA\Property(
     *     type="string",
     *     example="01:57 pm"
     * )
     */
    public $timeIn;

    public function rules()
    {
        return [
            [['date', 'timeIn'], 'required'],
            [['date'], 'date', 'format' => Yii::$app->params['formats']['db']],
            [['timeIn'], 'time', 'format' => Yii::$app->params['formats']['12h']],
        ];
    }
}

/**
 * @OA\RequestBody(
 *     request="Arrival",
 *     required=true,
 *     @OA\JsonContent(
 *         @OA\Property(
 *             property="Arrival",
 *             type="object",
 *             ref="#/components/schemas/Arrival"
 *         )
 *     )
 * )
 */
