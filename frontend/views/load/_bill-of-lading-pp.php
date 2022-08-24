<?php

use common\enums\BillOfLadingType;

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
  <div class="container">
    <div class="doc tables_straight">
      <div class="doc_title">
        <p class="text-uppercase w-100">Straight bill of lading - short form - original - not negotiable</p>
      </div>
      <hr class="border-y">
        <?php $lastLoadStop = $load->getLastLoadStop(); ?>
      <div class="doc__table mb-10">
        <table>
          <thead class="doc__table-head">
          <tr class="va-bottom">
            <th class="text-uppercase text-left">Name of carrier</th>
            <th class="text-uppercase text-left">Carrier's NO.</th>
            <th class="text-uppercase text-left">Date</th>
            <th class="text-uppercase text-left">Shipper's NO.</th>
          </tr>
          </thead>
          <tbody class="doc__table-body">
          <tr>
            <td><?= $company->name ?></td>
            <td><?= $load->loadStops[0]->stop_number ?></td>
            <td><?= $formatter->asDate($load->loadStops[0]->available_from, Yii::$app->params['formats'][0]) ?></td>
            <td><?= $lastLoadStop->stop_number ?></td>
          </tr>
          </tfoot>
        </table>
      </div>
      <hr class="border-y">
      <div class="doc__txt">
        <p class="title_p"><strong>RECEIVED, subject to the classifications and lawfully filed tariffs in effect on the
            date of this Bill of Lading,</strong></p>
        <p class="small-m">
          the property described below in apparent good order, except as noted (contents and condition of contents of
          packages unknown), marked, consigned, and destined as indicated below which said carrier (the word carrier
          being understood
          throughout this contract as meaning any person or corporation in possession of the property under the
          contract) agrees to carry to its usual place of delivery at said destination, if on its route, otherwise to
          deliver to another carrier on the route to
          said destination. It is mutually agreed as to each carrier of all or any of said property over all or any
          portion of said route to destination, and as to each party at any time interested in all or any said property,
          that every service to be performed
          hereunder shall be subject to all the terms and conditions of the Uniform Domestic Straight Bill of Lading set
          forth (1) in Uniform Freight Classifications in effect on the date hereof, if this is a rail or a rail-water
          shipment, or (2) in the applicable
          motor carrier classification or tariff if this is a motor carrier shipment.
        </p>
        <p class="small-l">
          Shipper hereby certifies that he is familiar with all the terms and conditions of the said bill of lading, set
          forth in the classification or tariff which governs the transportation of this shipment, and the said terms
          and conditions are
          hereby agreed to by the shipper and accepted for himself and his assigns.
        </p>
      </div>
      <hr class="border-y">
        <?php $stops = $load->getLoadStops()->orderBy(['stop_number' => SORT_ASC])->all(); ?>
        <?php
        $i = 0;
        $k = 0;
        $shipperCompanyName = '';
        $consigneeCompanyName = '';
        $numItems = count($stops);
        foreach ($stops as $stop) { ?>
            <?php
            if (++$k === $numItems) {
                $consigneeCompanyName = $stop->company->company_name;
            }
            if ($i == 0) {
                $shipperCompanyName = $stop->company->company_name;
            }
            ?>
            <?php $i++;
        } ?>
      <table class="w-100 border-top">
        <tr>
          <td class="w-50">
            <table class="w-100">
              <tr class="w-20 border-0">
                <th class="text-uppercase text-red border-0 text-left">From</th>
                <th class="text-uppercase border-0"></th>
              </tr>
              <tr class="w-20 border-0">
                <td class="text-uppercase border-0">Shipper</td>
                <td class="text-uppercase border-0"><?= $shipperCompanyName ?></td>
              </tr>
              <tr class="w-20 border-0">
                <td class="text-uppercase border-0">(Origin)</td>
                <td class="text-uppercase border-0"><?= $load->loadStops[0]->address . ", " . $load->loadStops[0]->zone . " " . $load->loadStops[0]->zip ?></td>
              </tr>
            </table>
          </td>
          <td class="w-50 border-left pleft">
            <table class="w-100">
              <tr class="w-20 border-0">
                <th class="text-uppercase text-red border-0 text-left">To</th>
                <th class="text-uppercase border-0"></th>
              </tr>
              <tr class="w-20 border-0">
                <td class="text-uppercase border-0">CONSIGNEE</td>
                <td class="text-uppercase border-0"><?= $consigneeCompanyName ?></td>
              </tr>
              <tr class="w-20 border-0">
                <td class="text-uppercase border-0">Street</td>
                <td class="text-uppercase border-0"><?= $lastLoadStop->address . ", " . $lastLoadStop->zone . " " . $lastLoadStop->zip ?></td>
              </tr>
              <tr class="w-20 border-0">
                <td class="text-uppercase border-0">Destination</td>
                <td class="text-uppercase border-0">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table class="w-100">
        <tr>
          <td class="w-15">
            <table class="w-100">
              <tr class="w-20 border-0">
                <td class="text-uppercase border-0 w-30">DELIVERING<br>CARRIER</td>
                <td class="text-uppercase border-0 w-70"><?= $load->dispatchAssignment->driver->first_name . " " . $load->dispatchAssignment->driver->last_name ?></td>
              </tr>
            </table>
          </td>
          <td class="w-85 border-left pleft">
            <table class="w-100">
              <tr class="w-20 border-0">
                <td class="text-uppercase border-0 w-50">ROUTE</td>
                <td class="text-uppercase border-0 w-50">VEHICLE<br>NUMBER
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= $load->dispatchAssignment->truck->truck_no ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table class="w-100 big-table valign-top">
        <tr>
          <td class="w-10 text-uppercase text-center">
            Pieces
          </td>
          <td class="w-5 text-red text-uppercase text-center border-left">
            HM
          </td>
          <td class="w-60 text-uppercase text-center border-left">
            <p>KIND OF PACKAGE, DESCRIPTION OF ARTICLES,<br>
              SPECIAL MARKS AND EXCEPTIONS</p>
          </td>
          <td class="w-10 text-uppercase text-center border-left">
            WEIGHT
          </td>
          <td class="w-15 text-uppercase text-center border-left">
            CHARGES
          </td>
        </tr>
        <tr>
          <td class="w-10 pleft">
              <?= $load->commodity_pieces ?>
          </td>
          <td class="w-5 border-left text-center">
              <?= $load->commodityCommodity ? $load->commodityCommodity->hazmat_code : '' ?>
          </td>
          <td class="w-60 border-left text-center padding_top pbottom-mini">
            <p class="clr-blue text-center">*** THIRD PARTY BILLING - REMIT PAYMENT TO ***<br>
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
          <td class="w-10 border-left text-center">
              <?= $load->commodity_weight ?>
          </td>
          <td class="w-15 border-left">

          </td>
        </tr>
      </table>
      <table class="w-100 text-red">
        <tr>
          <td class="w-35">
            PLACARDS SUPPILED&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="checker">&nbsp;&nbsp;&nbsp;&nbsp;</span> YES&nbsp;&nbsp;
            <span class="checker">&nbsp;&nbsp;&nbsp;&nbsp;</span> NO
          </td>
          <td class="w-40 border-left pleft pbottom">
            <span class="small-l">DRIVER'S SIGNATURE</span>
          </td>
          <td class="w-25 border-left pleft pbottom">
            <span class="small-l">EMERGENCY RESPONSE PHONE NO</span>
          </td>
        </tr>
      </table>
      <table class="w-100 text-red">
        <tr>
          <td class="w-50 valign-top">
            <p>REMIT C.O.D. TO:</p>
          </td>
          <td class="w-25 border-left pleft valign-top">
            <p>C.O.D. Amt $</p>
          </td>
          <td class="w-25 border-left pleft valign-top pbottom-mini">
            <p>C.O.D. FEE</p>
            <p>
              <span class="checker">&nbsp;<?php if ($load->bill == 'P'): echo "X"; else: echo "&nbsp;&nbsp;"; endif; ?>&nbsp;</span>
              PREPAID<br>
              <span class="checker">&nbsp;<?php if ($load->bill == 'C'): echo "X"; else: echo "&nbsp;&nbsp;"; endif; ?>&nbsp;</span>
              COLLECT
            </p>
          </td>
        </tr>
      </table>

      <table class="w-100">
        <tr>
          <td class="w-25">
            <p class="text-red small-m">*If the shipment moves between two ports by a carrier by
              water, the law requires that the bill of lading shall state required to state
              whether it is "carrier's or shipper's weight."
            </p>
            <p class="small-m"><br><strong>
                Shipper's imprintin lieu of stamp;not a part of bill of lading
                approved by the Interstate Commerce Commission.
              </strong>
            </p>
          </td>
          <td class="w-25 border-left pleft">
            <p class=" small-m">NOTE: Where the rate is dependent on value, shippers are
              required to state specifically in writing the agreed or declared value of the property.
              The agreed or declared value of the property is hereby specifically stated by the shipper to be not
              exceeding
            </p><br><br>
            <p>$&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Per</p>
          </td>
          <td class="w-25 border-left pleft  small-m">
            <p>Subject to Section 7 of conditions, if this shipment is to be delivered to the consignee without recourse
              on the
              consignor, the consignor shall sign the following statement:
              The carrier shall not make delivery of this shipment without payment of freight and all other lawful
              charges.
            </p><br><br><br><?= $freightChargesType ?>
            <p>
            <hr class="border-y" style="margin: 0">
            <br>(Signature of Consignor)</p>
          </td>
          <td class="w-25 border-left pleft">
            <table class="w-100 no-border">
              <tr>
                <td class="w-50">
                    <?php if ($freightChargesType == BillOfLadingType::SHOW_FREIGHT_CHARGES): ?>
                      <p><strong>TOTAL<br>CHARGES</strong></p>
                    <?php else: ?>&nbsp;
                    <?php endif ?>
                </td>
                <td class="w-50">
                    <?php if ($freightChargesType == BillOfLadingType::SHOW_FREIGHT_CHARGES): ?>
                      <p> <?= Yii::$app->formatter->asDecimal($load->total) ?></p>
                    <?php else: ?>&nbsp;
                    <?php endif ?>
                </td>
              </tr>
            </table>
            <hr class="border-y">
            <table class="w-100 no-border">
              <tr>
                <td class="w-50">
                  <p class="text-red small-l">Freight charges are
                    PREPAID unless
                    marked collect</p>
                </td>
                <td class="w-50 small-l">
                  <p><span class="checker ch_black">&nbsp;&nbsp;&nbsp;&nbsp;</span> Check box if
                    charges are Collect</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <p class="small-l">"This is to certify that the above named materials are properly classified, described,
        packaged,
        marked and labeled and are in proper condition for transportation, according to the applicable regulations of
        the Department of Transportation."</p>
      <br><br><br>
      <table class="w-100 no-border">
        <tr>
          <td class="w-30">&nbsp;</td>
          <td class="w-40"><strong>Shipper, Per</strong></td>
          <td class="w-30"><strong>Agent, Per</strong></td>
        </tr>
      </table>
      <hr class="border-y" style="margin: 0">
      <p><span class="small-m">Permanent post office address of shipper</span>&nbsp;&nbsp;&nbsp;
        <strong class="small-m">+ MARK WITH "X" TO DESIGNATE HAZARDOUS MATERIAL AS DEFINED IN TITLE 49 OF FEDERAL
          REGULATIONS.</strong></p>
      <p class="text-red small-m">
        When transporting hazardous materials include the technical or chemical name for n.o.s. (not otherwise
        specified) or generic description of material with appropriate UN OG NA number as defined in US DOT Emergency
        Response Communication
        Standard (HM-126C). Provide emergency response phone number in case of incident or accident.
      </p>
    </div>
  </div>
</div>
</body>