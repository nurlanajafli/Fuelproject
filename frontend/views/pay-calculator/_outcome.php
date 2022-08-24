<?php
/** @var \common\models\Payroll $payroll */
?>
<div class="col-4 cell">
    <div class="modal-border h-100">
        <span class="form-legend">
            <?php echo Yii::t('app', 'Earnings') ?>
        </span>
        <div class="row">
            <div class="col-sm-5">
                <?php echo $payroll->getAttributeLabel('dispatch_pay') ?>
            </div>
            <div class="col-sm-7 text-right">
                <?php echo Yii::$app->formatter->asDecimal($payroll->dispatch_pay) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <?php echo $payroll->getAttributeLabel('mileage_pay') ?>
            </div>
            <div class="col-sm-7 text-right">
                <?php echo Yii::$app->formatter->asDecimal($payroll->mileage_pay) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <?php echo $payroll->getAttributeLabel('totalwages') ?>
            </div>
            <div class="col-sm-7 text-right">
                <?php echo Yii::$app->formatter->asDecimal($payroll->totalwages) ?>
            </div>
        </div>
    </div>
</div>
<div class="col-4 cell">
    <div class="modal-border h-100">
        <span class="form-legend"><?php echo Yii::t('app', 'Adjustments') ?></span>
        <div class="row ">
            <div class="col-sm-5">
                <?php echo $payroll->getAttributeLabel('additions') ?>
            </div>
            <div class="col-sm-7 text-right">
                <?php echo Yii::$app->formatter->asDecimal($payroll->additions) ?>
            </div>
        </div>
        <div class="row ">
            <div class="col-sm-5">
                <?php echo $payroll->getAttributeLabel('deductions') ?>
            </div>
            <div class="col-sm-7 text-right">
                <?php echo Yii::$app->formatter->asDecimal(-$payroll->deductions) ?>
            </div>
        </div>
    </div>
</div>
<div class="col-4 cell">
    <div class="modal-border h-100">
        <div class="font-weight-bold text-center"><?php echo $payroll->getAttributeLabel('netamount') ?></div>
        <div class="text-center"><?php echo Yii::$app->formatter->asDecimal($payroll->netamount) ?></div>
    </div>
</div>
