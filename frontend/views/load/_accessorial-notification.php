<?php

use common\helpers\Utils;
use common\enums\LoadStopType;

/**
 * @var \common\models\Company $company
 * @var \common\models\Load $load
 */

$formatter = Yii::$app->formatter;
$minDateFormat = Yii::$app->params['formats'][2];
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
                    <p><?= ($company->mc_id ? "MC: {$company->mc_id} " : '') . ($company->main_phone ? "P: {$company->main_phone} " : '') . ($company->main_fax ? "F: {$company->main_fax}" : '') ?></p>
                </td>
                <td class="w-18 border-0 lnum">
                    <div class="load-number lnumber">
                        LOAD NUMBER<br>
                        <span class="clr-blue"><?= $load->id ?></span>
                    </div>
                    <p class="text-center"><?= $formatter->asDate('now', Yii::$app->params['formats'][0]) ?></p>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="doc__title border-y">
            <h1>Accessorial Notification</h1>
        </div>
        <div class="an_top">
            <p>Customer: <span class="clr-blue"><?= $load->billTo->name?></span></p>
            <p>Fax no: <span class="clr-blue"><?= $company->main_fax  ?></span></p>
            <p>Attention: </p>
            <p class="big_text clr-blue text-center">
                <?= $load->notes ?>
            </p>
        </div><br>
        <table class="w-100">
            <tbody>
            <tr class="doc__info">
                <td class="border-0">
                    <p>Your Ref No: <span class="clr-blue"><?= $load->customer_reference ?></span></p>
                    <p>Our Load No: <span class="clr-blue"><?= $load->id ?></span></p>
                </td>
                <td class="border-0">
                    <p>Ship From: <span class="clr-blue"><?= $formatter->asDate($load->received, Yii::$app->params['formats'][0]) ?></span></p>
                    <p>Ship To: <span class="clr-blue"><?= $formatter->asDate($load->release, Yii::$app->params['formats'][0]) ?></span></p>
                </td>
            </tr>
            </tbody>
        </table>
        <hr class="border-y">
        <table class="w-100">
            <tr>
                <td class="border-0">Description </td>
                <td class="border-0">Units</td>
                <td class="border-0">Rate </td>
                <td class="border-0">Total</td>
                <td class="border-0">Reference</td>
            </tr>
            <?php if(isset($load->loadAccessories) && sizeof($load->loadAccessories)>0): ?>
                <?php foreach($load->loadAccessories as $ak=>$av) { ?>
                    <tr>
                        <td class="border-0 clr-blue"><?= $av->accessorial->accessorialRating->description?></td>
                        <td class="border-0 clr-blue"><?= $av->units?></td>
                        <td class="border-0 clr-blue"><?= $av->rate_each?> </td>
                        <td class="border-0 clr-blue"><?= $av->amount?></td>
                        <td class="border-0"><?= $av->reference?></td>
                    </tr>
                <?php } ?>
            <?php endif; ?>
        </table>
        <hr class="border-y">
        <br><br>

        <div class="doc__footer">
            <div class="doc__footer-signature">
                <table class="w-100">
                    <tr>
                        <td class="border-0">
                            <p>______________________________________<br>
                                Printed Name
                            </p>
                        </td>
                        <td class="border-0">
                            <p>_____________________<br>Signature</p>
                        </td>
                        <td class="border-0">
                            <p>______________________<br>Date</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
</body>