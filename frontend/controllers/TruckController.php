<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\Permission;
use common\helpers\DateTime;
use common\models\Truck;
use common\models\TruckOdometer;
use Exception;
use frontend\forms\truck\Down;
use frontend\forms\truck\EditOutOfServiceStatus;
use frontend\forms\truck\OutOfService;
use Yii;
use yii\db\Expression;


/**
 * This is the class for controller "TruckController".
 */
class TruckController extends base\TruckController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view'],
                'permissions' => [Permission::TRUCK_LISTING]
            ],
            [
                'allow' => true,
                'actions' => ['index', 'view', 'create', 'update', 'delete', 'list-odom', 'add-odom', 'out-of-service', 'down', 'set-location'],
                'permissions' => [Permission::ADD_EDIT_TRUCKS]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $fpaClass = FormProcessingAction::class;
        return [
            'out-of-service' => [
                'class' => $fpaClass,
                'formClass' => function ($model) {
                    /** @var Truck $model */
                    return $model->out_of_service ? EditOutOfServiceStatus::class : OutOfService::class;
                },
                'before' => function ($actionParams) {
                    return $this->findModel($actionParams['id']);
                },
                'init' => function ($form, $model) {
                    /**
                     * @var Truck $model
                     */
                    if ($model->out_of_service) {
                        /** @var EditOutOfServiceStatus $form */
                        $form->editOrRemoveDate = EditOutOfServiceStatus::EDIT;
                        $form->date = $model->out_of_service;
                    }
                },
                'save' => function ($form, Truck $model, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $model->out_of_service = $model->out_of_service
                        ? ($form->editOrRemoveDate == EditOutOfServiceStatus::EDIT) ? $form->date : null
                        : new Expression('current_date');

                    return $action->saveResp($this->saveModel($model));
                }
            ],
            'down' => [
                'class' => $fpaClass,
                'formClass' => Down::class,
                'before' => function ($actionParams) {
                    return $this->findModel($actionParams['id']);
                },
                'init' => function ($form, $model) {
                    /**
                     * @var Down $form
                     * @var Truck $model
                     */
                    $form->setTruckModel($model);
                    if ($model->is_down) {
                        $form->returnsToService = $model->returns_to_service;
                        $form->returnLocation = $model->return_location_id;
                        $form->notifyAllDispatchPersonnel = $model->notify_all_dispatch_personnel;
                    }
                },
                'save' => function (Down $form, Truck $model, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    if ($model->is_down) {
                        if ($button == 'post_updates') {
                            $model->returns_to_service = $form->returnsToService;
                            $model->return_location_id = $form->returnLocation;
                            $model->notify_all_dispatch_personnel = $form->notifyAllDispatchPersonnel;
                        } else {
                            $model->is_down = false;
                        }
                    } else {
                        $model->is_down = true;
                        $model->returns_to_service = $form->returnsToService;
                        $model->return_location_id = $form->returnLocation;
                        $model->notify_all_dispatch_personnel = $form->notifyAllDispatchPersonnel;
                        $model->downed_by = Yii::$app->user->id;
                        $model->downed_at = new Expression('localtimestamp');
                    }

                    return $action->saveResp($this->saveModel($model));
                }
            ],
            'set-location' => $this->setLocationActionConfig('truck')
        ];
    }

    public function actionListOdom($id)
    {
        $data = TruckOdometer::find()->where(['truck_id' => $id])->orderBy(['id' => SORT_DESC])->all();
        return $this->renderPartial('listOdom', [
            'id' => $id,
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Truck;
        $model->in_svc = DateTime::nowDateYMD();

        try {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['index']);
            } elseif (!Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionAddOdom()
    {
        $model = new TruckOdometer();

        try {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['update', 'id' => $model->truck_id]);
            }
        } catch (Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
    }
}
