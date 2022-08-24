<?php

namespace garage\forms;

use common\enums\DefLevel;
use common\enums\FuelLevel;
use common\enums\ReportType;
use common\models\Driver;
use common\models\Trailer;
use common\models\Truck;
use yii\base\Model;
use Yii;

/**
 * Class ReportCreate
 *
 * @OA\Schema(
 *     required={"type","driverId","dateTime","mileage","defLevel","fuelLevel"}
 * )
 */
class ReportCreate extends Model
{
    /**
     * @OA\Property(
     *     type="integer"
     * )
     */
    public $truckId;

    /**
     * @OA\Property(
     *     type="integer"
     * )
     */
    public $trailerId;

    /**
     * @OA\Property(
     *     type="string"
     * )
     */
    public $type;

    /**
     * @OA\Property(
     *     type="integer"
     * )
     */
    public $driverId;

    /**
     * @OA\Property(
     *     type="string",
     *     example="2021-11-18T11:15:47-0800, 2021-11-18T11:15:47Z"
     * )
     */
    public $dateTime;

    /**
     * @OA\Property(
     *     type="integer"
     * )
     */
    public $mileage;

    /**
     * @OA\Property(
     *     type="string"
     * )
     */
    public $defLevel;

    /**
     * @OA\Property(
     *     type="string"
     * )
     */
    public $fuelLevel;

    public $timestamp;

    public function rules()
    {
        return [
            [['type', 'driverId', 'dateTime', 'mileage', 'defLevel', 'fuelLevel'], 'required'],
            [['type'], 'in', 'range' => ReportType::getEnums()],
            [['truckId'], 'required', 'when' => function ($model) {
                return empty($model->trailerId);
            }],
            [['trailerId'], 'required', 'when' => function ($model) {
                return empty($model->truckId);
            }],
            [['truckId', 'trailerId', 'driverId', 'mileage'], 'integer'],
            [['truckId'], 'exist', 'skipOnError' => true, 'targetClass' => Truck::class, 'targetAttribute' => ['truckId' => 'id']],
            [['trailerId'], 'exist', 'skipOnError' => true, 'targetClass' => Trailer::class, 'targetAttribute' => ['trailerId' => 'id']],
            [['driverId'], 'exist', 'skipOnError' => true, 'targetClass' => Driver::class, 'targetAttribute' => ['driverId' => 'id']],
            [['dateTime'], 'datetime', 'format' => Yii::$app->params['formats']['ISO8601'], 'timestampAttribute' => 'timestamp', 'timestampAttributeFormat' => "yyyy-MM-dd HH':'mm':'ss"],
            [['defLevel'], 'in', 'range' => DefLevel::getEnums()],
            [['fuelLevel'], 'in', 'range' => FuelLevel::getEnums()],
        ];
    }

    public function afterValidate()
    {
        parent::afterValidate();
        if ($this->hasErrors() == false) {
            if ($this->truckId) {
                $this->trailerId = null;
            }
        }
    }
}

/**
 * @OA\RequestBody(
 *     request="ReportCreate",
 *     required=true,
 *     @OA\JsonContent(
 *         @OA\Property(
 *             property="ReportCreate",
 *             type="object",
 *             ref="#/components/schemas/ReportCreate"
 *         )
 *     )
 * )
 */