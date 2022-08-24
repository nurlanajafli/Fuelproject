<?php

namespace common\controllers\traits;

use yii\base\Action;

/**
 * Class AllowedAttributes
 * @package common\controllers\traits
 *
 * @property Action $action Property that all controllers extended from yii\base\Controller have
 *
 * WARNING
 *
 * Use ONLY for controllers. Not for regular classes
 */
trait AllowedAttributes
{
    /**
     * @return array
     */
    protected final function getAllowedPost()
    {
        $attrs = $this->allowedAttributes();
        $post = \Yii::$app->getRequest()->post();

        if (!isset($attrs[$this->action->id]))
            return $post;

        return $this->readParams($attrs[$this->action->id], $post);
    }

    /**
     * @return array
     */
    protected final function getAllowedGet()
    {
        $attrs = $this->allowedAttributes();
        $get = \Yii::$app->getRequest()->get();

        if (!isset($attrs[$this->action->id]))
            return $get;

        return $this->readParams($attrs[$this->action->id], $get);
    }


    /**
     * @param $attrs
     * @param $rawData
     * @return array
     * @desc This method filters parameters
     */
    private function readParams($attrs, $rawData)
    {
        $data = [];

        foreach ($attrs as $key => $attribute) {
            if (is_array($attribute)) {
                if (!isset($rawData[$key])) continue;

                if (sizeof($attribute) == 1 && isset($attribute[0]) && is_array($attribute[0])) {
                    if (!is_array($rawData[$key])) continue;

                    foreach ($rawData[$key] as $child) {
                        $_data = $this->readParams($attribute[0], $child);
                        if (!$_data) continue;

                        $data[$key][] = $_data;
                    }
                } else {
                    if (!isset($rawData[$key]) || !is_array($rawData[$key])) continue;

                    $_data = $this->readParams($attribute, $rawData[$key]);
                    if (!$_data) continue;

                    $data[$key] = $_data;
                }
            } else {
                if (!isset($rawData[$attribute])) continue;

                $data[$attribute] = $rawData[$attribute];
            }
        }

        return $data;
    }

    protected function allowedAttributes()
    {
        return [];
    }
}
