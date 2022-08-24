<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "unit".
 *
 * @property integer $id
 * @property string $active
 * @property integer $driver_id
 * @property integer $co_driver_id
 * @property integer $truck_id
 * @property integer $trailer_id
 * @property integer $trailer_2_id
 * @property integer $office_id
 * @property string $notes
 * @property string $status
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $created_by
 * @property string $created_at
 *
 * @property \common\models\DispatchAssignment[] $dispatchAssignments
 * @property \common\models\LoadMovement[] $loadMovements
 * @property \common\models\TrackingLog[] $trackingLogs
 * @property \common\models\Driver $driver
 * @property \common\models\Driver $coDriver
 * @property \common\models\Office $office
 * @property \common\models\Trailer $trailer
 * @property \common\models\Trailer $trailer2
 * @property \common\models\Truck $truck
 * @property \common\models\User $updatedBy
 * @property \common\models\User $createdBy
 * @property string $aliasModel
 */
abstract class Unit extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active'], 'safe'],
            [['driver_id', 'co_driver_id', 'truck_id', 'trailer_id', 'trailer_2_id', 'office_id'], 'default', 'value' => null],
            [['driver_id', 'co_driver_id', 'truck_id', 'trailer_id', 'trailer_2_id', 'office_id'], 'integer'],
            [['notes'], 'string'],
            [['status'], 'string', 'max' => 25],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Driver::className(), 'targetAttribute' => ['driver_id' => 'id']],
            [['co_driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Driver::className(), 'targetAttribute' => ['co_driver_id' => 'id']],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Office::className(), 'targetAttribute' => ['office_id' => 'id']],
            [['trailer_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Trailer::className(), 'targetAttribute' => ['trailer_id' => 'id']],
            [['trailer_2_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Trailer::className(), 'targetAttribute' => ['trailer_2_id' => 'id']],
            [['truck_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Truck::className(), 'targetAttribute' => ['truck_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['created_by' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'active' => Yii::t('app', 'Active'),
            'driver_id' => Yii::t('app', 'Driver ID'),
            'co_driver_id' => Yii::t('app', 'Co Driver ID'),
            'truck_id' => Yii::t('app', 'Truck ID'),
            'trailer_id' => Yii::t('app', 'Trailer ID'),
            'trailer_2_id' => Yii::t('app', 'Trailer 2 ID'),
            'office_id' => Yii::t('app', 'Office ID'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'notes' => Yii::t('app', 'Notes'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispatchAssignments()
    {
        return $this->hasMany(\common\models\DispatchAssignment::className(), ['unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoadMovements()
    {
        return $this->hasMany(\common\models\LoadMovement::className(), ['unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrackingLogs()
    {
        return $this->hasMany(\common\models\TrackingLog::className(), ['unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriver()
    {
        return $this->hasOne(\common\models\Driver::className(), ['id' => 'driver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoDriver()
    {
        return $this->hasOne(\common\models\Driver::className(), ['id' => 'co_driver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(\common\models\Office::className(), ['id' => 'office_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrailer()
    {
        return $this->hasOne(\common\models\Trailer::className(), ['id' => 'trailer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrailer2()
    {
        return $this->hasOne(\common\models\Trailer::className(), ['id' => 'trailer_2_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTruck()
    {
        return $this->hasOne(\common\models\Truck::className(), ['id' => 'truck_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }




}
