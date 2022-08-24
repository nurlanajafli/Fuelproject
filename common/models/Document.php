<?php

namespace common\models;

use common\behaviors\UploadImageBehavior;
use common\helpers\DateTime;
use common\helpers\Hosts;
use common\helpers\Utils;
use common\models\base\Document as BaseDocument;
use common\models\traits\Template;
use mohorev\file\UploadBehavior;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class Document extends BaseDocument
{
    use Template;

    const SCENARIO_INSERT_1 = 'insert_1';
    const SCENARIO_INSERT_2 = 'insert_2';

    public function behaviors()
    {
        return ArrayHelper::merge(
            DateTime::setLocalTimestamp(parent::behaviors()),
            [
                [
                    'class' => UploadBehavior::class,
                    'attribute' => $this->getImageAttribute(),
                    'scenarios' => [self::SCENARIO_INSERT_1],
                    'path' => '@cdn-webroot',
                    'url' => Hosts::getImageCdn(),
                    'generateNewName' => function ($file) {
                        return Yii::$app->security->generateRandomString(50) . '.' . $file->extension;
                    }
                ],
                'image' => [
                    'class' => UploadImageBehavior::class,
                    'attribute' => $this->getImageAttribute(),
                    'scenarios' => [self::SCENARIO_INSERT_2],
                    'path' => '@cdn-webroot',
                    'url' => Hosts::getImageCdn(),
                    'thumbs' => function () {
                        return call_user_func($this->getThumbClass() . '::getSizeMap');
                    },
                    'fileInfoAttributes' => [
                        'mimeType' => 'mime_type',
                        'width' => 'width',
                        'height' => 'height',
                        'size' => 'size',
                    ]
                ]
            ],
        );
    }

    public function rules()
    {
        $class = $this->getThumbClass();
        $minHeight = ArrayHelper::getValue(call_user_func($class . '::getLargestSize'), 'height');
        $mimeTypes = ArrayHelper::getValue(Yii::$app->params, 'validImagesMimeTypes');

        $configErrorMessage = '';

        if (!$minHeight) {
            $configErrorMessage = 'Failed to get largest size with ' . $class . '::getLargestSize. ';
        }

        if (!$mimeTypes) {
            $configErrorMessage .= 'Parameter "validImagesMimeTypes" is not set. See params config file in common.';
        }

        if ($configErrorMessage) {
            throw new InvalidConfigException($configErrorMessage);
        }

        $imageAttribute = $this->getImageAttribute();
        $rules = parent::rules();
        $rules = Utils::removeAttributeRules($rules, $imageAttribute, ['string']);
        $rules = Utils::removeAttributeRules($rules, 'mime_type', ['required']);
        return ArrayHelper::merge($rules, [
            [[$imageAttribute], 'file', 'mimeTypes' => Yii::$app->params['validDocMimeTypes'], 'on' => [self::SCENARIO_INSERT_1], 'skipOnEmpty' => false],
            [
                [$imageAttribute], 'image',
                'minHeight' => $minHeight,
                'mimeTypes' => $mimeTypes,
                'on' => [self::SCENARIO_INSERT_2],
                'skipOnEmpty' => false
            ],
            [['code'], 'default', 'value' => null],
            [['code'], 'required', 'when' => function ($model) {
                return $model->load_id;
            }]
        ]);
    }

    public function getImageAttribute()
    {
        return 'file';
    }

    protected function getThumbClass()
    {
        return $this->load_id ? \common\enums\LoadThumb::class : \common\enums\DocumentThumb::class;
    }

    public function getThumbUrl(string $profile = 'thumb')
    {
        return $this->getThumbUploadUrl($this->getImageAttribute(), $profile);
    }

    public function getUrl()
    {
        return $this->getUploadUrl($this->getImageAttribute());
    }

    public function beforeValidate()
    {
        if ($this->getScenario() == self::SCENARIO_INSERT_1) {
            if ($instance = UploadedFile::getInstance($this, $this->getImageAttribute())) {
                $this->mime_type = $instance->type;
                $this->size = $instance->size;
            }
        }
        return parent::beforeValidate();
    }
}
