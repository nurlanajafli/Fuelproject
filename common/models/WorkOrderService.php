<?php

namespace common\models;

use common\models\base\WorkOrderService as BaseWorkOrderService;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "work_order_service".
 */
class WorkOrderService extends BaseWorkOrderService
{

    public static function tableName()
    {
        return '{{%work_order_service}}';
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

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
	    'service_date' => Yii::t('app', 'Service Date'),
	    'service_code' => Yii::t('app', 'Service Code'),
	    'vendor_id' => Yii::t('app', 'Serviced By'),
	    'description' => Yii::t('app', 'Service Description'),
        ]);
    }

    public function beforeSave($insert) {if (!$this->service_code) $this->service_code=null; return parent::beforeSave($insert);}
}
