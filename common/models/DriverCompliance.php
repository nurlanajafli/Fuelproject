<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\DriverCompliance as BaseDriverCompliance;
use Yii;

/**
 * This is the model class for table "driver_compliance".
 */
class DriverCompliance extends BaseDriverCompliance
{
    public $cdl_expires_diff;
    public $haz_mat_expires_diff;
    public $twic_exp_diff;
    public $last_drug_test_diff;
    public $last_alcohol_test_diff;
    public $work_auth_expires_diff;
    public $next_ffd_evaluation_diff;
    public $next_h2s_certification_diff;
    public $next_vio_review_diff;
    public $next_mvr_review_diff;
    public $next_dot_physical_diff;

    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'driver_id' => Yii::t('app', 'Driver ID'),
            'cdl_number' => Yii::t('app', 'CDL Number'),
            'cdl_state_id' => Yii::t('app', 'St'),
            'cdl_expires' => Yii::t('app', 'Expires'),
            'haz_mat' => Yii::t('app', 'HazMat'),
            'haz_mat_expires' => Yii::t('app', 'Expires'),
            'ace_id' => Yii::t('app', 'ACE ID'),
            'fast_id' => Yii::t('app', 'FAST ID'),
            'twic_exp' => Yii::t('app', 'TWIC Expires'),
            'last_drug_test' => Yii::t('app', 'Last Drug Test'),
            'last_alcohol_test' => Yii::t('app', 'Last Alcohol Test'),
            'work_auth_expires' => Yii::t('app', 'Work Auth Expires'),
            'next_ffd_evaluation' => Yii::t('app', 'Next FFD Evaluation'),
            'next_h2s_certification' => Yii::t('app', 'Next H2S Certification'),
            'next_vio_review' => Yii::t('app', 'Next Vio Review'),
            'next_mvr_review' => Yii::t('app', 'Next MVR Review'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'next_dot_physical' => Yii::t('app', 'Next DOT Physical'),
        ];
    }
}
