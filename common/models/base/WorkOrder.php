<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "work_order".
 *
 * @property integer $id
 * @property string $order_date
 * @property string $order_type
 * @property integer $truck_id
 * @property integer $trailer_id
 * @property integer $vendor_id
 * @property integer $odometer
 * @property string $status
 * @property string $authorized_by
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property \common\models\Trailer $trailer
 * @property \common\models\Truck $truck
 * @property \common\models\User $createdBy
 * @property \common\models\User $updatedBy
 * @property \common\models\Vendor $vendor
 * @property \common\models\WorkOrderService[] $workOrderServices
 * @property string $aliasModel
 */
abstract class WorkOrder extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'work_order';
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
            [['order_date', 'order_type', 'status'], 'required'],
            [['order_date'], 'safe'],
            [['truck_id', 'trailer_id', 'vendor_id', 'odometer'], 'default', 'value' => null],
            [['truck_id', 'trailer_id', 'vendor_id', 'odometer'], 'integer'],
            [['description'], 'string'],
            [['order_type', 'status', 'authorized_by'], 'string', 'max' => 255],
            [['trailer_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Trailer::className(), 'targetAttribute' => ['trailer_id' => 'id']],
            [['truck_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Truck::className(), 'targetAttribute' => ['truck_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Vendor::className(), 'targetAttribute' => ['vendor_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_date' => Yii::t('app', 'Order Date'),
            'order_type' => Yii::t('app', 'Order Type'),
            'truck_id' => Yii::t('app', 'Truck ID'),
            'trailer_id' => Yii::t('app', 'Trailer ID'),
            'vendor_id' => Yii::t('app', 'Vendor ID'),
            'odometer' => Yii::t('app', 'Odometer'),
            'status' => Yii::t('app', 'Status'),
            'authorized_by' => Yii::t('app', 'Authorized By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'description' => Yii::t('app', 'Description'),
        ];
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
    public function getTruck()
    {
        return $this->hasOne(\common\models\Truck::className(), ['id' => 'truck_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
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
    public function getVendor()
    {
        return $this->hasOne(\common\models\Vendor::className(), ['id' => 'vendor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkOrderServices()
    {
        return $this->hasMany(\common\models\WorkOrderService::className(), ['order_id' => 'id']);
    }




}
