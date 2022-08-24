<?php

namespace common\controllers\traits;

use Yii;

trait SaveModel
{
    /**
     * @param \yii\db\ActiveRecord $model
     * @param bool $runValidation
     * @param array $attributeNames
     * @return bool
     */
    protected function saveModel($model, $runValidation = true, $attributeNames = null)
    {
        if ($model->save($runValidation, $attributeNames)) {
            return true;
        }

        Yii::error([$model->formName() => $model->getErrors()]);
        return false;
    }

    protected function batchSaveModel($models)
    {
        foreach ($models as $model) {
            if (!$this->saveModel($model)) {
                return false;
            }
        }
        return true;
    }
}