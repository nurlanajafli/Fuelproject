<?php

namespace common\models;

use common\enums\CompanyOwnedLeased;
use common\enums\TrailerStatus;
use common\helpers\DateTime;
use common\models\base\Trailer as BaseTrailer;
use common\models\traits\Template;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "trailer".
 */
class Trailer extends BaseTrailer
{
    use Template;

    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->status = TrailerStatus::AVAILABLE;
        }
    }

    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['company_owned_leased'], 'in', 'range' => CompanyOwnedLeased::getEnums()],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'year' => Yii::t('app', 'Year'),
            'make' => Yii::t('app', 'Make'),
            'model' => Yii::t('app', 'Model'),
            'tare' => Yii::t('app', 'Tare'),
            'length' => Yii::t('app', 'Length'),
            'height' => Yii::t('app', 'Height'),
            'in_svc' => Yii::t('app', 'In Svc'),
            'status' => Yii::t('app', 'Status'),
            'serial' => Yii::t('app', 'Serial'),
            'license' => Yii::t('app', 'License'),
            'license_state_id' => Yii::t('app', 'License State ID'),
            'carb_id' => Yii::t('app', 'Carb ID'),
            'office_id' => Yii::t('app', 'Office'),
            'notes' => Yii::t('app', 'Notes'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'out_of_service' => Yii::t('app', 'Out Of Service'),
            'marked_as_down' => Yii::t('app', 'Marked As Down'),
        ];
    }

    public function get_label()
    {
        return sprintf('%s (%s %s)', $this->trailer_no, $this->make, $this->model);
    }
}
