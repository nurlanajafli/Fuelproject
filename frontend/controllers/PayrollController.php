<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\BatchConvertFormat;
use common\enums\CronTask;
use common\models\base\PayrollBatch;
use common\models\Company;
use common\models\Cron;
use common\models\Payroll;
use common\widgets\DataTables\Grid;
use frontend\forms\payroll\BatchConvert;
use frontend\forms\payroll\BatchSummary;
use frontend\forms\payroll\Filter;
use frontend\forms\payroll\PrintSettlements;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\ResponseFormatterInterface;
use ZipArchive;
use yii2tech\spreadsheet\Spreadsheet;
use yii2tech\spreadsheet\SerialColumn;
use yii\data\ArrayDataProvider;

class PayrollController extends base\BaseController
{
    protected $hashKey = 'hlW-VV$wikTdQGsVgIkA03';

//    public function beforeAction($action)
//    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return parent::beforeAction($action);
//    }

    public function actions()
    {
        return [
            'print' => [
                'class' => FormProcessingAction::class,
                'formClass' => PrintSettlements::class,
                'before' => function ($actionParams) {
                    return [$actionParams['id']];
                },
                'init' => function (PrintSettlements $form, $array) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $filter = new Filter();
                    $filter->batchId = $array[0];

                    $action->viewParams = [
                        'filter' => $filter
                    ];
                },
                'save' => function (PrintSettlements $form, $array, string $btn) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $zip = new ZipArchive();
                    $tmpDir = sys_get_temp_dir();
                    $fileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('pp') . '.zip';
                    $prettyName = sprintf('Settlements %s.zip', Yii::$app->formatter->asDate('now', Yii::$app->params['formats'][7]));
                    $zip->open($fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
                    /** @var Payroll[] $payrolls */
                    $payrolls = Payroll::find()->
                    alias('t')->
                    andWhere(['t.id' => $form->ids])->
                    joinWith(['payrollBatch', 'office', 'payrollPays.dLoad.loadStops.state', 'payrollPays.dLoad.dispatchAssignment', 'payrollAdjustments'])->
                    all();
                    $count = count($payrolls);
                    $company = Company::find()->alias('t')->joinWith('state')->andWhere(['t.id' => Yii::$app->params['companyId']])->one();

                    $batchInfo = [];
                    $batchInfo['from'] = $payrolls[0]->payrollBatch->id;
                    $batchInfo['to'] = $payrolls[0]->payrollBatch->id;
                    $batchInfo['check_date'] = $payrolls[0]->payrollBatch->id;
                    $batchInfo['batch_id'] = $payrolls[0]->payrollBatch->id;
                    $batchData = [];

                    foreach ($payrolls as $k => $payroll) {
                        $batchData[$k]['payroll_for'] = $payroll->getPayrollFor();
                        $batchData[$k]['pay_to'] = $payroll->getPayableTo();
                        $batchData[$k]['dispatch_payment'] = Yii::$app->formatter->asDecimal($payroll->dispatch_pay);
                        $batchData[$k]['adjustment'] = Yii::$app->formatter->asDecimal($payroll->deductions);
                        $batchData[$k]['net_amt'] = Yii::$app->formatter->asDecimal($payroll->netamount);
                        $batchData[$k]['total_payment'] = Yii::$app->formatter->asDecimal($payroll->totalwages);

                        $reportFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('ppr') . '.pdf';
                        $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                            'destination' => Pdf::DEST_FILE,
                            'filename' => $reportFileName,
                            'content' => $this->renderPartial('_report', ['company' => $company, 'payroll' => $payroll, 'page' => $k + 1, 'pages' => $count]),
                        ]));
                        $pdf->render();
                        $zip->addFile($reportFileName, $payroll->payroll_batch_id . ' - ' . $payroll->getPayrollFor() . '.pdf');
                        Cron::create(CronTask::DELETE_FILE, ['filename' => $reportFileName], 5 * 60, 2, 3600);
                        $payroll->posted = true;
                        $payroll->save();
                    }

                    $exporter = new Spreadsheet([
                        'title' => 'Settlements',
                        'startRowIndex' => 6,
                        'dataProvider' => new ArrayDataProvider([
                            'allModels' => $batchData
                        ]),
                        'columns' => [
                            ['attribute' => 'payroll_for'],
                            ['attribute' => 'pay_to'],
                            ['attribute' => 'dispatch_payment'],
                            ['attribute' => 'adjustment'],
                            ['attribute' => 'net_amt'],
                            ['attribute' => 'total_payment'],
                        ],
                    ]);
                    $exporter->renderCell('A1', 'Settlements', [
                        'font' => [
                            'size' => '16px',
                        ],
                    ]);

