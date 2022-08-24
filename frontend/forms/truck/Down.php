<?php

namespace frontend\forms\truck;

use yii\base\Model;
use common\models\Location;

class Down extends Model
{
    private $truckModel;

    public $returnsToService;
    public $returnLocation;
    public $notifyAllDispatchPersonnel;

    /**
     * @param mixed $truckModel
     */
    public function setTruckModel($truckModel): void
    {
        $this->truckModel = $truckModel;
    }

    /**
     * @return mixed
     */
    public function getTruckModel()
    {
        return $this->truckModel;
    }

    public function rules()
    {
        return [
            [['returnsToService'], 'date', 'format' => 'yyyy-MM-dd'],
            [['returnLocation'], 'exist', 'targetClass' => Location::className(), 'targetAttribute' => ['returnLocation' => 'id']],
            [['notifyAllDispatchPersonnel'], 'boolean'],
        ];
    }
}