<?php

use common\enums\LoadStopType;
use common\enums\RateBy;
use common\helpers\Utils;

/**
 * @var \common\models\Company $company
 * @var \common\models\Load $load
 * @var \common\models\DispatchAssignment $da
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
      <h1>DISPATCH CONFIRMATION</h1>
    </div>
      <?php
      $da = $load->getDispatchAssignment()->one();
      ?>

    <table class="w-100">
      <tbody>
      <tr class="doc__info">
          <td class="border-0 w-50">
              <p>Unit: <span class="clr-blue"><?= $da->unit_id ?></span></p>
              <p>Driver: <span class="clr-blue"><?= $da->driver->getFullName() ?></span></p>
              <p>Truck: <span class="clr-blue"><?= $da->truck_id ?></span></p>
              <p>Trailer: <span class="clr-blue"><?= $da->trailer_id ?></span></p>
              <p>License: <span class="clr-blue"><?= $da->driver->driverCompliance->cdl_number . ', ' .$da->driver->driverCompliance->cdl_expires  ?></span></p>
          </td>
          <td class="border-0 w-50">
              <p>Pieces: <span class="clr-blue"><?= $load->commodity_pieces ?></span></p>
              <p>Seats: <span class="clr-blue"><?= '' ?></span></p>
              <p>Space: <span class="clr-blue"><?= $load->commodity_space ?></span></p>
              <p>Pallets: <span class="clr-blue"><?= '' ?></span></p>
          </td>
          <td class="border-0 w-50">
              <p>Miles: <span class="clr-blue"><?= $da->driver_loaded_miles ?></span></p>
              <p>Weight: <span class="clr-blue"><?= $load->commodity_weight ?></span></p>
              <p>Tarif: <span class="clr-blue"><?= '' ?></span></p>
              <p>As Weight: <span class="clr-blue"><?= '' ?></span></p>
          </td>
          <td class="border-0 w-50">
              <p>Seal: <span class="clr-blue"><?= $load->seal_no ?></span></p>
              <p>Temp: <span class="clr-blue"><?= '' ?></span></p>
              <p>Pit X: <span class="clr-blue"><?= '' ?></span></p>
              <p>Haz: <span class="clr-blue"><?php if ($load->commodity_commodity_id && $load->commodity_commodity_id != ''): ?>Yes<?php else: ?>No<?php endif; ?></span></p>
          </td>

      </tr>
      </tbody>
    </table>
    <div class="doc__table">

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
        <hr>
        <p class="clr-blue">RATE: $<?= $load->rate ?></p>

    </div>
    <div class="doc__footer">

      <div class="doc__footer-signature">
        <div></div>
        <p></p>
      </div>
    </div>
  </div>
</div>
</body>