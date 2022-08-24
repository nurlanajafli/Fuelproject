<?php

use common\helpers\Utils;
use common\enums\CompanyThumb;
use common\models\AccountingDefault;

/**
 * @var \common\models\Company $company
 * @var \common\models\Load $load
 * @var AccountingDefault|null $accountingDefault
 */

$formatter = Yii::$app->getFormatter();
$shortDateFormat = Yii::$app->params['formats'][1];
$imageAttribute = $company->getImageAttribute();
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
</head>
<body>
  <div class="container">
    <div class="invoice">
      <div class="invoice__header">
        <table>
          <tbody>
            <tr>
              <td class="w-82 border-0">
                <div class="invoice__company clearfix">
                  <?php if ($company->$imageAttribute): ?><div class="invoice__company-logo pull-left"><img src="<?= $company->getThumbUploadUrl($imageAttribute, CompanyThumb::PREVIEW) ?>" alt=""></div><?php endif; ?>
                  <div class="invoice__company-info pull-left">
                    <p><?= $company->name ?></p>
                    <?php if ($company->address_1): ?>
                    <p><?= $company->address_1 ?></p>
                    <?php endif; if ($company->address_2): ?>
                    <p><?= $company->address_2 ?></p>
                    <?php endif; ?>
                    <p class="text-uppercase"><?= sprintf('%s, %s %s', $company->city, $company->state ? $company->state->state_code : '', $company->zip) ?></p>
                    <p><?= ($company->main_phone ? "P: {$company->main_phone} " : '') . ($company->main_fax ? "F: {$company->main_fax}" : '') ?></p>
                  </div>
                </div>
              </td>
              <td class="w-18 border-0">
                <div class="invoice__number">
                  <h4 class="clr-light-blue">INVOICE</h4>
                  <p><?= $load->id ?></p>
                  <p><?= $formatter->asDate('now', Yii::$app->params['formats'][0]) ?></p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="invoice__header-info">
          <tr>
            <td class="border-0">
              <p class="clr-light-blue">Bill To</p>
            </td>
          </tr>
          <tr>
            <td class="w-75 border-0">
              <p><?= $load->billTo->name ?></p>
              <?php if ($load->billTo->address_1): ?>
              <p><?= $load->billTo->address_1 ?></p>
              <?php endif; if ($load->billTo->address_2): ?>
              <p><?= $load->billTo->address_2 ?></p>
              <?php endif; ?>
              <p class="text-uppercase"><?= sprintf('%s, %s %s', $load->billTo->city, $load->billTo->state ? $load->billTo->state->state_code : '', $load->billTo->zip) ?></p>
            </td>
            <td class="w-25 border-0">
              <p>Terms:  <?= $load->billTo->terms0 ? $load->billTo->terms0->description : '' ?></p>
              <p>Reference: <?= $load->customer_reference ?></p>
              <p>Office: <?= $load->office ? $load->office->office : '' ?></p>
            </td>
          </tr>
        </table>
      </div>
      <div class="invoice__info">
        <div class="doc__table">
          <table>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Reference</td>
              </tr>
              <?php
              $loadStops = $load->getLoadStopsOrdered();
              foreach ($loadStops as $loadStop): ?>
              <tr>
                <td><?= Utils::abbreviation($loadStop->stop_type) ?: '?' ?></td>
                <td><?= $loadStop->getCompanyName() ?></td>
                <td><?= $loadStop->getAddress() ?></td>
                <td class="text-uppercase"><?= sprintf('%s, %s %s', $loadStop->getCity(), $loadStop->getStateCode(), $loadStop->getZip()) ?></td>
                <td><?= $loadStop->reference ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="invoice__inputs">
        <table class="invoice__inputs-first w-100">
          <tbody>
            <tr>
              <td>Commodity</td>
              <td>Description</td>
              <td>Reference</td>
              <?php if ($load->commodity_pieces != 0): ?><td>Pcs</td><?php endif; ?>
              <td>Bill Wgt</td>
            </tr>
            <tr>
                <td class="border-0"><?= $load->commodityCommodity ? $load->commodityCommodity->description : '' ?></td>
                <td class="border-0"></td>
                <td class="border-0"><?= $load->commodity_reference ?></td>
                <?php if ($load->commodity_pieces != 0): ?><td class="border-0"><?= $formatter->asDecimal($load->commodity_pieces) ?></td><?php endif; ?>
                <td class="border-0"><?= $formatter->asInteger($load->commodity_weight) ?></td>
            </tr>
          </tbody>
        </table>
        <table class="invoice__inputs-second w-100">
          <tbody>
            <tr>
              <td>Accessorial</td>
              <td>Reference</td>
              <td>Rate Ea</td>
              <td>Units</td>
              <td>Amount</td>
            </tr>
            <?php
            /** @var \common\models\LoadAccessories[] $accessories */
            $accessories = $load
                ->getLoadAccessories()
                ->joinWith('accessorial.accessorialRating')
                ->orderBy(['id' => SORT_ASC])
                ->all();
            foreach ($accessories as $accessory): ?>
            <tr>
              <td class="border-0"><?= $accessory->accessorial->accessorialRating->description ?></td>
              <td class="border-0"><?= $accessory->reference ?></td>
              <td class="border-0"><?= $formatter->asDecimal($accessory->rate_each) ?></td>
              <td class="border-0"><?= $formatter->asInteger($accessory->units) ?></td>
              <td class="border-0"><?= $formatter->asDecimal($accessory->amount) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="invoice__summary clearfix border-y">
        <div class="invoice__summary-item">
          <?php if ($loadStops): ?>
          <p>Pickup Date: <span><?= $formatter->asDate($loadStops[0]->available_from, $shortDateFormat) ?></span></p>
          <?php endif; if (count($loadStops) > 1): ?>
          <p>Delivery Date: <span><?= $formatter->asDate(end($loadStops)->available_thru, $shortDateFormat) ?></span></p>
          <?php endif; ?>
        </div>
        <div class="invoice__summary-item">
          <p>Total Pieces: <span><?= $formatter->asDecimal($load->commodity_pieces) ?></span></p>
          <p>Total Space: <span><?= $formatter->asDecimal($load->commodity_space) ?></span></p>
        </div>
        <div class="invoice__summary-item">
          <p>Actual Wgt: <span><?= $formatter->asInteger($load->commodity_weight) ?></span></p>
          <p>Tariff Wgt: <span>0</span></p>
        </div>
        <div class="invoice__summary-item">
          <p>As Wgt: <span><?= $formatter->asInteger($load->commodity_weight) ?></span></p>
          <p>Miles: <span><?= $formatter->asInteger($load->bill_miles) ?></span></p>
        </div>
        <div class="invoice__summary-item"></div>
      </div>
      <div class="invoice__total clearfix">
        <div class="pull-left w-50">
          <p>Remit Payment To:</p>
          <?php
          $out = [];
          if ($accountingDefault) {
              if ($accountingDefault->nameOnFactoredInvoicesCar) {
                  $out[] = $accountingDefault->nameOnFactoredInvoicesCar->name;
                  if ($accountingDefault->nameOnFactoredInvoicesCar->address_1) {
                      $out[] = $accountingDefault->nameOnFactoredInvoicesCar->address_1;
                  }
                  if ($accountingDefault->nameOnFactoredInvoicesCar->address_2) {
                      $out[] = $accountingDefault->nameOnFactoredInvoicesCar->address_2;
                  }
                  $out[] = sprintf('%s, %s %s',
                      $accountingDefault->nameOnFactoredInvoicesCar->city,
                      $accountingDefault->nameOnFactoredInvoicesCar->state ? $accountingDefault->nameOnFactoredInvoicesCar->state->state_code : '',
                      $accountingDefault->nameOnFactoredInvoicesCar->zip
                  );
              } elseif ($accountingDefault->nameOnFactoredInvoicesCus) {
                  $out[] = $accountingDefault->nameOnFactoredInvoicesCus->name;
                  if ($accountingDefault->nameOnFactoredInvoicesCus->address_1) {
                      $out[] = $accountingDefault->nameOnFactoredInvoicesCus->address_1;
                  }
                  if ($accountingDefault->nameOnFactoredInvoicesCus->address_2) {
                      $out[] = $accountingDefault->nameOnFactoredInvoicesCus->address_2;
                  }
                  $out[] = sprintf('%s, %s %s',
                      $accountingDefault->nameOnFactoredInvoicesCus->city,
                      $accountingDefault->nameOnFactoredInvoicesCus->state ? $accountingDefault->nameOnFactoredInvoicesCus->state->state_code : '',
                      $accountingDefault->nameOnFactoredInvoicesCus->zip
                  );
              } elseif ($accountingDefault->nameOnFactoredInvoicesVen) {
                  $out[] = $accountingDefault->nameOnFactoredInvoicesVen->name;
                  if ($accountingDefault->nameOnFactoredInvoicesVen->address_1) {
                      $out[] = $accountingDefault->nameOnFactoredInvoicesVen->address_1;
                  }
                  if ($accountingDefault->nameOnFactoredInvoicesVen->address_2) {
                      $out[] = $accountingDefault->nameOnFactoredInvoicesVen->address_2;
                  }
                  $out[] = sprintf('%s, %s %s',
                      $accountingDefault->nameOnFactoredInvoicesVen->city,
                      $accountingDefault->nameOnFactoredInvoicesVen->state ? $accountingDefault->nameOnFactoredInvoicesVen->state->state_code : '',
                      $accountingDefault->nameOnFactoredInvoicesVen->zip
                  );
              }
          }
          echo join('', array_map(function ($text) {
            return "<p>$text</p>";
          }, $out));
          ?>
        </div>
        <div class="pull-left w-25">
          <p>Rate By: <span><?= $load->rate_by ?></span></p>
          <p>Rate: <span><?= $formatter->asDecimal($load->rate) ?></span></p>
          <?php if ($load->discount_percent != 0): ?>
          <p>&nbsp;</p>
          <p>Discount: <span><?= $load->discount_percent ?></span></p>
          <?php endif; ?>
        </div>
        <div class="pull-left w-25">
          <table>
            <tbody>
              <tr>
                <td>
                  <p>Freight:</p>
                </td>
                <td>
                  <p><?= $formatter->asDecimal($load->freight) ?></p>
                </td>
              </tr>
              <tr>
                <td>
                  <p>+ Accessorials:</p>
                </td>
                <td>
                  <p><?= $formatter->asDecimal($load->accessories) ?></p>
                </td>
              </tr>
              <tr>
                <td>
                  <p>Invoice Total:</p>
                </td>
                <td>
                  <p class="border-b text-right"><?= $formatter->asDecimal($load->total) ?></p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>