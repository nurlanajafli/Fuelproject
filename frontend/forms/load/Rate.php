<?php

namespace frontend\forms\load;

use common\enums\LoadRateMethod;
use common\enums\LoadRateSource;
use common\enums\LoadRateType;
use common\enums\RateBy;
use common\models\LoadRatingMatrix;
use Yii;
use yii\base\Model;

class Rate extends Model
{
    protected $load;

    public $source;
    public $matrixNumber;
    public $rateBy;
    public $rate;
    public $units;
    public $discountPct;

    public function rules()
    {
        return [
            [['source'], 'required'],
            [['source'], 'in', 'range' => LoadRateSource::getEnums()],
            [['matrixNumber'], 'exist', 'targetClass' => LoadRatingMatrix::class, 'targetAttribute' => ['matrixNumber' => 'number'], 'filter' => function ($query) {
                $query->andWhere(['inactive' => false]);
            }],
            [['matrixNumber'], 'validateMatrixNumber'],
            [['rateBy'], 'string'],
            [['rateBy'], 'validateRateBy'],
            [['rate', 'discountPct'], 'default', 'value' => 0],
            [['rate', 'discountPct'], 'double'],
        ];
    }

    public function validateMatrixNumber($attribute, $params, $validator)
    {
    }

    public function validateRateBy($attribute, $params, $validator)
    {
        if ($this->$attribute && ($this->source == LoadRateSource::LOAD_MATRIX) && $this->matrixNumber) {
            $matrix = LoadRatingMatrix::findOne($this->matrixNumber);
            if ($matrix) {
                $values = $this->getRateByValues();
                if (!isset($values[$this->$attribute]) || !in_array($matrix->rate_method, $values[$this->$attribute]['methods'])) {
                    $this->addError($attribute, Yii::t('app', 'Value is not supported by matrix'));
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getLoad()
    {
        return $this->load;
    }

    /**
     * @param mixed $load
     */
    public function setLoad($load): void
    {
        $this->load = $load;
    }

    public function attributeLabels()
    {
        return [
            'matrixNumber' => Yii::t('app', 'Matrix No'),
        ];
    }

    public function getRateByValues()
    {
        $result = [];
        foreach (RateBy::getUiEnums() as $key => $value) {
            $result[$key] = ['methods' => [null], 'value' => $value, 'source' => [LoadRateSource::MANUAL]];
        }
        foreach (LoadRateType::getUiEnums() as $key => $value) {
            $methods = [];
            switch ($key) {
                case LoadRateType::FLAT:
                case LoadRateType::MILES:
                case LoadRateType::TON:
                    $methods = [LoadRateMethod::ZIP_ZIP, LoadRateMethod::GEOGRAPH, LoadRateMethod::ZONE_ZONE, LoadRateMethod::DISTANCE];
                    break;
                case LoadRateType::PIECE:
                case LoadRateType::SPACE:
                case LoadRateType::POUND:
                case LoadRateType::CWT:
                case LoadRateType::LOT:
                    $methods = [LoadRateMethod::ZIP_ZIP, LoadRateMethod::GEOGRAPH, LoadRateMethod::ZONE_ZONE];
                    break;
                case LoadRateType::MULTI:
                    $methods = [LoadRateMethod::ZIP_ZIP, LoadRateMethod::GEOGRAPH];
                    break;
                case LoadRateType::STEP:
                    $methods = [LoadRateMethod::ZONE_ZONE];
                    break;
            }
            $result[$key] = ['methods' => array_merge(isset($result[$key]) ? $result[$key]['methods'] : [], $methods), 'value' => $value, 'source' => array_merge(isset($result[$key]) ? $result[$key]['source'] : [], [LoadRateSource::LOAD_MATRIX])];
        }
        return $result;
    }
}
