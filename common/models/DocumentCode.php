<?php

namespace common\models;

use Yii;
use \common\models\base\DocumentCode as BaseDocumentCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "document_code".
 */
class DocumentCode extends BaseDocumentCode
{
    use \common\models\traits\Template;

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
}
