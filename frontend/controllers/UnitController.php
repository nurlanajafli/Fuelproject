<?php
/**
 * /var/www/html/frontend/runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\components\PCMiler;
use common\enums\Activity;
use common\enums\LoadStatus;
use common\enums\Permission;
use common\enums\UnitItemStatus;
use common\helpers\Utils;
use common\models\DispatchAssignment;
use common\models\Load;
use common\models\LoadStop;
use common\models\Unit;
use frontend\forms\SetLocation;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the class for controller "UnitController".
 */
class UnitController extends base\UnitController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'set-location', 'create', 'find', 'update', 'view'],
                'permissions' => [Permission::ADD_EDIT_UNITS]
            ],
        ];
    }

    public function actions()
    {
        return [
            'set-location' => array_merge($this->setLocationActionConfig('unit'), ['viewParams' => ['cb' => 'unitsetlctn2']]),
            'create' => [
                'class' => FormProcessingAction::class,
                'formClass' => '\common\models\Unit',
                'init' => function () {
                    $this->action->viewParams = [
                        'allSelectedParts' => $this->getSelectedParts(0),
                    ];
                },
                'save' => function ($form, $model, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $request = Yii::$app->getRequest();
                    $setLocationForm = new SetLocation();
                    $setLocationForm->load($request->post());
                    if (!$setLocationForm->validate()) {
                        return $action->saveResp(true, ['show_modal' => true]);
                    }

                    return $action->saveResp(Yii::$app->transaction->exec(function () use ($form, $setLocationForm) {
                        return $this->saveModel($form) && $this->addTrackingLog($setLocationForm, $form);
                    }), ['index']);
                }
            ]
        ];
    }

    public function actionFind(int $loadId)
    {
        /** @var Load $load */
        $load = Load::find()->alias('t')->joinWith(['loadStops.state', 'commodityCommodity'])->andWhere(['t.id' => $loadId])->one();
        if (!$load) {
            throw new NotFoundHttpException();
        }

        /** @var LoadStop[] $loadStops */
        $loadStops = $load->getLoadStops()->joinWith('company')->orderBy(['stop_number' => SORT_ASC])->all();
        /** @var Unit[] $units */
        $units = $calculatedRows = [];
        if ($loadStops) {
            /** @var Unit[] $rawUnits */
            $rawUnits = Unit::find()->joinWith(['driver'])->all();
            $formatter = Yii::$app->getFormatter();
            $shortFormat = Utils::getParam('formatter.date.short');
            /** @var PCMiler $pcmiler */
            $pcmiler = Yii::$app->pcmiler;
            foreach ($rawUnits as $unit) {
                $date = $city = $stateCode = $zone = $nextDate = $nextCity = $nextStateCode = $dhd = $total = $pct = null;
                $act = Activity::OFF_DUTY;
                /** @var DispatchAssignment $dispatchAssignment */
                $avLctn = null;
                if ($unit->status == UnitItemStatus::AVAILABLE) {
                    $date = $formatter->asDate('now', $shortFormat);
                    if ($model = $unit->getTrackingLogs()->joinWith('location.state')->orderBy(['created_at' => SORT_DESC])->limit(1)->one()) {
                        $avLctn = $model->location;
                    }
                }
                if (
                    ($unit->status == UnitItemStatus::IN_USE) &&
                    ($dispatchAssignment = $unit->getDispatchAssignments()->joinWith(['load l', 'load.loadStops.company.state'])->andWhere(['<>', 'l.status', LoadStatus::RESERVED])->orderBy(['created_at' => SORT_DESC])->limit(1)->one()) &&
                    ($array = $dispatchAssignment->load->getLoadStopsOrdered())
                ) {
                    $lastLoadStop = end($array);
                    $date = $formatter->asDate($lastLoadStop->available_thru, $shortFormat);
                    $avLctn = $lastLoadStop->company;
                }
                if (!$avLctn) {
                    continue;
                }

                array_push($units, $unit);
                $dhp = $pcmiler->getDistance($avLctn->getPoint(), $loadStops[0]->company->getPoint());
                $city = $avLctn->city;
                $stateCode = $avLctn->state_id ? $avLctn->state->state_code : null;
                $zone = $avLctn->zone;
                if (
                    ($dispatchAssignment = $unit->getDispatchAssignments()->joinWith(['load l', 'load.loadStops.company.state'])->andWhere(['l.status' => LoadStatus::RESERVED])->orderBy(['created_at' => SORT_ASC])->limit(1)->one()) &&
                    ($act = Activity::DISPATCH) &&
                    ($array = $dispatchAssignment->load->getLoadStopsOrdered())
                ) {
                    $nextDate = $formatter->asDate($dispatchAssignment->dispatch_start_date, $shortFormat);
                    $nextCity = $array[0]->company->city;
                    $nextStateCode = $array[0]->company->state_id ? $array[0]->company->state->state_code : null;
                    $dhd = $pcmiler->getDistance(end($loadStops)->company->getPoint(), $array[0]->company->getPoint());
                }
                if (!is_null($dhp) || !is_null($dhd)) {
                    $total = $dhp + $dhd;
                    $pct = $total / $load->bill_miles;
                }
                $calculatedRows[$unit->id] = [
                    'team' => Utils::yn($unit->co_driver_id),
                    'date' => $date,
                    'city' => $city,
                    'stateCode' => $stateCode,
                    'zone' => $zone,
                    'nextDate' => $nextDate,
                    'nextCity' => $nextCity,
                    'nextStateCode' => $nextStateCode,
                    'act' => $act,
                    'dhp' => $dhp,
                    'dhd' => $dhd,
                    'total' => $total,
                    'pct' => $pct,
                ];
            }
        }
        return $this->render('find', ['load' => $load, 'units' => $units, 'calculatedRows' => $calculatedRows]);
    }
}