                    $exporter->renderCell('A2', date('m/d/Y'));
                    $exporter->renderCell('A4', 'From: ');
                    $exporter->renderCell('B4', Yii::$app->formatter->asDate($batchInfo['from'], Yii::$app->params['formats'][3]));
                    $exporter->renderCell('C4', 'To: ');
                    $exporter->renderCell('D4', Yii::$app->formatter->asDate($batchInfo['to'], Yii::$app->params['formats'][3]));
                    $exporter->renderCell('E4', 'Check date: ');
                    $exporter->renderCell('F4', Yii::$app->formatter->asDate($batchInfo['check_date'], Yii::$app->params['formats'][3]));
                    $exporter->renderCell('G4', 'Batch No: ');
                    $exporter->renderCell('H4', $batchInfo['batch_id']);
                    $exporter->mergeCells('A1:J1');
                    $exporter->mergeCells('A2:J2');
                    $exporter->mergeCells('A3:J3');
                    $exporter->mergeCells('A5:J5');

                    $xlsxFileName = $tmpDir . DIRECTORY_SEPARATOR . uniqid('pp') .'Settlements_'.$batchInfo['batch_id'].'.xls';
                    $xlsPrettyName = sprintf('Settlements %s.xls', Yii::$app->formatter->asDate('now', Yii::$app->params['formats'][7]));
                    $exporter->save($xlsxFileName);
                    $zip->addFile($xlsxFileName, $xlsPrettyName);
                    Cron::create(CronTask::DELETE_FILE, ['filename' => $xlsxFileName], 5 * 60, 2, 3600);

