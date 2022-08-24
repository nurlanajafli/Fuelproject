<?php

namespace common\models;

use Yii;
use \common\models\base\AccessorialMatrix as BaseAccessorialMatrix;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "accessorial_matrix".
 */
class AccessorialMatrix extends BaseAccessorialMatrix
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }

    public function get_label()
    {
        return $this->matrix_no . " - " . $this->matrix_name;
    }
}
