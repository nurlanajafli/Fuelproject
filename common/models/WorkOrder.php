<?php

namespace common\models;

use common\enums\WorkOrderStatus;
use common\enums\WorkOrderType;
use common\models\base\WorkOrder as BaseWorkOrder;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "work_order".
 *
 * @property-read string total
 */
class WorkOrder extends BaseWorkOrder
{
    public static function tableName()
    {
        return '{{%work_order}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
            ],
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('CURRENT_TIMESTAMP'),
            ],
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['order_type', 'status'], 'required'],
            [['status'], 'default', 'value' => WorkOrderStatus::OPEN],
            [['order_type'], 'in', 'range' => WorkOrderType::getEnums()],
            [['status'], 'in', 'range' => WorkOrderStatus::getEnums()],
            /*[['truck_id'], 'required', 'when' => function ($model) {
                return $model->order_type == WorkOrderType::TRUCK;
            }],
            [['trailer_id'], 'required', 'when' => function ($model) {
                return $model->order_type == WorkOrderType::TRAILER;
            }],*/
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Yii::t('app', 'WO No'),
            'order_date' => Yii::t('app', 'WO Date'),
            'order_type' => Yii::t('app', 'Unit Type'),
            'truck_id' => Yii::t('app', 'Unit No'),
            'trailer_id' => Yii::t('app', 'Unit No'),
            'vendor_id' => Yii::t('app', 'Serviced By'),
            'authorized_by' => Yii::t('app', 'Authorized By'),
            'odometer' => Yii::t('app', 'Odometer'),
        ]);
    }

    public function gettotal()
    {
        return 0;
    }
}
