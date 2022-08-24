<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\components\Firebase;
use common\components\PCMiler;
use common\enums\BillOfLadingType;
use common\enums\CronTask;
use common\enums\DeliveryReceiptTypes;
use common\enums\LoadMovementAction;
use common\enums\LoadsheetType;
use common\enums\LoadStatus;
use common\enums\PayCode;
use common\enums\PaySource;
use common\enums\Permission;
use common\enums\TrailerDisposition;
use common\enums\TrailerStatus;
use common\enums\TruckStatus;
use common\enums\UnitItemStatus;
use common\helpers\DateTime;
use common\helpers\Utils;
use common\models\AccessorialMatrix;
use common\models\AccessorialPay;
use common\models\AccountingDefault;
use common\models\Company;
use common\models\Cron;
use common\models\DispatchAssignment;
use common\models\Load;
use common\models\LoadAccessory;
use common\models\LoadDrop;
use common\models\LoadMovement;
use common\models\LoadNote;
use common\models\LoadStop;
use common\models\Location;
use common\models\Office;
use common\models\User;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use dmstr\bootstrap\Tabs;
use Exception;
use frontend\forms\load\AddNote;
use frontend\forms\load\Arrival;
use frontend\forms\load\BillOfLadingPp;
use frontend\forms\load\DeliveryReceipt;
use frontend\forms\load\DuplicateLoad;
use frontend\forms\load\Loadsheet;
use frontend\forms\load\Rate;
use kartik\mpdf\Pdf;
use Throwable;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class LoadController extends base\BaseController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'edit', 'notes', 'delete', 'cancel',
                    'route-map', 'edit-accessory', 'delete-accessory', 'ajax-list-accessories', 'ajax-accessory-rate',
                    'accessorial-notification', 'reserve-load', 'drop', 'add-note', 'arrived', 'arrive', 'change-status', 'reset'],
                'permissions' => [Permission::ADD_EDIT_LOADS]
            ],
            [
                'allow' => true,
                'actions' => ['index', 'view', 'edit', 'notes', 'dispatch-load', 'dispatch-summary'],
                'permissions' => [Permission::EDIT_DISPATCHED_LOADS]
            ],
            [
                'allow' => true,
                'actions' => ['index', 'view', 'edit', 'notes', 'invoice', 'bill-of-lading-pp', 'loadsheet', 'delivery-receipt', 'transport-agreement', 'dispatch-confirmation'],
                'permissions' => [Permission::EDIT_INVOICED_LOADS]
            ],
            [
                'allow' => true,
                'actions' => ['index', 'view', 'edit', 'notes', 'duplicate-load'],
                'permissions' => [Permission::DUPLICATE_LOADS]
            ],
            [
                'allow' => true,
                'actions' => ['index', 'view', 'edit', 'notes', 'rate', 'ajax-preview-rates'],
                'permissions' => [Permission::VIEW_LOAD_RATING_INFORMATION]
            ],
            [
                'allow' => true,
                'actions' => ['index', 'view', 'edit', 'notes', 'dispatch-load', 'dispatch-summary'],
                'permissions' => [Permission::ADD_EDIT_DISPATCH_FILTERS]
            ],
            // N
            [
                'allow' => true,
                'actions' => ['board'],
                'permissions' => [Permission::LOAD_BOARD],
            ],
        ];
    }

    protected function allowedAttributes()
    {
        $result = [
            'dispatch-load' => [
                (new DispatchAssignment())->formName() => [
                    'pay_code', 'date', 'unit_id', 'driver_id', 'codriver_id', 'truck_id', 'trailer_id', 'trailer2_id',
                    'notes', 'driver_pay_source', 'driver_pay_matrix', 'driver_pay_type', 'driver_loaded_miles', 'driver_empty_miles', 'driver_loaded_rate',
                    'driver_empty_rate', 'codriver_pay_source', 'codriver_pay_type', 'codriver_loaded_miles', 'codriver_empty_miles',
                    'codriver_loaded_rate', 'codriver_empty_rate', 'dispatch_start_date', 'dispatch_start_time_in',
                    'dispatch_start_time_out', 'dispatch_deliver_date', 'dispatch_deliver_time_in', 'dispatch_deliver_time_out'
                ]
            ],
            'clear' => [(new Load())->formName() => ['scans']]
        ];

        $result['reserve-load'] = $result['dispatch-load'];

        if (Yii::$app->request->isPost) {
            $result['drop'] = [
                (new LoadDrop())->formName() => [
                    'drop_date', 'drop_time_from', 'drop_time_to', 'location_id', 'retrieve_date', 'retrieve_time', 'trailer_disposition'
                ]
            ];
        }

        return $result;
    }

    protected function requiredAttributes()
    {
        $result = [];

        if (Yii::$app->request->isPost) {
            $result['drop'] = [
                (new LoadDrop())->formName() => [
                    'drop_date', 'drop_time_from', 'drop_time_to', 'location_id', 'retrieve_date', 'retrieve_time', 'trailer_disposition'
                ]
            ];
        }

        return $result;
    }

    public function actions()
    {
        $fpa = FormProcessingAction::class;
        $func = function ($reserve) use ($fpa) {
            return [
                'class' => $fpa,
                'view' => 'dispatchLoad',
                'before' => function ($actionParams) {
                    return Load::find()->alias('t0')->joinWith(['dispatchAssignment', 'loadStops'])->andWhere(['t0.id' => $actionParams['id']])->one();
                },
                'form' => function (Load $model) use ($reserve) {
                    if (!$model->dispatchAssignment) {
                        $da = new DispatchAssignment();
                        $da->load_id = $model->id;
                        $da->date = Yii::$app->formatter->asDate('now', Yii::$app->params['formats']['db']);
                        $da->driver_pay_source = PaySource::STANDARD;
                        $da->driver_loaded_miles = $model->bill_miles;
                        $da->codriver_pay_source = PaySource::STANDARD;
                        $da->codriver_loaded_miles = $model->bill_miles;
                        $da->pay_code = PayCode::LOAD;
                        if ($model->loadStops) {
                            $i = count($model->loadStops) - 1;
                            $da->dispatch_start_date = $model->loadStops[0]->available_from;
                            $da->dispatch_start_time_in = $model->loadStops[0]->time_from;
                            $da->dispatch_start_time_out = $model->loadStops[0]->time_to;
                            $da->dispatch_deliver_date = $model->loadStops[$i]->available_from;
                            $da->dispatch_deliver_time_in = $model->loadStops[$i]->time_from;
                            $da->dispatch_deliver_time_out = $model->loadStops[$i]->time_to;
                        }
                        $da->setScenario(DispatchAssignment::SCENARIO_CREATE);
                    } else {
                        $da = $model->dispatchAssignment;
                        $da->setScenario(DispatchAssignment::SCENARIO_EDIT);
                    }
                    if ($reserve) {
                        $da->setScenario(DispatchAssignment::SCENARIO_EDIT);
                    } elseif ($model->status == LoadStatus::RESERVED) {
                        $da->setScenario(DispatchAssignment::SCENARIO_DISPATCH_RESERVED);
                    }
                    return $da;
                },
                'init' => function ($da, $load) use ($reserve) {
                    $this->action->viewParams = [
                        'load' => $load,
                        'title' => Yii::t('app', ($reserve ? 'Reserve' : 'Dispatch') . ' Load {load_number} TL', ['load_number' => $load->id]),
                        'errors' => $load->getAssignmentErrors($reserve)
                    ];
                },
                'save' => function ($da, $load, $buttonCode) use ($reserve) {
                    /**
                     * @var DispatchAssignment $da
                     * @var Load $load
                     */
                    $f = !empty($da->load->getAssignmentErrors($reserve)) || Yii::$app->transaction->exec(function () use ($da, $reserve, $load) {

                            if (!$this->saveModel($da)) {
                                return false;
                            }

                            if ($reserve) {
                                $load->addNote(sprintf(
                                    'Load reserved to unit %d. Driver: %s. Truck: (%d) %s',
                                    $da->unit_id, $da->driver->_label, $da->truck_id, $da->truck->get_label()
                                ));
                                $load->changeStatus(LoadStatus::RESERVED);
                                $load->addNote(sprintf('Load status changed to %s', LoadStatus::RESERVED));
                                Yii::$app->session->addFlash('success', Yii::t('app', 'Load has been reserved successfully'));
                                return true;
                            }

                            if (ArrayHelper::isIn($da->scenario, [DispatchAssignment::SCENARIO_CREATE, DispatchAssignment::SCENARIO_DISPATCH_RESERVED]) || ($load->status == LoadStatus::DROPPED)) {
                                if ($load->status == LoadStatus::DROPPED) {
                                    /** @var LoadDrop $loadDrop */
                                    $loadDrop = LoadDrop::find()->andWhere(['load_id' => $load->id])->orderBy(['created_at' => SORT_DESC])->one();
                                    if (!$loadDrop || $loadDrop->retrieve_unit_id) {
                                        return false;
                                    }

                                    $loadDrop->retrieve_driver_id = $da->driver_id;
                                    $loadDrop->retrieve_codriver_id = $da->codriver_id;
                                    $loadDrop->retrieve_truck_id = $da->truck_id;
                                    $loadDrop->retrieve_trailer_id = $da->trailer_id;
                                    $loadDrop->retrieve_trailer_2_id = $da->trailer2_id;
                                    $loadDrop->retrieve_unit_id = $da->unit_id;

                                    if (!$this->saveModel($loadDrop)) {
                                        return false;
                                    }
                                }
                                $load->addNote(sprintf(
                                    'Load assigned to unit %d. Driver: %s. Truck: (%d) %s',
                                    $da->unit_id, $da->driver->_label, $da->truck_id, $da->truck->get_label()
                                ));
                                $load->changeStatus(LoadStatus::ENROUTED);
                                $load->addNote(sprintf('Load status changed to %s', LoadStatus::ENROUTED));

                                if( ! $da->driver->validate('email_address') ){
                                    Yii::$app->getSession()->addFlash("error", "Error. Incorrect email address for driver!" );
                                    return $this->redirect(['index']);
                                }

                                $tmpDir = sys_get_temp_dir();
                                $fileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('r') . '.pdf';
                                $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                                    'destination' => Pdf::DEST_FILE,
                                    'filename' => $fileName,
                                    'content' => $this->renderPartial('_dispatch-confirmation', [
                                        'company' => Company::find()->alias('t')->andWhere(['t.id' => Utils::getParam('companyId')])->joinWith('state')->one(),
                                        'load' => $load,
                                    ])
                                ]));
                                $pdf->render();
                                Cron::create(CronTask::DELETE_FILE, ['filename' => $fileName], 3600, 2, 3600);

                                $sent = Yii::$app->getMailer()
                                    ->compose(['html' => 'driverDispatchConfirmation-html', 'text' => 'driverDispatchConfirmation-text'], ['model' => $da->driver])
                                    ->setTo( $da->driver->email_address )
                                    ->setFrom(Yii::$app->params['senderEmail'])
                                    ->setSubject('Load assign')
                                    ->attach($fileName)
                                    ->send();
                                if (!$sent) {
                                    Yii::$app->getSession()->addFlash("error", "Error to send dispatch confirmation to email!" );
                                    return $this->redirect(['index']);
                                }

                                if( $this->changeUnitStatus($da, UnitItemStatus::IN_USE)) {
                                    Yii::$app->session->addFlash('success', Yii::t('app', 'Load has dispatched successfully'));
                                }
                            } else {
                                $load->addNote('Load dispatch has been updated');
                                Yii::$app->session->addFlash('success', Yii::t('app', 'Load has updated successfully'));
                            }

                            if ($da->driver->user && $da->driver->user->status == User::STATUS_ACTIVE) {
                                /** @var Firebase $fb */
                                $fb = Yii::$app->firebase;
                                foreach ($da->driver->user->devices as $device) {
                                    $fb->sendNotification(
                                        $device->id,
                                        sprintf('Load %d', $load->id),
                                        'Load has assigned',
                                        null,
                                        ['load_id' => $load->id]
                                    );
                                }
                            }
                            return true;
                        });

                    return $this->action->saveResp($f, ['index']);
                }
            ];
        };
        return [
            'rate' => [
                'class' => $fpa,
                'before' => function ($actionParams) {
                    return Load::findOne($actionParams['id']);
                },
                'form' => function ($load) {
                    $result = new Rate();
                    $result->setLoad($load);
                    $result->source = $load->rate_source;
                    $result->matrixNumber = $load->rating_matrix_id;
                    $result->rateBy = $load->rate_by;
                    $result->rate = $load->rate;
                    $result->discountPct = $load->discount_percent;
                    return $result;
                },
                'save' => function (Rate $form, Load $model, $button) {
                    $model->rate_source = $form->source;
                    if ($model->rating_matrix_id)
                        $model->rating_matrix_id = $form->matrixNumber;
                    $model->rate_by = $form->rateBy;
                    $model->rate = $form->rate;
                    $model->discount_percent = $form->discountPct;
                    $model->updateRate();
                    $cond = Yii::$app->transaction->exec(function () use ($model) {
                        if ($model->ratingMatrix) {
                            $a = $model->ratingMatrix->calculate($model);
                            foreach ($a['bill_miles'] as $id => $value) {
                                $loadStop = LoadStop::findOne(['id' => $id, 'load_id' => $model->id]);
                                if (!$loadStop) {
                                    return false;
                                }
                                $loadStop->miles_to_next = $value;
                                if (!$this->saveModel($loadStop)) {
                                    return false;
                                }
                            }
                        }
                        if ($this->saveModel($model)) {
                            $model->addNote(
                                sprintf(
                                    'Load Revenue updated from $ %s to $ %s',
                                    Yii::$app->formatter->asDecimal($model->_old_total),
                                    Yii::$app->formatter->asDecimal($model->total)
                                )
                            );
                            return true;
                        }
                        return false;
                    });
                    if ($cond) {
                        Yii::$app->session->addFlash('success', 'Load have been updated successfully');
                    }
                    return $this->action->saveResp($cond, ['edit', 'id' => $model->id]);
                },
            ],
            'dispatch-load' => $func(false),
            'reserve-load' => $func(true),
            'drop' => [
                'class' => $fpa,
                'before' => function ($actionParams) {
                    return Load::find()->
                    alias('t0')->
                    joinWith(['dispatchAssignment.driver', 'dispatchAssignment.codriver', 'dispatchAssignment.truck', 'dispatchAssignment.trailer', 'dispatchAssignment.trailer2'])->
                    andWhere(['t0.id' => $actionParams['id'], 't0.status' => LoadStatus::ENROUTED])->
                    one();
                },
                'form' => function (Load $model) {
                    $loadDrop = new LoadDrop();
                    $loadDrop->load_id = $model->id;
                    $loadDrop->drop_driver_id = $model->dispatchAssignment->driver_id;
                    $loadDrop->drop_codriver_id = $model->dispatchAssignment->codriver_id;
                    $loadDrop->drop_truck_id = $model->dispatchAssignment->truck_id;
                    $loadDrop->drop_trailer_id = $model->dispatchAssignment->trailer_id;
                    $loadDrop->drop_trailer_2_id = $model->dispatchAssignment->trailer2_id;
                    $loadDrop->drop_unit_id = $model->dispatchAssignment->unit_id;
                    $loadDrop->trailer_disposition = TrailerDisposition::KEEP_WITH_UNIT;
                    return $loadDrop;
                },
                'save' => function (LoadDrop $form, Load $model, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    return $action->saveResp(Yii::$app->transaction->exec(
                        function () use ($form, $model) {
                            if ($model->dispatchAssignment->driver) {
                                $model->dispatchAssignment->driver->status = UnitItemStatus::AVAILABLE;
                                if (!$this->saveModel($model->dispatchAssignment->driver)) {
                                    return false;
                                }
                            }

                            if ($model->dispatchAssignment->codriver) {
                                $model->dispatchAssignment->codriver->status = UnitItemStatus::AVAILABLE;
                                if (!$this->saveModel($model->dispatchAssignment->codriver)) {
                                    return false;
                                }
                            }

                            if ($model->dispatchAssignment->truck) {
                                $model->dispatchAssignment->truck->status = UnitItemStatus::AVAILABLE;
                                if (!$this->saveModel($model->dispatchAssignment->truck)) {
                                    return false;
                                }
                            }

                            if ($model->dispatchAssignment->trailer) {
                                $model->dispatchAssignment->trailer->status = UnitItemStatus::AVAILABLE;
                                if (!$this->saveModel($model->dispatchAssignment->trailer)) {
                                    return false;
                                }
                            }

                            if ($model->dispatchAssignment->trailer2) {
                                $model->dispatchAssignment->trailer2->status = UnitItemStatus::AVAILABLE;
                                if (!$this->saveModel($model->dispatchAssignment->trailer2)) {
                                    return false;
                                }
                            }

                            $model->status = LoadStatus::DROPPED;

                            $loadNote = new LoadNote();
                            $loadNote->load_id = $model->id;
                            $loadNote->last_action = 8;
                            $loadNote->notes = 'Drop In-Transit';

                            $loadMovement = new LoadMovement();
                            $loadMovement->action = LoadMovementAction::DROP;
                            $loadMovement->unit_id = $model->dispatchAssignment->unit_id;
                            $loadMovement->load_id = $model->id;
                            $loadMovement->arrived_date = $form->drop_date;
                            $loadMovement->arrived_time_in = $form->drop_time_from;
                            $loadMovement->arrived_time_out = $form->drop_time_to;
                            $loadMovement->arrived_time_out = $form->drop_time_to;
                            $loadMovement->location_id = $form->location_id;
                            $loadMovement->truck_id = $model->dispatchAssignment->truck_id;
                            $loadMovement->trailer_id = $model->dispatchAssignment->trailer_id;
                            $loadMovement->driver_id = $model->dispatchAssignment->driver_id;

                            return $this->batchSaveModel([$model, $form, $loadNote, $loadMovement]);
                        }), ['index']
                    );
                }
            ],
            'add-note' => [
                'class' => $fpa,
                'formClass' => AddNote::class,
                'before' => function ($actionParams) {
                    return $this->findModel($actionParams['id']);
                },
                'init' => function (AddNote $form, Load $model) {
                    $form->date = DateTime::nowDateYMD();
                    $form->time = DateTime::nowTime();
                },
                'save' => function (AddNote $form, Load $model, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    $note = new LoadNote();
                    $note->load_id = $model->id;
                    $note->created_at = "{$form->date} {$form->time}:00";
                    $note->last_action = $form->lastAction;
                    $note->notes = $form->notes;
                    return $action->saveResp($this->saveModel($note));
                }
            ],
            'arrive' => [
                'class' => $fpa,
                'before' => function ($actionParams) {
                    $model = Load::findOne($actionParams['id']);
                    return ($model && ($model->status == LoadStatus::ENROUTED)) ? $model : null;
                },
                'viewOnBeforeFail' => 'dispatch-errors',
                'viewParamsOnBeforeFail' => ['title' => Yii::t('app', 'Arrive Load'), 'errors' => [Yii::t('app', 'Load status error')]],
                'viewParams' => function ($model) {
                    return ['loadModel' => $model];
                },
                'form' => function (Load $model) {
                    $form = new Arrival();
                    $form->date = DateTime::nowDateYMD();
                    $form->in = $form->out = DateTime::nowTime();
                    $form->trailerDisposition = Arrival::KEEP;
                    $form->postDeliveryOptions = Arrival::LEAVE;
                    return $form;
                },
                'save' => function (Arrival $form, Load $load, string $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $result = Yii::$app->transaction->exec(function () use ($form, $load) {
                        $load->arrived_date = $form->date;
                        $load->arrived_time_in = $form->in;
                        $load->arrived_time_out = $form->out;
                        $load->signed_for_by = $form->signedForBy;
                        $load->status = LoadStatus::COMPLETED;
                        if (!$this->saveModel($load)) {
                            Yii::$app->getSession()->addFlash("error", "Error while LOAD update: " . print_r($load->errors, true));
                            return false;
                        }

                        $loadNote = new LoadNote();
                        $loadNote->load_id = $load->id;
                        $loadNote->notes = sprintf('Status changed to "%s"', LoadStatus::COMPLETED);
                        if (!$this->saveModel($loadNote)) {
                            return false;
                        }

                        if ($form->signedForBy) {
                            $loadNote = new LoadNote();
                            $loadNote->load_id = $load->id;
                            $loadNote->notes = sprintf('Arrival: Signed For By %s', $form->signedForBy);
                            if (!$this->saveModel($loadNote)) {
                                return false;
                            }
                        }

                        $dispatchAssignment = $load->dispatchAssignment;

                        if ($form->trailerDisposition == Arrival::KEEP) {
                            if ($dispatchAssignment->trailer) {
                                $dispatchAssignment->trailer->status = TrailerStatus::AVAILABLE;
                                if (!$this->saveModel($dispatchAssignment->trailer)) {
                                    Yii::$app->getSession()->addFlash("error", "Error while TRAILER update: " . print_r($dispatchAssignment->trailer->errors, true));
                                    return false;
                                }
                            }
                            if ($dispatchAssignment->trailer2) {
                                $dispatchAssignment->trailer2->status = TrailerStatus::AVAILABLE;
                                if (!$this->saveModel($dispatchAssignment->trailer2)) {
                                    Yii::$app->getSession()->addFlash("error", "Error while TRAILER2 update: " . print_r($dispatchAssignment->trailer2->errors, true));
                                    return false;
                                }
                            }
                        } elseif ($form->trailerDisposition == Arrival::DROP) {
                            $array = [];
                            if ($dispatchAssignment->trailer) {
                                array_push($array, $dispatchAssignment->trailer->id);
                                $dispatchAssignment->trailer->status = TrailerStatus::DROP_UNLOAD;
                                if (!$this->saveModel($dispatchAssignment->trailer)) {
                                    Yii::$app->getSession()->addFlash("error", "Error while TRAILER update: " . print_r($dispatchAssignment->trailer->errors, true));
                                    return false;
                                }
                            }
                            if ($dispatchAssignment->trailer2) {
                                array_push($array, $dispatchAssignment->trailer2->id);
                                $dispatchAssignment->trailer2->status = TrailerStatus::DROP_UNLOAD;
                                if (!$this->saveModel($dispatchAssignment->trailer2)) {
                                    Yii::$app->getSession()->addFlash("error", "Error while TRAILER2 update: " . print_r($dispatchAssignment->trailer2->errors, true));
                                    return false;
                                }
                            }
                            if ($array && $dispatchAssignment->unit) {
                                $changed = false;
                                if (ArrayHelper::isIn($dispatchAssignment->unit->trailer_id, $array)) {
                                    $dispatchAssignment->unit->trailer_id = null;
                                    $changed = true;
                                }
                                if (ArrayHelper::isIn($dispatchAssignment->unit->trailer_2_id, $array)) {
                                    $dispatchAssignment->unit->trailer_2_id = null;
                                    $changed = true;
                                }
                                if ($changed && !$this->saveModel($dispatchAssignment->unit, false)) {
                                    Yii::$app->getSession()->addFlash("error", "Error while UNIT update: " . print_r($dispatchAssignment->unit->errors, true));
                                    return false;
                                }
                            }
                        }

                        if ($form->postDeliveryOptions == Arrival::LEAVE) {
                            foreach (
                                [
                                    'unit' => UnitItemStatus::AVAILABLE,
                                    'driver' => UnitItemStatus::AVAILABLE,
                                    'codriver' => UnitItemStatus::AVAILABLE,
                                    'truck' => TruckStatus::AVAILABLE
                                ] as $attr => $status
                            ) {
                                if ($dispatchAssignment->$attr) {
                                    $dispatchAssignment->$attr->status = $status;
                                    if (!$this->saveModel($dispatchAssignment->$attr, false)) {
                                        Yii::$app->getSession()->addFlash("error", "Arrival::LEAVE Error while " . strtoupper($attr) . " update: " . print_r($dispatchAssignment->$attr->errors, true));
                                        return false;
                                    }
                                }
                            }

                            if (($lastStop = $load->getLastLoadStop()) && $lastStop->company_id) {
                                $formData = [
                                    'date' => $form->date,
                                    'location_id' => $lastStop->company_id
                                ];
                                foreach (['unit', 'truck', 'trailer', 'trailer2'] as $attr) {
                                    if ($dispatchAssignment->$attr && !$this->addTrackingLog($formData, $dispatchAssignment->$attr)) {
                                        Yii::$app->getSession()->addFlash("error", "Error adding tracking info to " . strtoupper($attr) . ": " . print_r($dispatchAssignment->$attr->errors, true));
                                        return false;
                                    }
                                }
                            }
                        }
                        return true;
                    });
                    if ($result) {
                        Yii::$app->getSession()->addFlash('success', 'Load status has changed successfully');
                    }
                    return $action->saveResp($result, ['index']);
                }
            ]
        ];
    }

    public function actionNotes($id)
    {
        $grid = new Grid([
            'dataProvider' => new ActiveDataProvider([
                'query' => LoadNote::find()->where(['load_id' => $id])->orderBy(['created_at' => SORT_ASC]),
                'pagination' => false
            ]),
            'columns' => [
                new DataColumn([
                    'value' => function ($model) {
                        /** @var LoadNote $model */
                        // TODO: temp
                        return $model->createdBy->username;
                    }
                ]),
                new DataColumn([
                    'value' => function ($model) {
                        /** @var LoadNote $model */
                        return $model->lastAction ? $model->lastAction->id : "";
                    }
                ]),
                new DataColumn([
                    'value' => function ($model) {
                        /** @var LoadNote $model */
                        return Yii::$app->formatter->asDate($model->created_at); // TODO to DateHelper
                    }
                ]),
                new DataColumn([
                    'value' => function ($model) {
                        /** @var LoadNote $model */
                        return Yii::$app->formatter->asTime($model->created_at); // TODO to DateHelper
                    }
                ]),
                new DataColumn([
                    'value' => function ($model) {
                        /** @var LoadNote $model */
                        return $model->lastAction->description;
                    }
                ]),
                'notes',
            ]
        ]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $grid->getData();
    }

    public function actionRouteMap($id)
    {
        $model = $this->findModel($id);
        $stops = array_map(function (LoadStop $model) {
            return [
                'model' => $model,
                'point' => LoadStop::getPoint($model, Location::POINT_FORMAT_SHORT),
            ];
        }, $model->getLoadStops()->joinWith('company')->orderBy(['stop_number' => 'SORT_ASC'])->all());
        $stops = array_filter($stops, function ($array) {
            return !empty($array['point']);
        });
        if (count($stops) < 2) {
            throw new BadRequestHttpException();
        }

        $postBody = [
            'Map' => [
                'Viewport' => [
                    'Center' => null,
                    'ScreenCenter' => null,
                    'ZoomRadius' => 0,
                    'CornerA' => null,
                    'CornerB' => null,
                ],
                'Projection' => 0,
                'Style' => 0,
                'ImageOption' => 0,
                'Width' => 800,
                'Height' => 600,
                'Drawers' => [8, 2, 21, 22, 17, 11, 26, 6, 7],
                'LegendDrawer' => [
                    [
                        'Type' => 0,
                        'DrawOnMap' => false
                    ]
                ]
            ],
            'Routes' => [
                [
                    'Stops' => []
                ]
            ]
        ];
        foreach ($stops as $item) {
            /** @var LoadStop $stop */
            $stop = $item['model'];
            $company = $stop->company;

            $StreetAddress = null;
            if ($stop->getAddress()) {
                $StreetAddress = $stop->getAddress();
            }
            $City = null;
            if ($stop->getCity()) {
                $City = $stop->getCity();
            }
            $State = null;
            if ($stop->getStateCode()) {
                $State = $stop->getStateCode();
            }
            $Zip = null;
            if ($stop->getZip()) {
                $Zip = $stop->getZip();
            }
            $data = [
                'Address' => [
                    'StreetAddress' => $StreetAddress,
                    'City' => $City,
                    'State' => $State,
                    'Zip' => $Zip,
                ],
                'Coords' => $item['point'],
            ];
            array_push($postBody['Routes'][0]['Stops'], $data);
        }

        /** @var PCMiler $pcmiler */
        $pcmiler = Yii::$app->pcmiler;
        $imgData = $pcmiler->mapRoutes($postBody);
        if (!$imgData) {
            //   throw new ServerErrorHttpException();
            return $this->renderAjax('routeMapError', ['id' => $model->id]);
        }
        return $this->renderAjax('routeMap', ['id' => $model->id, 'imgData' => base64_encode($imgData)]);
    }

    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
        } catch (Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            Yii::$app->getSession()->addFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) {
            return $this->redirect(Url::previous());
        } elseif (isset(Yii::$app->session['__crudReturnUrl']) && Yii::$app->session['__crudReturnUrl'] != '/') {
            Url::remember(null);
            $url = Yii::$app->session['__crudReturnUrl'];
            Yii::$app->session['__crudReturnUrl'] = null;
            return $this->redirect($url);
        } else {
            Yii::$app->getSession()->addFlash('success', 'Load have been deleted');
            return $this->redirect(['index']);
        }
    }

    public function actionEditAccessory($load, $id = null)
    {
        $loadModel = Load::findOne($load);
        if ($id)
            $accessory = LoadAccessory::findOne(['id' => $id]);
        else {
            $accessory = new LoadAccessory();
            $accessory->load_id = $loadModel->id;
        }

        if ($this->request->isPost) {
            try {
                if ($accessory->load($_POST) && $accessory->save()) {
                    Yii::$app->getSession()->addFlash('success', "Load have been updated successfully");
                }
            } catch (Exception $e) {
                $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
                Yii::$app->getSession()->addFlash('error', $msg);
            }
            return $this->redirect(['load/edit', 'id' => $load]);
        } else {
            return $this->renderAjax('accessory-form', ['model' => $accessory]);
        }
    }

    /**
     * @param $load
     * @param $id
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteAccessory($load, $id)
    {
        $accessory = LoadAccessory::findOne(['id' => $id]);
        $accessory->delete();
        return $this->redirect(['load/edit', 'id' => $load]);
    }

    public function actionAjaxListAccessories($load, $matrix)
    {
        $load = Load::findOne(['id' => $load]);
        $matrix = AccessorialMatrix::findOne(['id' => $matrix]);
        /** @var AccessorialPay[] $data */
        // FIXME add filter by accessorialRating->accessorial_rating_matrix
        $data = AccessorialPay::find()->where(['office_id' => $load->office_id, 'inactive' => false])->all();
        $result = [];
        foreach ($data as $item) {
            if ($item->accessorialRating->accessorial_rating_matrix == $matrix->matrix_no) {
                $result[] = [
                    'id' => $item->id,
                    'label' => $item->accessorialRating->description,
                ];
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    public function actionAjaxAccessoryRate($accessory)
    {
        $acc = AccessorialPay::findOne(['id' => $accessory]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $acc->rate_each ?? 0;
    }

    public function actionAjaxPreviewRates()
    {
        $data = $this->request->get();
        $load = $data['load'];
        $loadModel = $this->findModel($load);
        $loadModel->rate = $data['rate'];
        $loadModel->rate_by = $data['rateBy'];
        $loadModel->discount_percent = $data['discount'];
        $loadModel->updateRate();

        $result = [
            'gross' => Yii::$app->formatter->asDecimal($loadModel->gross),
            'discount_amount' => Yii::$app->formatter->asDecimal($loadModel->discount_amount),
            'freight' => Yii::$app->formatter->asDecimal($loadModel->freight),
            'accessorials' => Yii::$app->formatter->asDecimal($loadModel->accessories),
            'total' => Yii::$app->formatter->asDecimal($loadModel->total)
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    public function actionDispatchSummary($load)
    {
        $loadModel = Load::findOne($load);
        return $this->renderAjax('dispatch-summary', [
            'model' => $loadModel,
            'loadMovements' => $loadModel->getLoadMovements()->alias('t0')->joinWith(['loadStop.company.state', 'location.state', 'driver', 'truck', 'trailer'])->orderBy(['t0.created_at' => SORT_ASC])->all()
        ]);
    }

    public function actionCancel($load)
    {
        if ($this->request->isPost) {
            $loadModel = Load::findOne($load);
            /** @var Load $loadModel */
            $loadModel->changeStatus(LoadStatus::CANCELLED);
            $loadModel->addNote("Load status have been changed to " . LoadStatus::CANCELLED);
            return $this->redirect(['index']);
        }
    }

    public function actionDuplicateLoad($load)
    {
        $loadModel = Load::findOne($load);
        /** @var Load $loadModel */
        $stops = $loadModel->getLoadStopsOrdered();
        $firstStop = $stops[0];
        $duplicateLoad = new DuplicateLoad();

        if ($this->request->isPost) {
            try {
                if ($duplicateLoad->load($_POST) && $duplicateLoad->validate()) {
                    $this->duplicateLoad($loadModel, $duplicateLoad);
                }
            } catch (Exception $e) {
                $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
                Yii::$app->getSession()->addFlash('error', $msg);
            }
            return $this->redirect(['index']);
        }

        $duplicateLoad->copiesToMake = 1;
        $duplicateLoad->dateReceived = $loadModel->received;
        $duplicateLoad->pickupDate = $firstStop->available_from;

        return $this->renderAjax('duplicate-load', [
            'model' => $loadModel,
            'duplicateLoad' => $duplicateLoad,
        ]);
    }

    public function actionChangeStatus($load)
    {
        $loadModel = Load::findOne($load);
        /** @var Load $loadModel */

        if ($this->request->isPost) {
            $status = $_POST['Load']['status'];
            $loadModel->changeStatus($status);
            $loadModel->addNote("Load status have been changed to " . $status);
            return $this->redirect(['edit', 'id' => $load]);
        }

        return $this->renderAjax('change-status', [
            'model' => $loadModel,
        ]);
    }

    public function actionDeliveryReceipt($id)
    {
        $model = $this->findModel($id);
        $form = new DeliveryReceipt();
        $form->type = DeliveryReceiptTypes::HIDE_REVENUE; // default value in select
        if (Yii::$app->request->isPost) {
            $form->load(Yii::$app->request->post());
            if ($form->validate()) {
                $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                    'content' => $this->renderPartial('_delivery-receipt', [
                        'company' => Company::find()->alias('t')->andWhere(['t.id' => Utils::getParam('companyId')])->joinWith('state')->one(),
                        'load' => $model,
                        'type' => $form->type,
                    ])
                ]));
                return $pdf->render();
            } else {
                return $this->redirect(['edit', 'id' => $model->id]);
            }
        }
        return $this->renderAjax('delivery-receipt-type', [
            'form' => $form,
        ]);
    }

    public function actionBillOfLadingPp($load)
    {
        $loadModel = Load::findOne($load);
        $form = new BillOfLadingPp();
        $form->freightChargesType = BillOfLadingType::SHOW_FREIGHT_CHARGES;
        $form->viewType = BillOfLadingType::STANDART_VIEW;
        $form->billingNoticeType = BillOfLadingType::SHOW_BILLING_NOTICE;
        $form->carrierNameType = BillOfLadingType::SHOW_CARRIER_NAME;
        $form->phoneNumbersType = BillOfLadingType::SHOW_PHONE_NUMBERS;
        /** @var Load $loadModel */
        if (Yii::$app->request->isPost) {
            $form->load(Yii::$app->request->post());
            if ($form->validate()) {
                $load = Load::find()->alias('t')->joinWith(['billTo.state', 'loadStops.company', 'loadStops.state'])->andWhere(['t.id' => $loadModel->id])->one();
                if (!$load || !$load->billTo) {
                    throw new NotFoundHttpException();
                }
                $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                    'format' => Pdf::FORMAT_A3,
                    'content' => $this->renderPartial('_bill-of-Lading-pp', [
                        'company' => Company::find()->alias('t')->andWhere(['t.id' => Utils::getParam('companyId')])->joinWith('state')->one(),
                        'load' => $load,
                        'freightChargesType' => $form->freightChargesType,
                        'viewType' => $form->viewType,
                        'billingNoticeType' => $form->billingNoticeType,
                        'carrierNameType' => $form->carrierNameType,
                        'phoneNumbersType' => $form->phoneNumbersType
                    ])
                ]));
                return $pdf->render();
            } else {
                return $this->redirect(['edit', 'id' => $loadModel->id]);
            }
        }
        return $this->renderAjax('bill-of-Lading-pp', [
            'form' => $form,
        ]);
    }

    public function actionLoadsheet($load)
    {
        $loadModel = Load::findOne($load);
        $form = new Loadsheet();
        $form->revenue = LoadsheetType::SHOW_ALL_REVENUE;
        $form->directions = LoadsheetType::SHOW_DIRECTIONS;
        $form->stopNotes = LoadsheetType::SHOW_STOP_NOTES;
        /** @var Load $loadModel */
        if (Yii::$app->request->isPost) {
            $form->load(Yii::$app->request->post());
            if ($form->validate()) {
                $load = Load::find()->alias('t')->joinWith(['billTo.state', 'loadStops.company', 'loadStops.state'])->andWhere(['t.id' => $loadModel->id])->one();
                if (!$load || !$load->billTo) {
                    throw new NotFoundHttpException();
                }
                $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                    'content' => $this->renderPartial('_loadsheet', [
                        'company' => Company::find()->alias('t')->andWhere(['t.id' => Utils::getParam('companyId')])->joinWith('state')->one(),
                        'load' => $load,
                        'revenue' => $form->revenue,
                        'directions' => $form->directions,
                        'stopNotes' => $form->stopNotes
                    ])
                ]));
                return $pdf->render();
            } else {
                return $this->redirect(['edit', 'id' => $loadModel->id]);
            }
        }
        return $this->renderAjax('loadsheet', [
            'form' => $form,
        ]);
    }

    /**
     * @param $load
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionReset($load)
    {
        if ($this->request->isPost) {
            $loadModel = Load::findOne($load);
            /** @var Load $loadModel */
            $loadModel->dispatchAssignment->delete();
            $loadModel->changeStatus(LoadStatus::AVAILABLE);
            $loadModel->addNote("Load status have been changed to " . LoadStatus::AVAILABLE);
            return $this->redirect(['edit', 'id' => $load]);
        }
    }

    public function actionArrived()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Load::find()
                ->with(['loadAccessories', 'dispatchAssignment'])
                ->where(['in', 'status', [LoadStatus::COMPLETED]]),
            'pagination' => false
        ]);

        Tabs::clearLocalStorage();
        Url::remember();
        Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('arrived', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTransportAgreement($id)
    {
        /** @var Load $load */
        $load = Load::find()->alias('t')->joinWith(['billTo.state', 'loadStops.company', 'loadStops.state'])->andWhere(['t.id' => $id])->one();
        if (!$load || !$load->billTo) {
            throw new NotFoundHttpException();
        }
        $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
            'content' => $this->renderPartial('_transport-agreement', [
                'company' => Company::find()->alias('t')->andWhere(['t.id' => Utils::getParam('companyId')])->joinWith('state')->one(),
                'load' => $load
            ])
        ]));
        return $pdf->render();
    }
    public function actionDispatchConfirmation($id)
    {
        /** @var Load $load */
        $load = Load::find()->alias('t')->joinWith(['billTo.state', 'loadStops.company', 'loadStops.state'])->with('dispatchAssignment')->andWhere(['t.id' => $id])->one();
        if (!$load || !$load->billTo) {
            throw new NotFoundHttpException();
        }
        $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
            'content' => $this->renderPartial('_dispatch-confirmation', [
                'company' => Company::find()->alias('t')->andWhere(['t.id' => Utils::getParam('companyId')])->joinWith('state')->one(),
                'load' => $load
            ])
        ]));
        return $pdf->render();
    }
    public function actionAccessorialNotification($id)
    {
        /** @var Load $load */
        $load = Load::find()->alias('t')->joinWith(['billTo.state', 'loadStops.company', 'loadStops.state'])->andWhere(['t.id' => $id])->one();
        if (!$load || !$load->billTo) {
            throw new NotFoundHttpException();
        }
        $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
            'content' => $this->renderPartial('_accessorial-notification', [
                'company' => Company::find()->alias('t')->andWhere(['t.id' => Utils::getParam('companyId')])->joinWith('state')->one(),
                'load' => $load
            ])
        ]));
        return $pdf->render();
    }

    public function actionInvoice($id)
    {
        /** @var Load $load */
        $load = Load::find()->alias('t')->joinWith(['billTo.state', 'loadStops.company', 'loadStops.state', 'office', 'billTo.terms0'])->andWhere(['t.id' => $id/*, 't.status' => LoadStatus::COMPLETED*/])->one();
        if (!$load || !$load->billTo) {
            throw new NotFoundHttpException();
        }
        $accountingDefault = AccountingDefault::find()->joinWith(['nameOnFactoredInvoicesCar.state', 'nameOnFactoredInvoicesCus.state', 'nameOnFactoredInvoicesVen.state'])->one();
        $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
            'content' => $this->renderPartial('_invoice', [
                'company' => Company::find()->alias('t')->andWhere(['t.id' => Utils::getParam('companyId')])->joinWith('state')->one(),
                'load' => $load,
                'accountingDefault' => $accountingDefault
            ])
        ]));
        return $pdf->render();
    }

    protected function duplicateLoad(Load $load, DuplicateLoad $form)
    {
        for ($i = 0; $i < $form->copiesToMake; $i++) {
            $newLoad = new Load();
            $newLoad->attributes = $load->attributes;
            $newLoad->received = $form->dateReceived;
            if (!$form->copyReferenceNumbers)
                unset($newLoad->customer_reference);
            $newLoad->save();
            $s = 0;

            // STOPS
            foreach ($load->getLoadStopsOrdered() as $stop) {
                $s++;
                $newStop = new LoadStop;
                $newStop->attributes = $stop->attributes;
                $newStop->load_id = $newLoad->id;
                if ($s == 1)
                    $newStop->available_from = $form->pickupDate;
                $newStop->save();
            }
            if ($form->copyImages) {
                // TODO
            }

            if ($form->postToFreightTracking) {
                // TODO
            }

            if ($form->copyLoadNotes) {
                foreach ($load->loadNotes as $note) {
                    $newNote = new LoadNote();
                    $newNote->attributes = $note->attributes;
                    $newNote->load_id = $newLoad->id;
                    $newNote->save();
                }
            }
        }

        if ($form->copyLoadNotes) {
            foreach ($load->loadNotes as $note) {
                $newNote = new LoadNote();
                $newNote->attributes = $note->attributes;
                $newNote->load_id = $newLoad->id;
                $newNote->save();
            }
        }
    }

    protected function changeUnitStatus(DispatchAssignment $dispatchAssignment, string $status)
    {
        if ($dispatchAssignment->unit) {
            $dispatchAssignment->unit->status = $status;
            if (!$dispatchAssignment->unit->save()) {
                Yii::$app->getSession()->addFlash("error", "Error change status for UNIT: " . print_r($dispatchAssignment->unit->errors, true));
                return false;
            }
        }

        if ($dispatchAssignment->driver) {
            $dispatchAssignment->driver->status = $status;
            if (!$dispatchAssignment->driver->save()) {
                Yii::$app->getSession()->addFlash("error", "Error change status for DRIVER: " . print_r($dispatchAssignment->driver->errors, true));
                return false;
            }
        }

        if ($dispatchAssignment->codriver) {
            $dispatchAssignment->codriver->status = $status;
            if (!$dispatchAssignment->codriver->save()) {
                Yii::$app->getSession()->addFlash("error", "Error change status for CODRIVER: " . print_r($dispatchAssignment->codriver->errors, true));
                return false;
            }
        }

        if ($dispatchAssignment->truck) {
            $dispatchAssignment->truck->status = $status;
            if (!$dispatchAssignment->truck->save()) {
                Yii::$app->getSession()->addFlash("error", "Error change status for TRUCK: " . print_r($dispatchAssignment->truck->errors, true));
                return false;
            }
        }

        if ($dispatchAssignment->trailer) {
            $dispatchAssignment->trailer->status = $status;
            if (!$dispatchAssignment->trailer->save()) {
                Yii::$app->getSession()->addFlash("error", "Error change status for TRAILER: " . print_r($dispatchAssignment->trailer->errors, true));
                return false;
            }
        }

        if ($dispatchAssignment->trailer2) {
            $dispatchAssignment->trailer2->status = $status;
            if (!$dispatchAssignment->trailer2->save()) {
                Yii::$app->getSession()->addFlash("error", "Error change status for TRAILER2: " . print_r($dispatchAssignment->trailer2->errors, true));
                return false;
            }
        }

        return true;
    }

    protected function findModel($id)
    {
        if (($model = Load::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    // N
    public function actionBoard()
    {
        $q = DispatchAssignment::find()->
        joinWith(['createdBy da_d', 'driver d', 'codriver cd', 'truck tk', 'trailer tr', 'load l', 'load.loadStops.company.state lscs', 'load.loadStops.state lss'])->
        andWhere(['l.status' => [LoadStatus::AVAILABLE, LoadStatus::ENROUTED, LoadStatus::COMPLETED]])->
        orderBy(new Expression("concat_ws(', ',da_d.last_name,da_d.first_name) ASC, dispatch_start_date ASC, dispatch_start_time_in ASC"));
        return $this->render('board', ['dataProvider' => new ActiveDataProvider(['query' => $q, 'pagination' => false])]);
    }

    public function actionIndex()
    {
        $q1 = Load::find()->alias('t')->joinWith(['loadStops ls', 'dispatchAssignment.driver da_d'])->where(['AND', ['IN', 't.status', [LoadStatus::AVAILABLE, LoadStatus::RESERVED, LoadStatus::POSSIBLE]], ['OR', ['t.release' => null], ['t.created_by' => Yii::$app->user->id]]])
            ->orWhere(['AND', ['IN', 't.status', [LoadStatus::AVAILABLE, LoadStatus::RESERVED, LoadStatus::POSSIBLE]], ['>', 't.release', new Expression('NOW()')]])
            ->orWhere(['t.status' => LoadStatus::PENDING, 't.created_by' => Yii::$app->user->id])
            ->orWhere(['t.status' => LoadStatus::DROPPED]);
        $q2 = Load::find()->alias('t')->joinWith(['loadStops ls', 'dispatchAssignment.driver da_d'])->andWhere(['t.status' => LoadStatus::ENROUTED]);
        return $this->render('index', [
            'dataProvider1' => new ActiveDataProvider(['query' => $q1, 'pagination' => false]),
            'dataProvider2' => new ActiveDataProvider(['query' => $q2, 'pagination' => false])
        ]);
    }

    public function actionEdit($id = null)
    {
        if (is_null($id)) {
            /** @var Load $lastLoad */
            $lastLoad = Load::find()->orderBy(['created_at' => SORT_DESC])->one();
            if ($lastLoad && $lastLoad->isEmpty()) {
                $model = $lastLoad;
            } else {
                $model = new Load();
                $model->booked_by = Yii::$app->user->id;
                $model->status = LoadStatus::AVAILABLE;
                $model->received = Yii::$app->formatter->asDate('now', Yii::$app->params['formats']['db']);
                /** @var Office $office */
                $office = Office::find()->orderBy(['id' => SORT_ASC])->one();
                if ($office) {
                    $model->office_id = $office->id;
                }
                $model->save();
                Cron::create(CronTask::DELETE_EMPTY_LOAD, ['id' => $model->id], 3600, 2, 3600);
            }
            return $this->redirect(['edit', 'id' => $model->id]);
        } else {
            $model = $this->findModel($id);
        }
        if (Yii::$app->request->isPost) {
            $model->load($_POST);
            $validationErrors = ActiveForm::validate($model);
            if (!$validationErrors) {
                $model->save();
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $validationErrors;
        }
        return $this->render('edit', ['model' => $model]);
    }
}
