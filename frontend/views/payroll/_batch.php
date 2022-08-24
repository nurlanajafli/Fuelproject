<!DOCTYPE html>
<head>
    <meta charset="utf-8">
</head>
<body>
<div class="container">
    <h3 class="h3 mb-0 text-gray-800"><?=Yii::t('app', 'Payroll summary') ?></h3>
    <p><?=date('m/d/Y')?></p>
    <br>
    <div class="row">
        <div class="col-lg w-100">
            <table class="w-100 border-0">
                <tr class="border-none">
                    <td class="border-none"><?=Yii::t('app', 'From') ?>: <?=Yii::$app->formatter->asDate($payrolls[0]->payrollBatch->period_start, Yii::$app->params['formats'][3])?></td>
                    <td class="border-none"><?=Yii::t('app', 'To') ?>: <?=Yii::$app->formatter->asDate($payrolls[0]->payrollBatch->period_end, Yii::$app->params['formats'][3])?></td>
                    <td class="border-none"><?=Yii::t('app', 'Check date') ?>: <?=Yii::$app->formatter->asDate($payrolls[0]->payrollBatch->check_date, Yii::$app->params['formats'][3])?></td>
                    <td class="border-none"><?=Yii::t('app', 'Batch No') ?>: <?=$payrolls[0]->payrollBatch->id?></td>
                    <td class="border-none"><?=Yii::t('app', 'Type') ?>: <?=$payrolls[0]->payrollBatch->type?></td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg w-100">
            <table class="w-100 no-border border-0">
                <tr class="bg-grey">
                    <td class="text-left"><?=Yii::t('app', 'Payroll for') ?></td>
                    <td class="text-left"><?=Yii::t('app', 'Pay To') ?></td>
                    <td class="text-right"><?=Yii::t('app', 'Gross') ?></td>
                    <td class="text-left"><?=Yii::t('app', 'Per Diem') ?></td>
                    <td class="text-left"><?=Yii::t('app', 'State') ?></td>
                    <td class="text-left"><?=Yii::t('app', 'Federal') ?></td>
                    <td class="text-left"><?=Yii::t('app', 'FICA') ?></td>
                    <td class="text-left"><?=Yii::t('app', 'Medicare') ?></td>
                    <td class="text-right"><?=Yii::t('app', 'Other') ?></td>
                    <td class="text-right"><?=Yii::t('app', 'Check Amt') ?></td>
                </tr>
                <?php foreach($batchArray as $arr) { ?>
                    <tr>
                        <td class="text-left"><?=$arr['payroll_for']?></td>
                        <td class="text-left"><?=$arr['pay_to']?></td>
                        <td class="text-right"><?=$arr['gross']?></td>
                        <td class="text-left"><?=$arr['per_diem']?></td>
                        <td class="text-left"><?=$arr['state']?></td>
                        <td class="text-left"><?=$arr['federal']?></td>
                        <td class="text-left"><?=$arr['fica']?></td>
                        <td class="text-left"><?=$arr['medicare']?></td>
                        <td class="text-right"><?=$arr['other']?></td>
                        <td class="text-right"><?=$arr['check_amt']?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
</body>