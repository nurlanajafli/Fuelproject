<?php

namespace frontend\controllers;

use app\models\FuelImport;
use common\actions\FormProcessingAction;
use common\components\ComdataFileParser;
use common\enums\{FuelCardDataProvider as FC, Permission};
use common\helpers\Path;
use common\models\{
    FuelcardAccountConfig as FCC,
    FuelProductCode,
    FuelPurchase,
    State,
    DriverFuelCard
};
use common\widgets\DataTables\Grid;
use frontend\forms\fuel\ImportFuelForm;
use Yii;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\helpers\{VarDumper, Url};
use yii\web\{BadRequestHttpException, HttpException};

class FuelController extends base\BaseController
{
    private array $fuel_fields = [
        'tractor_fuel' => 'TrkFul',
        'other_fuel' => 'DiesEx',
        'prod_group' => 'code',
        'reefer'    => 'RefFul',
        'oil'       => 'Oil'
    ];

    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => [
                    'index', 'parse-file', 'view', 'update', 'delete', 'import', 'update-product-code',
                    'ajax-setup-product-codes', 'ajax-setup-account', 'ajax-import-form',
                    'save-account-config', 'setup-comdata-account', 'process-import'
                ],
                'permissions' => [Permission::FUEL_MANAGEMENT]
            ],
        ];
    }

    public function actions()
    {
        return [
            'update-product-code' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return FuelProductCode::findOne($actionParams['id']);
                },
                'form' => 'proxy',
                'save' => function ($form, $model, $submitButtonCode) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    return $action->saveResp($this->saveModel($form));
                },
            ]
        ];
    }

    /**
     * Updates an existing Fuel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load($_POST) && $model->save()) {
            return $this->redirect(Url::previous());
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Fuel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $output = [
            'redirect' => Url::toRoute('import')
        ];
        
        try {
            $this->findModel($id)->delete();
            $output['res'] = 'success';
            $output['msg'] = 'Fuel Successfully deleted!';
        } catch (\Exception $e) {
            $output['res'] = 'error';
            $output['msg'] = isset($e->errorInfo[2]) ? $e->errorInfo[2] : $e->getMessage();
        }

        return $this->asJson($output);
    }

    public function actionImport()
    {
        Url::remember();
        return $this->render('import');
    }

    public function actionParseFile() {
        // $filePath = Yii::getAlias('@cdn/web'). "/C-367.CLVCH.FM00001.02142022";
        $filePath = Path::join(dirname(dirname(__DIR__)), 'fuel_providers', 'comdata', 'C-367.CLVCH.FM00001.03092022');
        ComdataFileParser::parse($filePath);
    }

    public function actionAjaxSetupProductCodes($provider, $data = 0)
    {
        if ($data) {
            return $this->replyJson((new Grid([
                'dataProvider' => new ActiveDataProvider([
                    'query' => FuelProductCode::find()->with(['ooAcct', 'cdAcct', 'feeAcct'])->where(['provider' => $provider])->orderBy(['description' => SORT_ASC]),
                    'pagination' => false,
                ]),
                'columns' => [
                    'id',
                    'description',
                    'oo_acct',
                    'cd_acct',
                    'fee_amt',
                    'fee_acct'
                ]
            ]))->getData());
        }
        return $this->renderAjax('setup-product-codes', [
            'provider' => $provider
        ]);
    }

    public function actionAjaxSetupAccount($provider)
    {
        $model = FCC::findOne($provider);
        if (!$model) {
            $model = new FCC();
            $model->type = Yii::$app->request->post('provider');
        }

        if ($provider == FC::COMDATA)
            $view = 'setup-comdata-account';
        else
            $view = 'setup-account';

        return $this->renderAjax($view, [
            'provider' => $provider,
            'model' => $model
        ]);
    }

    public function actionSaveAccountConfig()
    {
        if (Yii::$app->request->isPost) {
            $model = FCC::findOne(Yii::$app->request->post('provider'));

            if (!$model) {
                $model = new FCC();
                $model->type = Yii::$app->request->post('provider');
            }
            $model->loadConfig($model->type, Yii::$app->request->post());
            $model->save();
        }

        return $this->redirect(['fuel/import']);
    }

    public function actionAjaxImportForm($provider)
    {
        if ($provider != FC::getKey(FC::COMDATA)) {
            return $this->renderAjax('error');
        }

        $dir = $this->getFileDir();

        $files = \yii\helpers\FileHelper::findFiles($dir);
        $fileNames = [];
        $fileNamesExtra = [];
        foreach ($files as $file) {
            if (preg_match('%/(?!\.)([^/]+)$%m', $file, $regs)) {
                $fileNames[] = $regs[1];
            }
        }

        foreach ($fileNames as $fileName)
            $fileNamesExtra[] = $fileName . " (" . self::human_filesize(filesize($dir . DIRECTORY_SEPARATOR . $fileName)) . ")";

        return $this->renderAjax('import-form', [
            'provider' => $provider,
            'fileNames' => array_combine($fileNames, $fileNamesExtra)
        ]);
    }

    public static function human_filesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    public function actionProcessImport()
    {
        $form = new ImportFuelForm();
        $form->load($this->request->post());

        $file = $this->getFileDir() . DIRECTORY_SEPARATOR . $form->data_file;
        $data = ComdataFileParser::parse($file);
        $parsedData = ComdataFileParser::getParsedData($file);

        Yii::info(["INPUT DATA" => \yii\helpers\Json::encode($data), "TRANSFORMED DATA" => $parsedData], 'fuel_card');

        /* (
            [card] => 1703983991
            [date] => 220225
            [time] => 0130
            [trip] =>
            [driver] => WHITTON KIRK
            [driver_license] =>
            [trailer] =>
            [unit] => 2714
            [fs_city] => SULPHUR SPRS
            [fs_st] => TX
            [transaction_id] => 014009
            [fuel_qty] => 0
            [fuel_ppg] => 0
            [fuel_cost] => 0
            [tractor_fuel_qty] => 107.48
            [tractor_fuel_ppg] => 3.999
            [tractor_fuel_cost] => 429.86
            [tractor_fuel_billing_flag] => Direct Bill
            [reefer_fuel_qty] => 0
            [reefer_fuel_ppg] => 0
            [reefer_fuel_cost] => 0
            [reefer_fuel_billing_flag] =>
            [other_fuel_qty] => 7.16
            [other_fuel_ppg] => 4.001
            [other_fuel_cost] => 28.65
            [oil_qty] => 0
            [oil_total] => 0
            [oil_billing_flag] =>
            [total_amount_due] => 429.86
        ) */

        if (!empty($parsedData['items']) && is_array($parsedData['items'])) {
            $fp = false;
            foreach ($parsedData['items'] as $item) {
                $fp = FuelImport::findOne(['transaction_id' => $item['transaction_id']]);
                foreach ($this->fuel_fields as $key_fuel_field => $fuel_field) {
                    if (!empty($item[$key_fuel_field . '_cost'])) {
                        if (!$fp) {
                            $fp = new FuelImport();
                        }
                        $this->setCommonFields($fp, $item);
                        if ($fuel_field == 'code') {
                            if ( !empty($item[$key_fuel_field . '_val']) && !empty($item[$key_fuel_field . '_code']) ) {
                                $fuel_field = ComdataFileParser::getProdDescByCode($item[$key_fuel_field . '_code']);
                            }
                        }
                        if ($fuel_field) {
                            $FuelCode = FuelProductCode::findOne([
                                'provider' => FC::COMDATA,
                                'description' => $fuel_field
                            ]);
                            $fp->product_code = $FuelCode->id ?? 0;
                            $fp->quantity = $item[$key_fuel_field . '_qty'] ?? 0;
                            $fp->cost = $item[$key_fuel_field . '_cost'] ?? 0;
                            $fp->ppg = $item[$key_fuel_field . '_ppg'] ?? 0;
                            $fp->description = "Comdata {$fuel_field}";
                            $fp->save();
                        }
                    }
                }
            }
        }
        return $this->redirect(['fuel/import']);
    }

    private function setCommonFields(FuelImport $fuelImport, array $item)
    {
        $fuelImport->transaction_id = $item['transaction_id'];
        $dateFormat = date_create_from_format("ymd", $item['date']);
        $fuelImport->purchase_date = $dateFormat->format('Y-m-d');
        $timeParts = str_split($item['time'], 2);
        $fuelImport->purchase_time = $timeParts[0] . ":" . $timeParts[1];
        $fuelImport->unit_id = $item['unit'];
        $fuelImport->vendor = trim($item['fs_vendor']);
        $fuelImport->city = trim($item['fs_city']);
        $fuelImport->card_check_no = $item['card'];
        $fuelImport->driver_id = $fuelImport->state_id = 0;
        if ($state = State::findOne(['state_code' => $item['fs_st']])) {
            $fuelImport->state_id = $state->id;
        }
        if ($driverFuel = DriverFuelCard::findOne(['card_id' => $item['card']])) {
            $fuelImport->driver_id = $driverFuel->driver_id;
        }
    }

    private function findModel($id) {
        if (($model = FuelImport::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    private function getFileDir()
    {
        return Path::join(dirname(dirname(__DIR__)), 'fuel_providers', 'comdata' . DIRECTORY_SEPARATOR . 'outgoing');
    }
}