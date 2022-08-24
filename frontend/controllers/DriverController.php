<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\CronTask;
use common\enums\FuelCardType;
use common\enums\LoadStatus;
use common\enums\Permission;
use common\enums\Scope;
use common\models\AccountingDefault;
use common\models\Company;
use common\models\Cron;
use common\models\Driver;
use common\models\DriverFuelCard;
use common\models\Load;
use common\models\User;
use frontend\forms\driver\Register;
use frontend\forms\driver\Unregister;
use kartik\mpdf\Pdf;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use ZipArchive;

/**
 * This is the class for controller "DriverController".
 */
class DriverController extends base\DriverController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'create', 'update', 'delete','tax', 'invoices','register', 'unregister'],
                'permissions' => [Permission::ADD_EDIT_DRIVERS]
            ]
        ];
    }

    public function allowedAttributes()
    {
        $driver = new Driver();
        return [
            'tax' => [
                $driver->formName() => [
                    'state_id', 'pay_frequency'
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $fpaClass = FormProcessingAction::class;
        return [
            'register' => [
                'class' => $fpaClass,
                'formClass' => '\frontend\forms\driver\Register',
                'before' => function ($actionParams) {
                    $this->action->message = Yii::t('app', 'Driver has already registered');
                    /** @var Driver $driver */
                    $driver = Driver::find()
                        ->alias('d')
                        ->joinWith('user u')
                        ->andWhere(['d.id' => $actionParams['id']])
                        ->andWhere(new Expression('(d.user_id IS NULL) OR (u.status <> :status)', [':status' => User::STATUS_ACTIVE]))
                        ->one();
                    if ($driver) {
                        $user = $driver->user ?: new User();
                        $user->email = $driver->email_address;
                        if (!$user->validate('email')) {
                            $this->action->statusCode = 400;
                            $this->action->message = $user->getFirstError('email');
                            $driver = null;
                        }
                    }
                    return $driver;
                },
                'save' => function (Register $form, Driver $model, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $password = '';
                    for ($i = 1; $i <= 6; $i++) {
                        $password .= rand(0, 9);
                    }

                    $sent = Yii::$app->getMailer()
                        ->compose(['html' => 'driverRegister-html', 'text' => 'driverRegister-text'], ['model' => $model, 'password' => $password])
                        ->setTo($model->email_address)
                        ->setFrom(Yii::$app->params['senderEmail'])
                        ->setSubject(Yii::$app->params['welcomeSubject'])
                        ->send();
                    if (!$sent) {
                        return $action->saveResp(false);
                    }
                    $f = Yii::$app->transaction->exec(function () use ($model, $password) {
                        if (!$model->user_id) {
                            $user = new User();
                            $user->generateAuthKey();
                            $user->scope = Scope::DRIVER;
                        } else {
                            $user = $model->user;
                        }
                        $user->password = $password;
                        $user->status = User::STATUS_ACTIVE;
                        $user->email = $model->email_address;
                        if (!$this->saveModel($user)) {
                            return false;
                        }

                        if (!$model->user_id) {
                            $model->user_id = $user->id;
                            if (!$this->saveModel($model)) {
                                return false;
                            }
                        }
                        return true;
                    });
                    return $action->saveResp($f);
                }
            ],
            'unregister' => [
                'class' => $fpaClass,
                'formClass' => '\frontend\forms\driver\Unregister',
                'before' => function ($actionParams) {
                    $this->action->message = Yii::t('app', 'Driver has already unregistered');
                    return Driver::find()
                        ->alias('d')
                        ->joinWith('user u')
                        ->where(['d.id' => $actionParams['id'], 'u.status' => User::STATUS_ACTIVE])
                        ->one();
                },
                'save' => function (Unregister $form, Driver $model, string $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $user = $model->user;
                    $user->status = User::STATUS_INACTIVE;

                    return $action->saveResp($this->saveModel($user), ['update', 'id' => $user->id]);
                }
            ],
            'tax' => [
                'class' => $fpaClass,
                'view' => 'tax',
                'before' => function ($actionParams) {
                    return Driver::findOne($actionParams['id']);
                },
                'form' => function (Driver $driver) {
                    return $driver;
                },
                'save' => function (Driver $form, Driver $model, string $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    return $action->saveResp($this->saveModel($form), ['update', 'id' => $model->id]);
                }
            ]
        ];
    }

    private function save()
    {
        $controller = Yii::$app->controller;
        $action = $controller->action;
        $model = ($action->id == 'create') ? new Driver() : $this->findModel($controller->actionParams['id']);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->save()) {
                $fuelCards = $model->driverFuelCards;
                $fuelCardTypes = FuelCardType::getEnums();
                foreach ($fuelCardTypes as $fuelCardType) {
                    $found = false;
                    foreach ($fuelCards as $fuelCard) {
                        if ($fuelCard->card_type == $fuelCardType) {
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $fuelCard = new DriverFuelCard();
                        $fuelCard->driver_id = $model->id;
                        $fuelCard->card_type = $fuelCardType;
                    }

                    $formName = $fuelCard->formName();
                    $formName = substr($formName, 0, strpos($formName, '['));
                    // TODO: psb (filter post)
                    $fuelCard->load(Yii::$app->request->post($formName), $fuelCardType);
                    $fuelCard->save();
                }
                $transaction->commit();
                return $this->redirect('index');
            } else {
                $transaction->rollBack();
            }
        }
        return $this->render($action->id, ['model' => $model]);
    }

    public function actionInvoices($id)
    {
        $loads = Load::find()
            ->alias('t')
            ->joinWith([
                'billTo.state',
                'loadStops.company',
                'loadStops.state',
                'office',
                'billTo.terms0',
                'documents' => function (ActiveQuery $query) {
                    $query->orderBy('created_at');
                },
                'dispatchAssignment AS da'
            ])
            ->andWhere(['t.status' => LoadStatus::COMPLETED])
            ->andWhere(['IS NOT', 't.bill_to', null])
            ->andWhere(['or',
                ['da.driver_id' => $id],
                ['da.codriver_id' => $id]])
            ->orderBy(['t.id' => SORT_ASC])
            ->all();

        /*if (!$loads)
         return $this->replyJson(['message' => Yii::t('app', 'No completed loads were found according to the specified criteria')]);
         
         if (!$download)
         return $this->replyJson(['redirect' => 1]);*/

        $accountingDefault = AccountingDefault::find()
            ->joinWith([
                'nameOnFactoredInvoicesCar.state',
                'nameOnFactoredInvoicesCus.state',
                'nameOnFactoredInvoicesVen.state'
            ])->one();
        $company = Company::find()
            ->alias('t')
            ->andWhere([
                't.id' => Yii::$app->params['companyId']
            ])->joinWith('state')->one();
        $zip = new ZipArchive();
        $tmpDir = sys_get_temp_dir();
        $zipFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid() . '.zip';
        $zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $sleep = 600;
        foreach ($loads as $load) {
            $taFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('ta') . '.pdf';
            $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                'destination' => Pdf::DEST_FILE,
                'filename' => $taFileName,
                'content' => $this->renderPartial('//load/_transport-agreement', [
                    'company' => $company,
                    'load' => $load
                ])
            ]));
            $pdf->render();
            Cron::create(CronTask::DELETE_FILE, ['filename' => $taFileName], $sleep, 2, 3600);

            if ($load->documents) {
                $docFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('d') . '.pdf';
                $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                    'destination' => Pdf::DEST_FILE,
                    'filename' => $docFileName,
                    'content' => $this->renderPartial('//load/_documents', [
                        'models' => $load->documents,
                    ])
                ]));
                $pdf->render();
                Cron::create(CronTask::DELETE_FILE, ['filename' => $docFileName], $sleep, 2, 3600);
            }

            $fileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('r') . '.pdf';
            $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                'destination' => Pdf::DEST_FILE,
                'filename' => $fileName,
                'content' => $this->renderPartial('//load/_invoice', [
                    'company' => $company,
                    'load' => $load,
                    'accountingDefault' => $accountingDefault
                ])
            ]));
            $pdf->addPdfAttachment($taFileName);
            if ($load->documents) {
                $pdf->addPdfAttachment($docFileName);
            }
            $pdf->render();

            Cron::create(CronTask::DELETE_FILE, ['filename' => $fileName], $sleep, 2, 3600);

            $localName = trim(preg_replace('/[^a-zA-Z0-9\s_-]/', '', $load->billTo->name)) . " - {$load->id}.pdf";
            $zip->addFile($fileName, $localName);
        }
        $zip->close();
        Cron::create(CronTask::DELETE_FILE, ['filename' => $zipFileName], $sleep, 2, 3600);
        $attachmentName = sprintf('Invoices_%s.zip', Yii::$app->formatter->asDate('now', Yii::$app->params['formatter']['date']['longFN']));
        return Yii::$app->response->sendFile($zipFileName, $attachmentName);
    }

    public function actionCreate()
    {
        return $this->save();
    }

    public function actionUpdate($id)
    {
        return $this->save();
    }
}
