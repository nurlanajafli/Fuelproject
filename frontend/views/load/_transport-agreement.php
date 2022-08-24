<?php

use common\enums\LoadStopType;
use common\helpers\Utils;

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
      <h1>TRANSPORT AGREEMENT</h1>
    </div>
    <table class="w-100">
      <tbody>
      <tr class="doc__info">
        <td class="border-0 w-50">
          <p>
            To: <span class="clr-blue"><?= $load->billTo->name ?></span>
              <?php if ($load->billTo->address_1): ?>
                <br><span class="clr-blue"><?= $load->billTo->address_1 ?></span>
              <?php endif;
              if ($load->billTo->address_2): ?>
                <br><span class="clr-blue"><?= $load->billTo->address_2 ?></span>
              <?php endif; ?>
            <br>
            <br> <span
                    class="clr-blue text-uppercase"><?= sprintf('%s, %s %s', $load->billTo->city, $load->billTo->state ? $load->billTo->state->state_code : '', $load->billTo->zip) ?></span>
          </p>
        </td>
        <td class="border-0 w-50">
          <p>Attn: <span>&nbsp;</span></p>
          <p>Phone: <span class="clr-blue"><?= $load->billTo->main_phone ?></span></p>
          <p>Fax: <span class="clr-blue"><?= $load->billTo->main_fax ?></span></p>
          <p>Ref: <span class="clr-blue"><?= $load->customer_reference ?></span></p>
        </td>
      </tr>
      </tbody>
    </table>
    <div class="doc__table">
      <div class="doc__table-header border-y">
        <p><?= sprintf('%s agrees to pay %s for transport services as follows:', $load->billTo->name, $company->name) ?></p>
      </div>
      <table>
        <thead class="doc__table-head">
        <tr>
          <th>Stop</th>
          <th></th>
          <th>From</th>
          <th>To</th>
          <th>Company<br>Address</th>
          <th>City<br>Phone</th>
          <th>St<br>Zip</th>
          <th>Ref<br>Contact</th>
          <th>Appt<br>Appt Ref</th>
        </tr>
        </thead>
        <tbody class="doc__table-body clr-blue">
        <?php
        $loadStops = $load->getLoadStopsOrdered();
        foreach ($loadStops as $loadStop):
            $t = '';
            switch ($loadStop->stop_type) {
                case LoadStopType::SHIPPER:
                    $t = 'P/U';
                    break;
                case LoadStopType::CONSIGNEE:
                    $t = 'Deliver';
                    break;
            }
            ?>
          <tr>
            <td><?= $loadStop->stop_number ?></td>
            <td><?= $t ?></td>
            <td><?= $formatter->asDate($loadStop->available_from, $minDateFormat) ?></td>
            <td><?= $formatter->asDate($loadStop->available_thru, $minDateFormat) ?></td>
            <td class="text-uppercase"><?= $loadStop->getCompanyName() ?><br><?= $loadStop->getAddress() ?></td>
            <td><?= $loadStop->city ?><br><?= $loadStop->phone ?></td>
            <td class="text-uppercase"><?= $loadStop->getStateCode() . $loadStop->getZip() ?></td>
            <td><?= $loadStop->reference ?><br><?= $loadStop->contact ?></td>
            <td><?= Utils::yn($loadStop->appt_required) ?><br><?= $loadStop->appt_reference ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <div class="doc__table-footer border-y">
        <div class="doc__table-footer-inner clearfix">
          <div class="doc__table-footer-item">
            <p>Freight Charges</p>
            <p class="clr-blue"><?= $formatter->asDecimal($load->freight) ?></p>
          </div>
          <div class="doc__table-footer-item">
            <p>Accessorials</p>
            <p class="clr-blue"><?= $formatter->asDecimal($load->accessories) ?></p>
          </div>
          <div class="doc__table-footer-item">
            <p>Total Charges</p>
            <p class="clr-blue"><?= $formatter->asDecimal($load->total) ?></p>
          </div>
        </div>
      </div>
    </div>
    <div class="doc__footer">
        <?php
        $country = $state = '?';
        if ($company->state) {
            $country = ($company->state->country_code == 'US') ? 'USA' : $company->state->country;
            $state = $company->state->state;
        }
        ?>
      <p><?= $load->billTo->name ?> agrees to pay amounts billed on this agreement within 30 days of the date
        invoiced. Amounts not paid within terms will be charged interest in the amount of 0 percent per annum.
        This agreement shall be governed by the laws of the state of <?= $state ?> and all parties to this
        agreement waive objections on the grounds of improper jurisdiction or venue of any action brought in the
        County of <?= $country ?>, in the state of <?= $state ?>.</p>
      <p>In the event a suit is commenced to enforce the payment of this agreed amount, debtor agrees to pay all
        costs, expenses and actual attorney fees incurred by creditor in connection with same.</p>
      <p class="clr-blue"><?= $company->name ?></p>
      <div class="doc__footer-signature">
        <div></div>
        <p></p>
      </div>
    </div>
  </div>
</div>
</body>