                    $zip->close();
                    Cron::create(CronTask::DELETE_FILE, ['filename' => $fileName], 5 * 60, 2, 3600);
                    return $action->saveResp(true, ['download', 'fileName' => Yii::$app->security->hashData(basename($fileName) . ",$prettyName", $this->hashKey)]);
                }
            ],
            'batch-summary' => [
                'class' => FormProcessingAction::class,
                'formClass' => BatchSummary::class,
                'view' => 'batch-summary',
                'before' => function ($actionParams) {
                    return [$actionParams['id']];
                },
                'init' => function (BatchSummary $form, $array) {
                    $action = $this->action;
                    $filter = new Filter();
                    if(!$array[0] || $array[0] == 0) {
                       $lastPayrollBatch = PayrollBatch::find()->max('id');
                       if($lastPayrollBatch) {
                           $array[0] = $lastPayrollBatch;
                       }
                    }
                    $filter->batchId = $array[0];
                    $action->viewParams = [
                        'filter' => $filter,
                    ];
                },
            ],
        ];
    }

    public function actionBatchConvert($id) {
        $form = new BatchConvert();
        $form->batch_id = $id;
        $form->convertFormat = BatchConvertFormat::XLSX;
        if (Yii::$app->request->isPost) {
            $form->load(Yii::$app->request->post());
            if ($form->validate()) {
                $payrolls = Payroll::find()->alias('p')->joinWith(['driver d', 'office o', 'payrollBatch pb'])
                    ->andWhere(['p.payroll_batch_id' => $form->batch_id])->orderBy(['p.id' => SORT_ASC])->all();
                $batchArray = [];
                foreach ($payrolls as $pk => $pv) {
                    $batchArray[$pk]['payroll_for'] = $pv->getPayrollFor();
                    $batchArray[$pk]['pay_to'] = $pv->getPayableTo();
                    $batchArray[$pk]['gross'] = Yii::$app->formatter->asDecimal($pv->getNetAmount());
                    $batchArray[$pk]['per_diem'] = '0.00';
                    $batchArray[$pk]['state'] = '0.00';
                    $batchArray[$pk]['federal'] = '0.00';
                    $batchArray[$pk]['fica'] = '0.00';
                    $batchArray[$pk]['medicare'] = '0.00';
                    $batchArray[$pk]['other'] = $pv->getDeductions();
                    $batchArray[$pk]['check_amt'] = Yii::$app->formatter->asDecimal($pv->getTotalWages());
                }
                if ($form->convertFormat == BatchConvertFormat::XLSX) {
                    // Export to XLSX file & download
                    $exporter = new Spreadsheet([
                        'title' => 'Payroll Batch Summary',
                        'startRowIndex' => 6,
                        'dataProvider' => new ArrayDataProvider([
                            'allModels' => $batchArray,
                        ]),
                        'columns' => [
                            ['attribute' => 'payroll_for'],
                            ['attribute' => 'pay_to'],
                            ['attribute' => 'gross'],
                            ['attribute' => 'per_diem'],
                            ['attribute' => 'state'],
                            ['attribute' => 'federal'],
                            ['attribute' => 'fica'],
                            ['attribute' => 'medicare'],
                            ['attribute' => 'other'],
                            ['attribute' => 'check_amt'],
                        ],
                    ]);
                    $exporter->renderCell('A1', 'Payroll summary', [
                        'font' => [
                            'size' => '16px',
                            'color' => [
                                'rgb' => '#FF0000',
                            ],
                        ],
                    ]);
                    $exporter->renderCell('A2', date('m/d/Y'));
                    $exporter->renderCell('A4', 'From: ');
                    $exporter->renderCell('B4', Yii::$app->formatter->asDate($payrolls[0]->payrollBatch->period_start, Yii::$app->params['formats'][3]));
                    $exporter->renderCell('C4', 'To: ');
                    $exporter->renderCell('D4', Yii::$app->formatter->asDate($payrolls[0]->payrollBatch->period_end, Yii::$app->params['formats'][3]));
                    $exporter->renderCell('E4', 'Check date: ');
                    $exporter->renderCell('F4', Yii::$app->formatter->asDate($payrolls[0]->payrollBatch->check_date, Yii::$app->params['formats'][3]));
                    $exporter->renderCell('G4', 'Batch No: ');
                    $exporter->renderCell('H4', $payrolls[0]->payrollBatch->id);
                    $exporter->renderCell('I4', 'Type: ');
                    $exporter->renderCell('J4', $payrolls[0]->payrollBatch->type);
                    $exporter->mergeCells('A1:J1');
                    $exporter->mergeCells('A2:J2');
                    $exporter->mergeCells('A3:J3');
                    $exporter->mergeCells('A5:J5');
                    $exporter->send('Payroll_Batch_No_' . $form->batch_id . '.xls');

                } elseif ($form->convertFormat == BatchConvertFormat::PDF) {
                    $batchFileName = 'Payroll_Batch_No_' . $form->batch_id . '.pdf';
                    $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                        'destination' => Pdf::DEST_DOWNLOAD,
                        'filename' => $batchFileName,
                        'content' => $this->renderPartial('_batch',[
                            'payrolls' => $payrolls,
                            'batchArray' => $batchArray,
                        ])
                    ]));
                    return $pdf->render();
                }
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->renderAjax('batch-convert',[
            'form' => $form
        ]);
    }

    public function actionIndexBatch() {
        $query = Payroll::find()->alias('t')->joinWith(['driver', 'payrollBatch pb']);

        $filter = new Filter();
        $filter->load(Yii::$app->request->isPost ? Yii::$app->request->post() : Yii::$app->request->get());
        $filter->apply($query);

        $grid = new Grid([
            'dataProvider' => new ActiveDataProvider([
                'query' => $query,
                'pagination' => false
            ]),
            'columns' => [
                'id',
                'null|method=getPayrollFor',
                'null|method=getPayableTo',
                'null|method=getTotalWages|decimal',
                'null|decimal',
                'null|decimal',
                'null|decimal',
                'null|decimal',
                'null|decimal',
                'null|method=getDeductions',
                'null|method=getPeriodStart',
                'null|method=getPeriodEnd',
                'null|method=getCheckDate',
                'null|method=getBatchNo',
                'null|method=getBatchType',
                'null|method=getNetAmount|decimal',
            ]
        ]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->replyJson($grid->getData());
    }

    public function actionIndex()
    {
        $query = Payroll::find()->alias('t')->joinWith(['driver', 'payrollBatch pb']);

        $filter = new Filter();
        $filter->load(Yii::$app->request->isPost ? Yii::$app->request->post() : Yii::$app->request->get());
        $filter->apply($query);

        $grid = new Grid([
            'dataProvider' => new ActiveDataProvider([
                'query' => $query,
                'pagination' => false
            ]),
            'columns' => [
                'id',
                'null|method=getPayrollFor',
                'office_id'
            ]
        ]);
        return $this->replyJson($grid->getData());
    }

    public function actionDownload($fileName)
    {
        if ($fileName = Yii::$app->security->validateData($fileName, $this->hashKey)) {
            list($fileName, $attachmentName) = explode(',', $fileName);
            return Yii::$app->response->sendFile(sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($fileName), $attachmentName);
        }

        throw new BadRequestHttpException();
    }
}