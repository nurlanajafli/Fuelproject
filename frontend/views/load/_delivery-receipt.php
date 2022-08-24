<?php

use common\enums\DeliveryReceiptTypes;

/**
 * @var \common\models\Company $company
 * @var \common\models\Load $load
 * @var string $type
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
      <h1>Delivery Receipt</h1>
    </div>
    <table class="w-100">
      <tbody>
      <tr class="doc__info">
        <td class="border-0">
          <p>
            Deliver To: <br>
            <span class="clr-blue"><?= $load->billTo->name ?></span>
              <?php if ($load->billTo->address_1): ?>
                <br><span class="clr-blue"><?= $load->billTo->address_1 ?></span>
              <?php endif;
              if ($load->billTo->address_2): ?>
                <br><span class="clr-blue"><?= $load->billTo->address_2 ?></span>
              <?php endif; ?>
            <br> <span
                    class="clr-blue text-uppercase"><?= sprintf('%s, %s %s', $load->billTo->city, $load->billTo->state ? $load->billTo->state->state_code : '', $load->billTo->zip) ?></span>
          </p>
        </td>
        <td class="border-0">
          <p>Phone: <span class="clr-blue"></span></p>
          <p>Contact: <span class="clr-blue"><?= $company->main_phone ?></span></p>
          <p>Reference: <span class="clr-blue"><?= $load->customer_reference ?></span></p>
        </td>
        <td class="border-0">
          <p>Date: <span class="clr-blue"></span></p>
          <p>From: <span
                    class="clr-blue"><?= $formatter->asDate($load->received, Yii::$app->params['formats'][0]) ?></span>
          </p>
          <p>To: <span
                    class="clr-blue"><?= $formatter->asDate($load->release, Yii::$app->params['formats'][0]) ?></span>
          </p>
        </td>
      </tr>
      </tbody>
    </table>
    <table class="w-100">
      <tr>
        <td class="border-0">Pickup
          from: <?= $load->loadStops[0]->address . ", " . $load->loadStops[0]->city . " " . $load->loadStops[0]->zone ?></td>
        <td class="border-0">Reference: <?= $load->loadStops[1]->reference ?></td>
      </tr>
    </table>
    <hr class="border-y">
    <table class="w-100">
      <tbody>
      <tr class="doc__info">
        <td class="border-0">
          <p>
            Billing To: <br>
            <span class="clr-blue"><?= $load->billTo->name ?></span>
              <?php if ($load->billTo->address_1): ?>
                <br><span class="clr-blue"><?= $load->billTo->address_1 ?></span>
              <?php endif;
              if ($load->billTo->address_2): ?>
                <br><span class="clr-blue"><?= $load->billTo->address_2 ?></span>
              <?php endif; ?>
            <br> <span
                    class="clr-blue text-uppercase"><?= sprintf('%s, %s %s', $load->billTo->city, $load->billTo->state ? $load->billTo->state->state_code : '', $load->billTo->zip) ?></span>
          </p>
        </td>
        <td class="border-0">
          <p>Office: <span class="clr-blue"><?= $load->office->office ?></span></p>
          <p>Terms: <span class="clr-blue"> <?= $load->billTo->terms ?> </span></p>
          <p>Reference: <span class="clr-blue"><?= $load->customer_reference ?></span></p>
        </td>
      </tr>
      </tbody>
    </table>
    <br><br><br>
    <table class="w-100">
      <tr>
        <td class="border-0">
          <p><strong>Notes:</strong> <?= $load->notes ?></p>
        </td>
          <?php if ($type == DeliveryReceiptTypes::SHOW_REVENUE): ?>
            <td class="border-0 w-40 bill" style="width:40%">
              <p class="text-right mb-2 mt-2">Freight
                Charges: <?= Yii::$app->formatter->asDecimal($load->freight) ?></p>
              <p class="text-right mb-2 mt-2">Other
                charges: <?= Yii::$app->formatter->asDecimal($load->accessories) ?></p>
              <p class="text-right float-right mb-2 mt-2">=====================</p>
              <p class="text-right mb-2 mt-2">Total Charges: <?= Yii::$app->formatter->asDecimal($load->total) ?></p>
            </td>
          <?php endif; ?>
      </tr>
    </table>
    <div class="doc__footer">
      <div class="doc__footer-signature">
        <hr class="border-y">
        <table class="w-100">
          <tr>
            <td class="border-0">
              <p>Trailer: <?= $load->trailerType ? $load->trailerType->description : '' ?></p>
            </td>
            <td class="border-0">
              <p>Truck: </p>
            </td>
            <td class="border-0">
              <p>Time in: _________</p>
            </td>
            <td class="border-0">
              <p>Time out: _________</p>
            </td>
          </tr>
        </table>
        <table class="w-100">
          <tr>
            <td class="border-0">
              <p>Received In Good Order By: ________________________________</p>
            </td>
            <td class="border-0">
              <p>Date: ___________</p>
            </td>
            <td class="border-0">
              <p>Pieces: _____________</p>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
</body>