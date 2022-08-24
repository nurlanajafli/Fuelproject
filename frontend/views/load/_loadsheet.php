<?php

use common\enums\LoadsheetType;
use common\enums\RateBy;

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
  <div class="doc m-m">
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
      <h1>Load information</h1>
    </div>
    <table class="w-100 valign-top">
      <tbody>
      <tr class="doc__info">
        <td class="w-10 border-0">
          &nbsp;
        </td>
        <td class="border-0 w-40 v-top">
          <table class="w-100 border-0">
            <tr class="border-none">
              <td class="border-none">Customer:</td>
              <td class="border-none">
                <span class="clr-blue"><?= $load->billTo->name ?></span>
                  <?php if ($load->billTo->address_1): ?>
                    <br><span class="clr-blue"><?= $load->billTo->address_1 ?></span>
                  <?php endif;
                  if ($load->billTo->address_2): ?>
                    <br><span class="clr-blue"><?= $load->billTo->address_2 ?></span>
                  <?php endif; ?><br>
                <span class="clr-blue text-uppercase">
                                        <?= sprintf('%s, %s %s', $load->billTo->city, $load->billTo->state ? $load->billTo->state->state_code : '', $load->billTo->zip) ?>
                                    </span>
              </td>
            </tr>
            <tr class="border-none">
              <td class="border-none"><p>Ref No:</p></td>
              <td class="border-none">
                <p class="clr-blue"><?= $load->customer_reference ?></p>
              </td>
            </tr>
          </table>
        </td>
        <td class="w-10 border-0">
          &nbsp;
        </td>
        <td class="border-0 w-40">
          <table class="w-100 border-0">
            <tr class="border-none">
              <td class="border-none">
                <p>Phone:<br> <span><?= $load->billTo->main_phone ?></span></p>
                <p>Fax:<br> <span><?= $load->billTo->main_fax ?></span></p>
                <p>Disp contact:<br> <span><?= $load->billTo->disp_contact ?></span></p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      </tbody>
    </table>
    <hr class="border-y">
    <table class="w-100 m-m">
      <tr>
        <td class="w-20 border-0">&nbsp;</td>
        <td class="border-0 w-20 text-center">Status: <span class="clr-blue"><?= $load->status ?></span></td>
        <td class="border-0 w-20 text-center">User: <span
                  class="clr-blue"><?= $load->bookedBy->last_name . " " . $load->bookedBy->first_name ?></span></td>
        <td class="border-0 w-20 text-center">Job No: <span class="clr-blue">&nbsp;</span></td>
        <td class="w-20 border-0">&nbsp;</td>
      </tr>
    </table>
    <hr class="border-y">
    <table class="w-100 no-border border-0">
      <tr>
        <td class="border-0 text-center">Received</td>
        <td class="border-0 text-center">Release</td>
        <td class="border-0 text-center">Equip</td>
        <td class="border-0 text-center">Office</td>
        <td class="border-0 text-center">Temp</td>
        <td class="border-0 text-center">Pallets</td>
        <td class="border-0 text-center">Miles</td>
        <td class="border-0 text-center">Seal</td>
        <td class="border-0 text-center">Load Type</td>
        <td class="border-0 text-center">Haz</td>
        <td class="border-0 text-center">Pit X</td>
      </tr>
      <tr>
        <td class="border-0 text-center"><?= $formatter->asDate($load->received, Yii::$app->params['formats'][0]) ?></td>
        <td class="border-0 text-center"><?= $load->release ?></td>
        <td class="border-0 text-center">&nbsp;</td>
        <td class="border-0 text-center"><?= $load->office->office ?></td>
        <td class="border-0 text-center">&nbsp;</td>
        <td class="border-0 text-center">&nbsp;</td>
        <td class="border-0 text-center"><?= $load->rate_by == RateBy::MILES ? $load->rate : ''; ?></td>
        <td class="border-0 text-center"><?= $load->seal_no ?></td>
        <td class="border-0 text-center"><?= $load->type->description ?></td>
        <td class="border-0 text-center"><?php if ($load->commodity_commodity_id && $load->commodity_commodity_id != ''): ?>Yes<?php else: ?>No<?php endif; ?></td>
        <td class="border-0 text-center">No</td>
      </tr>
    </table>

    <p class="clr-blue"><?php echo $load->notes; ?></p>
      <?php if ($revenue != LoadsheetType::HIDE_ALL_REVENUE) { ?>
        <hr class="border-y">
        <table class="w-100 no-border border-0">
          <tr>
            <td class="border-0 text-center">Frt<br></br>Matrix</td>
            <td class="border-0 text-center">Acc<br>Matrix</td>
            <td class="border-0 text-center">Com<br>Matrix</td>
            <td class="border-0 text-center">Rate Type</td>
            <td class="border-0 text-center">Rate</td>
            <td class="border-0 text-center">Units</td>
            <td class="border-0 text-center">Frt Rev</td>
            <td class="border-0 text-center">Acc Rev</td>
            <td class="border-0 text-center">Total Rev</td>
            <td class="border-0 text-center">Inv No</td>
            <td class="border-0 text-center">Inv Date</td>
          </tr>
          <tr>
            <td class="border-0 text-center"><span class="clr-blue">&nbsp;</span></td>
            <td class="border-0 text-center"><span class="clr-blue">&nbsp;</span></td>
            <td class="border-0 text-center"><span class="clr-blue">&nbsp;</span></td>
            <td class="border-0 text-center"><span class="clr-blue"><?= $load->rate_by ?></span></td>
            <td class="border-0 text-center"><span class="clr-blue"><?= $load->rate ?></span></td>
            <td class="border-0 text-center"><span class="clr-blue"><?= $load->units ?></span></td>
            <td class="border-0 text-center"><span
                      class="clr-blue"><?= Yii::$app->formatter->asDecimal($load->freight) ?></span></td>
            <td class="border-0 text-center"><span
                      class="clr-blue"><?= Yii::$app->formatter->asDecimal($load->accessories) ?></span></td>
            <td class="border-0 text-center"><span
                      class="clr-blue"><?= Yii::$app->formatter->asDecimal($load->total) ?></span></td>
            <td class="border-0 text-center"><span class="clr-blue">&nbsp;</span></td>
            <td class="border-0 text-center"><span class="clr-blue"><?= $load->invoice_date ?></span></td>
          </tr>
        </table>
      <?php } ?>


    <table class="w-100 no-border border-0">
      <tr class="bg-grey">
        <td class="border-0 text-left">Stop</td>
        <td class="border-0 text-left">&nbsp;</td>
        <td class="border-0 text-left">From</td>
        <td class="border-0 text-left">To</td>
        <td class="border-0 text-left">Company</br>Address</td>
        <td class="border-0 text-left">City<br>Phone</td>
        <td class="border-0 text-right">St<br>Zip</td>
        <td class="border-0 text-left">Ref<br>Contact</td>
        <td class="border-0 text-right">Appt<br>Appt Ref</td>
      </tr>
        <?php foreach ($load->loadStops as $lsk => $lsv) { ?>
          <tr>
            <td class="border-0 text-left"><span class="clr-blue"><?= $lsv->stop_type ?></span></td>
            <td class="border-0 text-left"><span class="clr-blue"><?= $lsv->stop_number ?></span></td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= $formatter->asDate($lsv->available_from, Yii::$app->params['formats'][0]) ?><br><?= $lsv->time_from ?></span>
            </td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= $formatter->asDate($lsv->available_thru, Yii::$app->params['formats'][0]) ?><br><?= $lsv->time_to ?></span>
            </td>
            <td class="border-0 text-left"><span class="clr-blue"><?= $lsv->company_id ?> - get<br><?= $lsv->address ?></span>
            </td>
            <td class="border-0 text-left"><span class="clr-blue"><?= $lsv->city ?><br><?= $lsv->phone ?></span></td>
            <td class="border-0 text-right"><span class="clr-blue"><?= $lsv->zone ?><br><?= $lsv->zip ?></span></td>
            <td class="border-0 text-left"><span class="clr-blue"><?= $lsv->reference ?><br><?= $lsv->contact ?></span>
            </td>
            <td class="border-0 text-right"><span
                      class="clr-blue"><?= $lsv->appt_required ?><br><?= $lsv->appt_reference ?></span></td>
          </tr>
            <?php if ($stopNotes == LoadsheetType::SHOW_STOP_NOTES): ?>
            <tr>
              <td class="border-0 ">&nbsp;</td>
              <td class="border-0 ">&nbsp;</td>
              <td colspan="7" class="border-0 ">
                <p><span class="clr-blue">Stop note</span></p>
              </td>
            </tr>
            <?php endif; ?>
        <?php } ?>

        <?php if ($directions == LoadsheetType::SHOW_DIRECTIONS): ?>
          <tr>
            <td class="border-0 ">&nbsp;</td>
            <td class="border-0 ">&nbsp;</td>
            <td colspan="7" class="border-0 ">
              <p><span class="clr-blue">location test directions</span></p>
            </td>
          </tr>
        <?php endif; ?>
    </table>
    <table class="w-100 no-border border-0">
      <tr class="bg-grey">
        <td class="border-0 text-left">Commodity</td>
        <td class="border-0 text-left">Description Reference</td>
        <td class="border-0 text-left">&nbsp;</td>
        <td class="border-0 text-left">&nbsp;</td>
        <td class="border-0 text-left">Pieces</td>
        <td class="border-0 text-left">Weight</td>
        <td class="border-0 text-left">&nbsp;</td>
      </tr>
      <tr>
        <td class="border-0 text-left"><span
                  class="clr-blue"><?= $load->commodityCommodity ? $load->commodityCommodity->hazmat_code : '' ?></span>
        </td>
        <td class="border-0 text-left"><span class="clr-blue"><?= $load->commodity_reference ?></span></td>
        <td class="border-0 text-left"><span class="clr-blue">&nbsp;</span></td>
        <td class="border-0 text-left"><span class="clr-blue">&nbsp;</span></td>
        <td class="border-0 text-left"><span class="clr-blue"><?= $load->commodity_pieces ?></span></td>
        <td class="border-0 text-left"><span class="clr-blue"><?= $load->commodity_weight ?></span></td>
        <td class="border-0 text-left"><span class="clr-blue">&nbsp;</span></td>
      </tr>
    </table>
      <?php if ($revenue == LoadsheetType::SHOW_ALL_REVENUE && isset($load->loadAccessories) && sizeof($load->loadAccessories) > 0): ?>
        <table class="w-100 no-border border-0">
          <tr class="bg-grey">
            <td class="border-0 text-left">Accessorial</td>
            <td class="border-0 text-left">Reference</td>
            <td class="border-0 text-left">Units</td>
            <td class="border-0 text-left">Rate Each</td>
            <td class="border-0 text-left">Total</td>
          </tr>
            <?php foreach ($load->loadAccessories as $ak => $av) { ?>
              <tr>
                <td class="border-0 text-left"><span
                          class="clr-blue"><?= $av->accessorial->accessorialRating->description ?></span></td>
                <td class="border-0 text-left"><span class="clr-blue"><?= $av->reference ?></span></td>
                <td class="border-0 text-left"><span class="clr-blue"><?= $av->units ?></span></td>
                <td class="border-0 text-left"><span class="clr-blue"><?= $av->rate_each ?></span></td>
                <td class="border-0 text-left"><span class="clr-blue"><?= $av->amount ?></span></td>
              </tr>
            <?php } ?>
        </table>
      <?php endif; ?>
      <?php $coDriverPercent = false; ?>
    <table class="w-100 no-border border-0">
      <tr class="bg-grey">
        <td class="border-0 text-left">Carried by</td>
        <td class="border-0 text-left">Deliver Cell</td>
        <td class="border-0 text-left">Status</td>
        <td class="border-0 text-left">Truck</td>
        <td class="border-0 text-left">Trailer</td>
        <td class="border-0 text-left">Miles</td>
        <td class="border-0 text-right">Pay Code</td>
        <td class="border-0 text-left">Pay Type</td>
        <td class="border-0 text-right">Rate</td>
        <td class="border-0 text-left">Total Pay</td>
          <?php if ($load->dispatchAssignment->codriver_id && $load->dispatchAssignment->codriver_id != ''
              && $load->dispatchAssignment->driver->co_driver_earning_percent && $load->dispatchAssignment->driver->co_driver_earning_percent > 0): ?>
              <?php $coDriverPercent = true; ?>
            <td class="border-0 text-left">Percent</td>
            <td class="border-0 text-left">Amount</td>
          <?php endif; ?>
      </tr>
      <tr>
        <td class="border-0 text-left">
                    <span class="clr-blue">
                        <?= $load->dispatchAssignment->driver->first_name . " " . $load->dispatchAssignment->driver->last_name . " " . $load->dispatchAssignment->driver->middle_name ?>
                    </span>
        </td>
        <td class="border-0 text-left"><span class="clr-blue"><?= $load->dispatchAssignment->driver->telephone ?></span>
        </td>
        <td class="border-0 text-left"><span class="clr-blue"><?= $load->dispatchAssignment->driver->status ?></span>
        </td>
        <td class="border-0 text-left"><span
                  class="clr-blue"><?= $load->dispatchAssignment->truck->truck_no . " (" . $load->dispatchAssignment->truck->make . ")"; ?></span>
        </td>
        <td class="border-0 text-left"><span
                  class="clr-blue"><?= $load->dispatchAssignment->trailer->trailer_no . " (" . $load->dispatchAssignment->trailer->make . ")"; ?></span>
        </td>
        <td class="border-0 text-left"><span
                  class="clr-blue"><?= $load->dispatchAssignment->driver->loaded_per_mile ?></span></td>
        <td class="border-0 text-right"><span class="clr-blue"><?= $load->dispatchAssignment->pay_code ?></span></td>
        <td class="border-0 text-right"><span class="clr-blue"><?= $load->dispatchAssignment->driver_pay_type ?></span>
        </td>
        <td class="border-0 text-left"><span
                  class="clr-blue"><?= $load->dispatchAssignment->driver_loaded_miles ?></span></td>
        <td class="border-0 text-left"><span
                  class="clr-blue"><?= Yii::$app->formatter->asDecimal($load->dispatchAssignment->driver_total_pay) ?></span>
        </td>
          <?php if ($coDriverPercent == true): ?>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?php $driverPercent = 1 - $load->dispatchAssignment->driver->co_driver_earning_percent;
                    echo $driverPercent; ?></span></td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= Yii::$app->formatter->asDecimal($driverPercent * $load->dispatchAssignment->driver_total_pay) ?></span>
            </td>
          <?php endif ?>
      </tr>

        <?php if ($coDriverPercent == true): ?>
          <tr>
            <td class="border-0 text-left">
                        <span class="clr-blue">
                            <?= $load->dispatchAssignment->codriver->first_name . " " . $load->dispatchAssignment->codriver->last_name . " " . $load->dispatchAssignment->codriver->middle_name ?>
                        </span>
            </td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= $load->dispatchAssignment->codriver->telephone ?></span></td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= $load->dispatchAssignment->codriver->status ?></span></td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= $load->dispatchAssignment->truck->truck_no . " (" . $load->dispatchAssignment->truck->make . ")"; ?></span>
            </td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= $load->dispatchAssignment->trailer->trailer_no . " (" . $load->dispatchAssignment->trailer->make . ")"; ?></span>
            </td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= $load->dispatchAssignment->codriver->loaded_per_mile ?></span></td>
            <td class="border-0 text-right"><span class="clr-blue"><?= $load->dispatchAssignment->pay_code ?></span>
            </td>
            <td class="border-0 text-right"><span
                      class="clr-blue"><?= $load->dispatchAssignment->codriver_pay_type ?></span></td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= $load->dispatchAssignment->codriver_loaded_miles ?></span></td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= Yii::$app->formatter->asDecimal($load->dispatchAssignment->driver_total_pay)//Yii::$app->formatter->asDecimal($load->dispatchAssignment->codriver_total_pay) ?></span>
            </td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= $load->dispatchAssignment->driver->co_driver_earning_percent ?></span></td>
            <td class="border-0 text-left"><span
                      class="clr-blue"><?= Yii::$app->formatter->asDecimal($load->dispatchAssignment->driver->co_driver_earning_percent * $load->dispatchAssignment->driver_total_pay) ?></span>
            </td>
          </tr>
        <?php endif; ?>
    </table>
  </div>
</div>
</body>