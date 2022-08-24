<?php

namespace v1\templates\loadStop;

use common\enums\LoadStopType;
use common\models\LoadStop;
use TRS\RestResponse\templates\BaseTemplate;
use v1\templates\location\Small as LocationSmall;
use Yii;
use yii\helpers\ArrayHelper;

/**
 *
 * @OA\Schema(
 *     schema="LoadStopSmall",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="number",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="company",
 *         type="object",
 *         ref="#/components/schemas/LocationSmall"
 *     )
 * )
 *
 */
class Small extends BaseTemplate
{
    public $config;

    protected function prepareResult()
    {
        /** @var LoadStop $model */
        $model = $this->model;
        $companyArr = [];

        $states = ArrayHelper::getValue($this->config, 'states', []);

        if($model->company && $model->company->company_name && $model->company_id !='') {
            $company = $model->company;
            $companyArr = $company->getAsArray(
                LocationSmall::class,
                ($company->state_id && isset($states[$company->state_id])) ? ['state_abbreviation' => $states[$company->state_id]] : []
            );
        } else {
            $companyArr['name'] = $model->getCompanyName();
            $companyArr['address'] = $model->getAddress();
            $companyArr['city'] = $model->getCity();
            $companyArr['state_abbreviation'] = $model->getStateCode();
            $companyArr['zip'] = $model->getZip();
            $companyArr['phone'] = '';
        }

        $this->result = [
            'id' => $model->id,
            'number' => $model->stop_number,
            'type' => Yii::t('app', ($model->stop_type == LoadStopType::SHIPPER) ? 'Pickup' : 'Delivery'),
            'company' => $companyArr
        ];
    }
}
