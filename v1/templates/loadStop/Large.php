<?php

namespace v1\templates\loadStop;

use common\models\LoadMovement;
use common\models\LoadStop;
use common\models\State;
use TRS\RestResponse\templates\BaseTemplate;
use v1\templates\location\Small as LocationSmall;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @OA\Schema(
 *     schema="LoadStopLarge",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="appt_required",
 *         type="boolean"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         example="2021-03-23"
 *     ),
 *     @OA\Property(
 *         property="time_in",
 *         type="string",
 *         example="01:57 pm"
 *     ),
 *     @OA\Property(
 *         property="time_out",
 *         type="string",
 *         example="02:57 pm"
 *     ),
 *     @OA\Property(
 *         property="company",
 *         type="object",
 *         ref="#/components/schemas/LocationSmall"
 *     )
 * )
 */
class Large extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var LoadStop $model */
        $model = $this->model;
        $states = ArrayHelper::map(State::find()->all(), 'id', 'state_code');
        $companyArr = [];

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

        $formatter = Yii::$app->getFormatter();
        /** @var LoadStop|LoadMovement $m */
        $m = $model->loadMovement ?: $model;
        $this->result = [
            'id' => $model->id,
            'appt_required' => $model->appt_required,
            'date' => $m->arrived_date,
            'time_in' => $m->arrived_time_in ? strtolower($formatter->asTime($m->arrived_time_in, Yii::$app->params['formats']['12h'])) : null,
            'time_out' => $m->arrived_time_out ? strtolower($formatter->asTime($m->arrived_time_out, Yii::$app->params['formats']['12h'])) : null,
            'company' => $companyArr
        ];
    }
}
