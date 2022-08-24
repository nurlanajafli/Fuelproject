<?php

namespace v1\forms;

use yii\base\Model;
use Yii;

/**
 * Class Departure
 *
 * @OA\Schema(
 *     required={"date","timeIn","timeOut"}
 * )
 */
class Departure extends Model
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

    /**
     * @OA\Property(
     *     type="string",
     *     example="02:57 pm"
     * )
     */
    public $timeOut;

    /**
     * @OA\Property(
     *     type="number",
     *     format="float"
     * )
     */
    public $pieces;

    /**
     * @OA\Property(
     *     type="integer"
     * )
     */
    public $weight;

    /**
     * @OA\Property(
     *     type="string"
     * )
     */
    public $seal_no;

    /**
     * @OA\Property(
     *     type="string"
     * )
     */
    public $bol;

    public function rules()
    {
        return [
            [['date', 'timeIn', 'timeOut'], 'required'],
            [['pieces'], 'number'],
            [['weight'], 'integer'],
            [['seal_no', 'bol'], 'string', 'max' => 255],
            [['date'], 'date', 'format' => Yii::$app->params['formats']['db']],
            [['timeIn', 'timeOut'], 'time', 'format' => Yii::$app->params['formats']['12h']],
        ];
    }
}

/**
 * @OA\RequestBody(
 *     request="Departure",
 *     required=true,
 *     @OA\JsonContent(
 *         @OA\Property(
 *             property="Departure",
 *             type="object",
 *             ref="#/components/schemas/Departure"
 *         )
 *     )
 * )
 */