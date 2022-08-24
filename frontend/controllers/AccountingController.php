<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\BillOfLadingType;
use common\enums\CronTask;
use common\enums\DeliveryReceiptTypes;
use common\enums\LoadsheetType;
use common\enums\LoadStatus;
use common\enums\Permission;
use common\models\AccountingDefault;
use common\models\Company;
use common\models\Cron;
use common\models\Load;
use common\models\Payroll;
use dmstr\bootstrap\Tabs;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii2tech\spreadsheet\Spreadsheet;
use ZipArchive;

class AccountingController extends base\BaseController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['load-billing', 'hold-billing', 'clear-load', 'dispatch-summary'],
                'permissions' => [Permission::LOAD_BILLING_TL]
            ],
            [
                'allow' => true,
                'actions' => ['load-clearing'],
                'permissions' => [Permission::LOAD_CLEARING]
            ],
        ];
    }

    public function allowedAttributes()
    {
        $load = new Load();
        return [
            'clear-load' => [$load->formName() => ['scans']]
        ];
    }

    public function actions()
    {
        return [
            'clear-load' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return Load::find()
                        ->alias('t')
                        ->joinWith(['dispatchAssignment.driver', 'dispatchAssignment.codriver', 'dispatchAssignment.truck'])
                        ->where(['t.id' => $actionParams['id'], 't.status' => LoadStatus::COMPLETED])
                        ->one();
                },
                'form' => function ($load) {
                    return $load;
                },
                'init' => function ($form, $model) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    $action->viewParams['prev'] = false;
                    $action->viewParams['next'] = false;
                    /** @var Load[] $rows */
                    $rows = Load::find()->where(['status' => LoadStatus::COMPLETED])->orderBy(['arrived_date' => SORT_DESC, 'arrived_time_out' => SORT_DESC])->all();
                    foreach ($rows as $k => $row) {
                        if ($row->id == $model->id) {
                            if (isset($rows[$k - 1])) {
                                $action->viewParams['prev'] = Url::toRoute(['clear', 'id' => $rows[$k - 1]->id]);
                            }
                            if (isset($rows[$k + 1])) {
                                $action->viewParams['next'] = Url::toRoute(['clear', 'id' => $rows[$k + 1]->id]);
                            }
                            break;
                        }
                    }
                },
                'save' => function (Load $form, Load $model, string $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    return $action->saveResp($this->saveModel($form), ['load_cleared' => $form->loadcleared, 'backup_cleared' => $form->backupcleared, 'close_modal' => false]);
                }
            ]
        ];
    }

    public function actionLoadBilling($export = null)
    {
        if ($export) {
            /** @var Load[] $loads */
            $loads = Load::find()
                ->alias('t')
                ->joinWith([
                    'billTo.state',
                    'loadStops.company',
                    'loadStops.state',
                    'office',
                    'billTo.terms0',
                    'commodityCommodity',
                    'documents' => function (ActiveQuery $query) {
                        $query->orderBy('created_at');
                    }
                ])
                ->andWhere(['t.status' => LoadStatus::COMPLETED])
                ->andWhere(['IS NOT', 't.bill_to', null])
                ->andWhere(['IN', 't.id', explode(",", $export)])
                ->orderBy(['t.id' => SORT_ASC])
                ->all();

            $accountingDefault = AccountingDefault::find()->joinWith(['nameOnFactoredInvoicesCar.state', 'nameOnFactoredInvoicesCus.state', 'nameOnFactoredInvoicesVen.state'])->one();
            $company = Company::find()->alias('t')->andWhere(['t.id' => Yii::$app->params['companyId']])->joinWith('state')->one();
            $zip = new ZipArchive();
            $tmpDir = sys_get_temp_dir();
            $zipFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid() . '.zip';
            $zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $sleep = 600;
            $invoicesInfo = [];
            foreach ($loads as $k => $load) {
                $imgsFileName = $docFileName = $loadFileName = $drFileName = $billFileName = $taFileName = null;
                if ($documents = $load->billTo->getDocuments()->orderBy(['created_at' => SORT_ASC])->all()) {
                    $imgsFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('imgs') . '.pdf';
                    $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                        'destination' => Pdf::DEST_FILE,
                        'filename' => $imgsFileName,
                        'content' => $this->renderPartial('_document_images', [
                            'documents' => $documents
                        ])
                    ]));
                    $pdf->render();
                    Cron::create(CronTask::DELETE_FILE, ['filename' => $imgsFileName], $sleep, 2, 3600);
                }
                if ($load->billTo->transport_agreement) {
                    $taFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('ta') . '.pdf';
                    $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                        'destination' => Pdf::DEST_FILE,
                        'filename' => $taFileName,
                        'content' => $this->renderPartial('_transport-agreement', [
                            'company' => $company,
                            'load' => $load
                        ])
                    ]));
                    $pdf->render();
                    Cron::create(CronTask::DELETE_FILE, ['filename' => $taFileName], $sleep, 2, 3600);
                }
                if ($load->billTo->bill_of_lading) {
                    $billFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('bill') . '.pdf';
                    $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                        'destination' => Pdf::DEST_FILE,
                        'filename' => $billFileName,
                        //'format' => Pdf::FORMAT_A3,
                        'content' => $this->renderPartial('_bill-of-lading-pp', [
                            'company' => $company,
                            'load' => $load,
                            'freightChargesType' => BillOfLadingType::SHOW_FREIGHT_CHARGES,
                            'viewType' => BillOfLadingType::STANDART_VIEW,
                            'billingNoticeType' => BillOfLadingType::SHOW_BILLING_NOTICE,
                            'carrierNameType' => BillOfLadingType::SHOW_CARRIER_NAME,
                            'phoneNumbersType' => BillOfLadingType::SHOW_PHONE_NUMBERS,
                        ])
                    ]));
                    $pdf->render();
                    Cron::create(CronTask::DELETE_FILE, ['filename' => $billFileName], $sleep, 2, 3600);
                }
                if ($load->billTo->delivery_receipt) {
                    $drFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('dr') . '.pdf';
                    $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                        'destination' => Pdf::DEST_FILE,
                        'filename' => $drFileName,
                        'content' => $this->renderPartial('_delivery-receipt', [
                            'company' => $company,
                            'load' => $load,
                            'type' => DeliveryReceiptTypes::HIDE_REVENUE,
                        ])
                    ]));
                    $pdf->render();
                }
                if ($load->billTo->load_receipts) {
                    $loadFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('load') . '.pdf';
                    $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                        'destination' => Pdf::DEST_FILE,
                        'filename' => $loadFileName,
                        'content' => $this->renderPartial('_loadsheet', [
                            'company' => $company,
                            'load' => $load,
                            'revenue' => LoadsheetType::SHOW_ALL_REVENUE,
                            'directions' => LoadsheetType::SHOW_DIRECTIONS,
                            'stopNotes' => LoadsheetType::SHOW_STOP_NOTES
                        ])
                    ]));
                    $pdf->render();
                }
                $loadDocuments = $load->documents;
                if ($loadDocuments) {
                    $docFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('d') . '.pdf';
                    $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                        'destination' => Pdf::DEST_FILE,
                        'filename' => $docFileName,
                        'content' => $this->renderPartial('//load/_documents', [
                            'models' => $loadDocuments,
                        ])
                    ]));
                    $pdf->render();
                    Cron::create(CronTask::DELETE_FILE, ['filename' => $docFileName], $sleep, 2, 3600);
                }
                $fileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('r') . '.pdf';
                $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                    'destination' => Pdf::DEST_FILE,
                    'filename' => $fileName,
                    'content' => $this->renderPartial('_invoice', [
                        'company' => $company,
                        'load' => $load,
                        'accountingDefault' => $accountingDefault
                    ])
                ]));
                if ($taFileName) $pdf->addPdfAttachment($taFileName);
                if ($billFileName) $pdf->addPdfAttachment($billFileName);
                if ($drFileName) $pdf->addPdfAttachment($drFileName);
                if ($loadFileName) $pdf->addPdfAttachment($loadFileName);
                if ($imgsFileName) $pdf->addPdfAttachment($imgsFileName);
                if ($docFileName) $pdf->addPdfAttachment($docFileName);
                $pdf->render();
                Cron::create(CronTask::DELETE_FILE, ['filename' => $fileName], $sleep, 2, 3600);
                $localName = trim(preg_replace('/[^a-zA-Z0-9\s_-]/', '', $load->billTo->name)) . " - {$load->id}.pdf";
                $zip->addFile($fileName, $localName);

                $invoicesInfo[$k]['invoice_number'] = $load->id;
                $invoicesInfo[$k]['bill_to'] = $load->billTo->name." ".$load->billTo->address_1." ".$load->billTo->address_2." ".sprintf('%s, %s %s', $load->billTo->city, $load->billTo->state ? $load->billTo->state->state_code : '', $load->billTo->zip);
                $invoicesInfo[$k]['terms'] = $load->billTo->terms0 ? $load->billTo->terms0->description : '';
                $invoicesInfo[$k]['reference'] = $load->customer_reference;

                $loadStops = $load->getLoadStopsOrdered();
                $attrStops = [
                    ['attribute' => 'invoice_number'],
                    ['attribute' => 'bill_to'],
                    ['attribute' => 'terms'],
                    ['attribute' => 'reference']
                ];
                $ak= 4;
                foreach ($loadStops as $kl=>$loadStop):
                    $invoicesInfo[$k]['stop_'.$kl.'_company_name'] = $loadStop->getCompanyName();
                    $invoicesInfo[$k]['stop_'.$kl.'_company_city'] = $loadStop->getAddress();
                    $invoicesInfo[$k]['stop_'.$kl.'_company_address'] = sprintf('%s, %s %s', $loadStop->getCity(), $loadStop->getStateCode(), $loadStop->getZip());
                    $invoicesInfo[$k]['stop_'.$kl.'_reference'] = $loadStop->reference;
                    $attrStops[$ak++]['attribute'] = 'stop_'.$kl.'_company_name';
                    $attrStops[$ak++]['attribute'] = 'stop_'.$kl.'_company_city';
                    $attrStops[$ak++]['attribute'] = 'stop_'.$kl.'_company_address';
                    $attrStops[$ak++]['attribute'] = 'stop_'.$kl.'_reference';
                endforeach;

                $invoicesInfo[$k]['pickup_date'] = Yii::$app->formatter->asDate($loadStops[0]->available_from, Yii::$app->params['formats'][1]);
                $invoicesInfo[$k]['delivery_date'] = Yii::$app->formatter->asDate(end($loadStops)->available_thru, Yii::$app->params['formats'][1]);
                $invoicesInfo[$k]['total_pieces'] = Yii::$app->formatter->asDecimal($load->commodity_pieces) ;
                $invoicesInfo[$k]['total_space'] = Yii::$app->formatter->asDecimal($load->commodity_space) ;
                $invoicesInfo[$k]['actual_wgt'] = Yii::$app->formatter->asDecimal($load->commodity_weight) ;
                $invoicesInfo[$k]['bill_miles'] = Yii::$app->formatter->asInteger($load->bill_miles);

                $invoicesInfo[$k]['rate_by'] =  $load->rate_by;
                $invoicesInfo[$k]['rate'] = Yii::$app->formatter->asDecimal($load->rate);
                $invoicesInfo[$k]['discount'] = $load->discount_percent;
                $invoicesInfo[$k]['freight'] = Yii::$app->formatter->asDecimal($load->freight);
                $invoicesInfo[$k]['accessorial'] = Yii::$app->formatter->asDecimal($load->accessories);
                $invoicesInfo[$k]['invoice_total'] = Yii::$app->formatter->asDecimal($load->total);

                $attrStops2 = [
                    ['attribute' => 'pickup_date'],
                    ['attribute' => 'delivery_date'],
                    ['attribute' => 'total_pieces'],
                    ['attribute' => 'total_space'],
                    ['attribute' => 'actual_wgt'],
                    ['attribute' => 'bill_miles'],
                    ['attribute' => 'rate_by'],
                    ['attribute' => 'rate'],
                    ['attribute' => 'discount'],
                    ['attribute' => 'freight'],
                    ['attribute' => 'accessorial'],
                    ['attribute' => 'invoice_total'],
                ];
                $attrStops = ArrayHelper::merge($attrStops, $attrStops2);
            }

            $exporter = new Spreadsheet([
                'title' => 'Invoices_'.Yii::$app->formatter->asDate('now', Yii::$app->params['formatter']['date']['longFN']),
                'startRowIndex' => 4,
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $invoicesInfo
                ]),
                'columns' => $attrStops,
            ]);
            $exporter->renderCell('A1', 'Invoices', [
                'font' => [
                    'size' => '16px',
                ],
            ]);

            $exporter->renderCell('A2', date('m/d/Y'));
            $exporter->mergeCells('A1:J1');
            $exporter->mergeCells('A2:J2');

            $xlsxFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('pp') .'Invoices_'. Yii::$app->formatter->asDate('now', Yii::$app->params['formatter']['date']['longFN']).'.xls';
            $xlsPrettyName = sprintf('Invoices_ %s.xls', Yii::$app->formatter->asDate('now', Yii::$app->params['formatter']['date']['longFN']));
            $exporter->save($xlsxFileName);
            $zip->addFile($xlsxFileName, $xlsPrettyName);
            Cron::create(CronTask::DELETE_FILE, ['filename' => $xlsxFileName], 5 * 60, 2, 3600);

            $zip->close();
            Cron::create(CronTask::DELETE_FILE, ['filename' => $zipFileName], $sleep, 2, 3600);
            $attachmentName = sprintf('Invoices_%s.zip', Yii::$app->formatter->asDate('now', Yii::$app->params['formatter']['date']['longFN']));
            return Yii::$app->response->sendFile($zipFileName, $attachmentName);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Load::find()
                ->with(['loadAccessories', 'dispatchAssignment'])
                ->where(['in', 'status', [LoadStatus::COMPLETED]]),
            'pagination' => false
        ]);
        Tabs::clearLocalStorage();
        Url::remember();
        Yii::$app->session['__crudReturnUrl'] = null;
        return $this->render('load-billing', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLoadClearing()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Load::find()
//                ->with(['loadAccessories', 'dispatchAssignment'])
                ->where(['status' => [LoadStatus::COMPLETED]]),
            'pagination' => false
        ]);

        return $this->render('load-clearing', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHoldBilling($id)
    {
        $result = [
            'error' => 1,
            'message' => ''
        ];
        $load = Load::findOne($id);
        if ($load) {
            $newState = !$load->hold_billing;
            $load->hold_billing = $newState;
            $load->save();
            $result = [
                'error' => 0,
                'message' => Yii::t(
                    'app',
                    'Hold Billing, for load {load} TL, has been {state}',
                    ["load" => $id, "state" => $newState ? 'enabled' : 'disabled']
                ),
                'exec' => 'window.location.reload();'
            ];
        }

        return $this->replyJson($result);
    }
}