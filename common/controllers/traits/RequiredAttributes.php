<?php

namespace common\controllers\traits;


use yii\base\Action;
use yii\web\BadRequestHttpException;

/**
 * Class RequiredAttributes
 * @package common\controllers\traits
 *
 * @property Action $action Property that all controllers extended from yii\base\Controller have
 *
 * WARNING
 *
 * Use ONLY for controllers. Not for regular classes
 */
trait RequiredAttributes
{
    /**
     * @param $attrs
     * @param $rawData
     * @return array
     * @desc This method filters parameters
     */
    private function checkRequiredParams($attrs, $rawData)
    {
        foreach ($attrs as $key => $attribute) {
            if (is_array($attribute)) {
                if (sizeof($attribute) == 1 && isset($attribute[0]) && is_array($attribute[0])) {
                    if (!(isset($rawData[$key]) && is_array($rawData[$key]))) return false;

                    foreach ($rawData[$key] as $child)
                        if (!$this->checkRequiredParams($attribute[0], $child)) return false;
                } else {
                    if (!isset($rawData[$key]) || !is_array($rawData[$key])) return false;

                    if (!$this->checkRequiredParams($attribute, $rawData[$key])) return false;
                }
            } else {
                if (!isset($rawData[$attribute])) return false;
            }
        }

        return true;
    }

    protected final function isHasAllRequiredAttributes()
    {
        $attrs = $this->requiredAttributes();
        $post = \Yii::$app->getRequest()->post();
        $result = true;

        if (isset($attrs[$this->action->id]) && !$this->checkRequiredParams($attrs[$this->action->id], $post))
            $result = false;

        return $result;
    }

    protected function requiredAttributes()
    {
        return [];
    }
}
