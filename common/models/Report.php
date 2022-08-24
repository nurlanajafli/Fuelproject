<?php

namespace common\models;

use common\behaviors\UploadImageBehavior;
use common\enums\DefLevel;
use common\enums\FuelLevel;
use common\enums\ReportStatus;
use common\enums\ReportType;
use common\helpers\DateTime;
use common\helpers\Hosts;
use common\helpers\Utils;
use common\models\base\Report as BaseReport;
use common\models\traits\Template;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "report".
 */
class Report extends BaseReport
{
    const SCENARIO_SIGN = 'sign';

    use Template;

    public function behaviors()
    {
        return ArrayHelper::merge(DateTime::setLocalTimestamp(parent::behaviors(), [BaseActiveRecord::EVENT_BEFORE_INSERT => null]), [
            [
                'class' => UploadImageBehavior::class,
                'attribute' => 'signature_file',
                'scenarios' => [self::SCENARIO_SIGN],
                'path' => '@cdn-webroot',
                'url' => Hosts::getImageCdn(),
                'thumbs' => [],
                'fileInfoAttributes' => [
                    'mimeType' => 'signature_mime_type',
                ]
            ]
        ]);
    }

    public function rules()
    {
        $mimeTypes = ArrayHelper::getValue(Yii::$app->params, 'validImagesMimeTypes');
        if (!$mimeTypes) {
            throw new InvalidConfigException('Parameter "validImagesMimeTypes" is not set. See params config file in common');
        }

        $rules = parent::rules();
        if ($this->getScenario() == self::SCENARIO_SIGN) {
            $rules = Utils::removeAttributeRules($rules, 'signature_file', ['string']);
        }
        return ArrayHelper::merge($rules, [
            [
                ['signature_file'],
                'image',
                'minHeight' => 100,
                'mimeTypes' => $mimeTypes,
                'on' => [self::SCENARIO_SIGN],
                'skipOnEmpty' => false,
            ],
            [['type'], 'in', 'range' => ReportType::getEnums()],
            [['fuel_level'], 'in', 'range' => FuelLevel::getEnums()],
            [['def_level'], 'in', 'range' => DefLevel::getEnums()],
            [['status'], 'in', 'range' => ReportStatus::getEnums()],
        ]);
    }

    public function beforeValidate()
    {
        if ($this->getScenario() == self::SCENARIO_SIGN) {
            $this->signed_at = new Expression('LOCALTIMESTAMP');
        }
        return parent::beforeValidate();
    }
}
