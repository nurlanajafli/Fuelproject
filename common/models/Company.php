<?php

namespace common\models;

use common\enums\BusinessType;
use common\models\base\Company as BaseCompany;
use Yii;
use yii\helpers\ArrayHelper;
use common\helpers\DateTime;
use mohorev\file\UploadImageBehavior;
use common\helpers\Hosts;
use common\enums\CompanyThumb;
use yii\base\InvalidConfigException;
use common\helpers\Utils;

/**
 * This is the model class for table "company".
 */
class Company extends BaseCompany
{
    const SCENARIO_INSERT = 'insert';

    public function behaviors()
    {
        return ArrayHelper::merge(DateTime::setLocalTimestamp(parent::behaviors()), [
            'image' => [
                'class' => UploadImageBehavior::class,
                'attribute' => $this->getImageAttribute(),
                'scenarios' => [self::SCENARIO_INSERT],
                'path' => '@cdn-webroot',
                'url' => Hosts::getImageCdn(),
                'thumbs' => CompanyThumb::getSizeMap()
            ]
        ]);
    }

    public function rules()
    {
        $minHeight = ArrayHelper::getValue(CompanyThumb::getLargestSize(), 'height');
        $mimeTypes = ArrayHelper::getValue(Yii::$app->params, 'validImagesMimeTypes');

        $configErrorMessage = '';

        if (!$minHeight) {
            $configErrorMessage = 'Failed to get largest size with CompanyThumb::getLargestSize. ';
        }

        if (!$mimeTypes) {
            $configErrorMessage .= 'Parameter "validImagesMimeTypes" is not set. See params config file in common.';
        }

        if ($configErrorMessage) {
            throw new InvalidConfigException($configErrorMessage);
        }

        $imageAttribute = $this->getImageAttribute();
        return ArrayHelper::merge(Utils::removeAttributeRules(parent::rules(), $imageAttribute, ['string']), [
            [['business_type'], 'in', 'range' => array_values(BusinessType::getEnums())],
            [
                [$imageAttribute],
                'image',
                'minHeight' => $minHeight,
                'mimeTypes' => $mimeTypes,
                'on' => [self::SCENARIO_INSERT],
                'skipOnEmpty' => true
            ]
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'name' => Yii::t('app', 'Company Name'),
            'ar_contact' => Yii::t('app', 'A/R Contact'),
            'ap_contact' => Yii::t('app', 'A/P Contact'),
            'dot_id' => Yii::t('app', 'DOT ID'),
            'mc_id' => Yii::t('app', 'MC ID'),
            'scac' => Yii::t('app', 'SCAC'),
        ]);
    }

    public function getImageAttribute()
    {
        return 'logo';
    }
}
