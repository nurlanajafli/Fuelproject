<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\LoadStopType;
use common\helpers\DateTime;
use common\models\Load;
use common\models\LoadStop;
use frontend\actions\loadStop\UpdateAction;
use Yii;
use yii\helpers\ArrayHelper;

class LoadStopController extends base\BaseController
{
    protected function allowedAttributes()
    {
        $loadStop = new LoadStop();
        return [
            'create' => $array = [
                $loadStop->formName() => [
                    'stop_type', 'available_from', 'available_thru', 'time_from', 'time_to', 'company_id', 'address', 'city', 'state_id', 'zip',
                    'zone', 'phone', 'contact', 'reference', 'appt_required', 'appt_reference', 'notes', 'company_name', 'lat','lon'
                ]
            ],
            'update' => $array
        ];
    }

    public function actions()
    {
        return [
            'create' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return Load::find()->alias('t')->joinWith('loadStops')->where(['t.id' => $actionParams['id']])->one();
                },
                'form' => function (Load $load) {
                    $loadStop = new LoadStop();
                    $loadStop->load_id = $load->id;
                    $loadStop->stop_number = empty($array = ArrayHelper::map($load->loadStops, 'id', 'stop_number')) ? 1 : max($array) + 1;
                    $loadStop->available_from = DateTime::nowDateYMD();
                    $loadStop->available_thru = DateTime::nowDateYMD();
                    if ($lastLoadStop = $load->getLastLoadStop()) {
                        $loadStop->available_from = $lastLoadStop->available_from;
                        $loadStop->available_thru = $lastLoadStop->available_thru;
                    }
                    $loadStop->stop_type = ($loadStop->stop_number == 1) ? LoadStopType::SHIPPER : LoadStopType::CONSIGNEE;
                    return $loadStop;
                },
                'save' => function (LoadStop $form, Load $load, string $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    return $action->saveResp(Yii::$app->transaction->exec(function () use ($form) {
                        return $this->saveModel($form);
                    }), ['load/edit', 'id' => $load->id]);
                }
            ],
            'update' => [
                'class' => UpdateAction::class,
                'before' => function ($actionParams) {
                    return LoadStop::find()->where(['id' => $actionParams['id'], 'load_id' => $actionParams['loadId']])->one();
                },
                'form' => function (LoadStop $loadStop) {
                    return $loadStop;
                },
                'save' => function (LoadStop $form, LoadStop $loadStop, string $button) {
                    /** @var UpdateAction $action */
                    $action = $this->action;

                    return $action->saveResp(Yii::$app->transaction->exec(function () use ($form) {
                        return $this->saveModel($form);
                    }), ['load/edit', 'id' => $loadStop->load_id]);
                }
            ]
        ];
    }

    public function actionDelete($loadId, $id)
    {
        /** @var LoadStop $loadStop */
        $loadStop = LoadStop::find()->where(['id' => $id, 'load_id' => $loadId])->one();
        if ($loadStop) {
            Yii::$app->transaction->exec(function () use ($loadStop) {
                return $loadStop->delete();
            });
            return $this->redirect(['load/edit', 'id' => $loadStop->load_id]);
        }
        throw new yii\web\NotFoundHttpException();
    }
}
