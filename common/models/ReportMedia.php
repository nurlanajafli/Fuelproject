<?php

namespace common\models;

use common\behaviors\UploadImageBehavior;
use common\enums\DamageType;
use common\enums\ReportMediaThumb;
use common\enums\Side;
use common\helpers\DateTime;
use common\helpers\Hosts;
use common\helpers\Utils;
use common\models\base\ReportMedia as BaseReportMedia;
use common\models\traits\Template;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "report_media".
 */
class ReportMedia extends BaseReportMedia
{
    use Template;

    const SCENARIO_INSERT = 'insert';

    public function behaviors()
    {
        return ArrayHelper::merge(
            DateTime::setLocalTimestamp(parent::behaviors()),
            [
                [
                    'class' => UploadImageBehavior::class,
                    'attribute' => $this->getImageAttribute(),
                    'scenarios' => [self::SCENARIO_INSERT],
                    'path' => '@cdn-webroot',
                    'url' => Hosts::getImageCdn(),
                    'thumbs' => ReportMediaThumb::getSizeMap(),
                    'fileInfoAttributes' => [
                        'mimeType' => 'mime_type',
                        'width' => 'width',
                        'height' => 'height',
                        'size' => 'size',
                    ]
                ]
            ]
        );
    }

    public function transactions()
    {
        return ArrayHelper::merge(parent::transactions(), [
            static::SCENARIO_INSERT => static::OP_INSERT
        ]);
    }

    public function rules()
    {
        $imageAttribute = $this->getImageAttribute();
        $minHeight = ArrayHelper::getValue(ReportMediaThumb::getLargestSize(), 'height');
        if (!$minHeight) {
            throw new InvalidConfigException('Failed to get the largest size');
        }

        $mimeTypes = ArrayHelper::getValue(Yii::$app->params, 'validImagesMimeTypes');
        if (!$mimeTypes) {
            throw new InvalidConfigException('Parameter "validImagesMimeTypes" is not set. See params config file in common');
        }

        $rules = parent::rules();
        if ($this->getScenario() == self::SCENARIO_INSERT) {
            $rules = Utils::removeAttributeRules($rules, $imageAttribute, ['string']);
            $rules = Utils::removeAttributeRules($rules, 'mime_type', ['required']);
        }
        return ArrayHelper::merge($rules, [
            [
                [$imageAttribute],
                'image',
                'minHeight' => $minHeight,
                'mimeTypes' => $mimeTypes,
                'on' => [self::SCENARIO_INSERT],
                'skipOnEmpty' => false,
            ],
            [['damage_type'], 'in', 'range' => DamageType::getEnums()],
            [['side'], 'in', 'range' => Side::getEnums()],
        ]);
    }

    public function getImageAttribute()
    {
        return 'file';
    }
}
