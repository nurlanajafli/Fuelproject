<?php
/**
 * @var \common\models\Company $company
 * @var \common\models\Payroll $payroll
 * @var int $page
 * @var int $pages
 */

use common\enums\PayrollBatchType;

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
</head>
<body>
<div class="container">
    <div class="doc">
        <table class="w-100">
            <tbody>
            <tr class="clearfix">
                <td class="w-82 border-0">
                    <p><?= $company->name ?></p>
                    <?php if ($company->address_1): ?>
                        <p><?= $company->address_1 ?></p>
                    <?php endif;
                    if ($company->address_2): ?>
                        <p><?= $company->address_2 ?></p>
                    <?php endif; ?>
                    <p class="text-uppercase"><?= sprintf('%s, %s %s', $company->city, $company->state ? $company->state->state_code : '', $company->zip) ?></p>
                    <p><?= ($company->main_phone ? "P: {$company->main_phone} " : '') . ($company->main_fax ? "F: {$company->main_fax}" : '') ?></p>
                </td>
                <td class="w-18 border-0 va-bottom text-right">
                    <p class="text-right"><?= $payroll->office ? $payroll->office->office : '' ?></p>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="doc__table mb-10">
            <div class="doc__table-header border clearfix">
                <p class="text-uppercase text-right pull-left w-60">DRIVER PAY SETTLEMENT</p>
                <p class="text-right pull-left w-40"><?= sprintf('Page %d of %d', $page, $pages) ?></p>
            </div>
            <table>
                <tbody>
                <tr>
                    <td class="border-0 w-50">
                        <p>Name: <span><?= $payroll->getPayrollFor() ?></span></p>
                        <p>Pay To: <span><?= $payroll->getPayableTo() ?></span></p>
                    </td>
                    <td class="border-0 w-25">
                        <p>Period Start:
                            <span><?= Yii::$app->formatter->asDate($payroll->payrollBatch->period_start, Yii::$app->params['formats'][1]) ?></span>
                        </p>
                        <p>Period End:
                            <span><?= Yii::$app->formatter->asDate($payroll->payrollBatch->period_end, Yii::$app->params['formats'][1]) ?></span>
                        </p>
                    </td>
                    <td class="border-0 w-25">
                        <p>Check Date:
                            <span><?= Yii::$app->formatter->asDate($payroll->payrollBatch->check_date, Yii::$app->params['formats'][1]) ?></span>
                        </p>
                        <p>Batch: <span><?= $payroll->payrollBatch->id ?></span></p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="doc__table mb-50">
            <div class="doc__table-header border">
                <p class="text-uppercase text-center">SUMMARY</p>
            </div>
            <table>
                <tbody>
                <tr>
                    <td class="border-0 w-50">
                        <p>Dispatch Pay: <span><?= Yii::$app->formatter->asDecimal($payroll->dispatch_pay) ?></span></p>
                        <p>Total Pay: <span><?= Yii::$app->formatter->asDecimal($payroll->totalwages) ?></span></p>
                        <?php if ($payroll->codriver_pay > 0): ?>
                            <p>-CoDriver Pay:
                                <span><?= Yii::$app->formatter->asDecimal(-$payroll->codriver_pay) ?></span></p>
                        <?php endif; ?>
                    </td>
                    <td class="border-0 w-50">
                        <p>Gross: <span><?= Yii::$app->formatter->asDecimal($payroll->totalwages) ?></span></p>
                        <p>Adjustments: <span><?= Yii::$app->formatter->asDecimal($payroll->deductions) ?></span></p>
                        <p>Net Amount: <span><?= Yii::$app->formatter->asDecimal($payroll->netamount) ?></span></p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="doc__table mb-10">
            <div class="doc__table-header border">
                <p class="text-uppercase text-center">DISPATCH PAY</p>
            </div>
            <table>
                <thead class="doc__table-head">
                <?php if ($payroll->payrollBatch->type == PayrollBatchType::D_DRIVER): ?>
                    <tr class="va-bottom">
                        <th>Load</th>
                        <th>Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Pay Code</th>
                        <th>Pay Type</th>
                        <th class="text-right">Loaded<br>Rate</th>
                        <th class="text-right">Empty<br>Rate</th>
                        <th class="text-right">Load<br>Revenue</th>
                        <th class="text-right">Total<br>Pay</th>
                        <th>Percent</th>
                        <th class="text-right">Pay<br>Amount</th>
                    </tr>
                <?php endif; ?>
                </thead>
                <tbody class="doc__table-body">
                <?php $freightSum = 0;
                if ($payroll->payrollBatch->type == PayrollBatchType::D_DRIVER):
                    foreach ($payroll->payrollPays as $payrollPay):
                        $load = $payrollPay->dLoad;
                        $from = $to = '';
                        if ($load->loadStops) {
                            $from = $load->loadStops[0]->state ?
                                sprintf('%s, %s', $load->loadStops[0]->city, $load->loadStops[0]->state->state_code) :
                                $load->loadStops[0]->city;
                            $i = count($load->loadStops) - 1;
                            $to = $load->loadStops[$i]->state ?
                                sprintf('%s, %s', $load->loadStops[$i]->city, $load->loadStops[$i]->state->state_code) :
                                $load->loadStops[$i]->city;
                        }
                        $freightSum += $load->freight;
                        ?>
                    <tr>
                        <td><?= $load->id ?></td>
                        <td><?= Yii::$app->formatter->asDate($load->arrived_date, Yii::$app->params['formats'][2]) ?></td>
                        <td><?= $from ?></td>
                        <td><?= $to ?></td>
                        <td><?= $load->dispatchAssignment->pay_code ?></td>
                        <td><?= $load->dispatchAssignment->driver_pay_type ?></td>
                        <td class="text-right"><?= Yii::$app->formatter->asDecimal($load->dispatchAssignment->driver_loaded_rate) ?></td>
                        <td class="text-right"><?= Yii::$app->formatter->asDecimal($load->dispatchAssignment->driver_empty_rate) ?></td>
                        <td class="text-right"><?= Yii::$app->formatter->asDecimal($load->freight) ?></td>
                        <td class="text-right"><?= Yii::$app->formatter->asDecimal($load->dispatchAssignment->driver_total_pay) ?></td>
                        <?php
                        if($load->dispatchAssignment->driver->co_driver_id == '' || $load->dispatchAssignment->driver->co_driver_id == 0
                            || is_null($load->dispatchAssignment->driver->co_driver_id)) {
                        ?>
                            <td>1.00</td>
                            <td><?= Yii::$app->formatter->asDecimal($load->dispatchAssignment->driver_total_pay) ?></td>
                        <?php } else { ?>
                            <?php if($payroll->driver_id == $load->dispatchAssignment->driver->co_driver_id) { ?>
                            <td><?= Yii::$app->formatter->asDecimal($load->dispatchAssignment->driver->co_driver_earning_percent) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal($load->dispatchAssignment->driver_total_pay*$load->dispatchAssignment->driver->co_driver_earning_percent) ?></td>
                        <?php } else { ?>
                            <td><?= Yii::$app->formatter->asDecimal(1-$load->dispatchAssignment->driver->co_driver_earning_percent) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal($load->dispatchAssignment->driver_total_pay*(1-$load->dispatchAssignment->driver->co_driver_earning_percent)) ?></td>
                        <?php } ?>
                        <?php } ?>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td class="text-right" colspan="6">Totals</td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($freightSum) ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal($payroll->dispatch_pay) ?></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="clearfix">
            <div class="w-73 py-0 mr-2 pull-left">
                <div class="doc__table">
                    <div class="doc__table-header border">
                        <p class="text-uppercase text-center">PAY ADJUSTMENTS</p>
                    </div>
                    <table>
                        <thead>
                        <tr>
                            <?php if ($payroll->payrollBatch->type == PayrollBatchType::D_DRIVER): ?>
                                <th>Type</th>
                                <th class="text-right">Amt</th>
                                <th>Truck No</th>
                                <th>Description</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($payroll->payrollAdjustments as $payrollAdjustment): if ($payroll->payrollBatch->type == PayrollBatchType::D_DRIVER): ?>
                            <tr>
                                <td><?= $payrollAdjustment->d_payroll_adjustment_code ?></td>
                                <td class="text-right"><?= Yii::$app->formatter->asDecimal(-$payrollAdjustment->d_amount) ?></td>
                                <td></td>
                                <td><?= $payrollAdjustment->d_description ?></td>
                            </tr>
                        <?php endif; endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td class="text-right">Total Adjustments</td>
                            <td class="text-right"><?= Yii::$app->formatter->asDecimal(-$payroll->deductions) ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="w-25 p-0 pull-left">
                <div class="doc__table">
                    <div class="doc__table-header border">
                        <p class="text-uppercase text-center">BALANCES</p>
                    </div>
                    <table>
                        <thead>
                        <tr>
                            <td>Description</td>
                            <td class="text-right">Balance</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Advances Given</td>
                            <td class="text-right"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>