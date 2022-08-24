<?php

namespace common\widgets;

use yii\base\Widget;
use Yii;
use yii\helpers\ArrayHelper;

class Button extends Widget
{
    /**
     * @var array
     */
    public $options = [];

    public function run()
    {
        return $this->render('button', [
            'options' => ArrayHelper::merge([
                'a' => [
                    'url' => '#',
                    'text' => '',
                    'options' => [],
                ],
                'icon' => [
                    'before' => '',
                    'after' => '',
                    'name' => '',
                ],
            ], Yii::$app->params['buttonWidget'], $this->options),
        ]);
    }
}