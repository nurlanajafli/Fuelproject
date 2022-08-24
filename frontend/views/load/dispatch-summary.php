<?php
/**
 * @var \common\models\Load $model
 * @var \common\models\LoadMovement[] $loadMovements
 */

use common\enums\LoadStatus;
use common\helpers\Utils;
use common\models\LoadAccessory;
use yii\helpers\Url;

$this->beginBlock('form');
?>
  <div class="card-header py-3">
    <div class="edit-form-toolbar">
        <?php if (!in_array($model->status, [LoadStatus::ENROUTED, LoadStatus::COMPLETED])) : ?>
          <div class="dropdown">
            <button class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-toggle="dropdown"
                    data-placement="top" title="<?= Yii::t('app', 'Dispatch Load') ?>"><i
                      class="fas fa-truck"></i></button>
            <div class="dropdown-menu">
              <a class="dropdown-item js-ajax-modal"
                 data-url="<?= Url::toRoute(['dispatch-load', 'id' => $model->id]) ?>"
                 href="#"><?= Yii::t('app', 'Dispatch Load') ?></a>
              <a class="dropdown-item disabled" href="#"><?= Yii::t('app', 'Broker Load') ?></a>
            </div>
          </div>
        <?php else : ?>
          <button class="btn btn-link js-ajax-modal"
                  data-url="<?= Url::toRoute(['arrive', 'id' => $model->id]) ?>" data-tooltip="tooltip"
                  data-placement="top" title="<?= Yii::t('app', 'Arrive Load') ?>"><i
                    class="fas fa-sign-in-alt"></i></button>
        <?php endif; ?>
      <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-toggle="dropdown"
                data-placement="top" title="<?= Yii::t('app', 'Reserve Load') ?>"><i
                  class="fas fa-calendar-plus"></i></button>
        <div class="dropdown-menu">
          <a class="dropdown-item js-ajax-modal"
             data-url="<?= Url::toRoute(['reserve-load', 'id' => $model->id]) ?>"
             href="#"><?= Yii::t('app', 'Reserve Unit') ?></a>
          <a class="dropdown-item disabled" href="#"><?= Yii::t('app', 'Reserve Carrier') ?></a>
        </div>
      </div>
      <div class="dropdown">
        <button disabled class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-toggle="dropdown"
                data-placement="top" title="<?= Yii::t('app', 'Pay Accessorials') ?>"><i
                  class="fas fa-money-bill"></i></button>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="#"><?= Yii::t('app', 'Add Driver Accessorial Pay') ?></a>
          <a class="dropdown-item" href="#"><?= Yii::t('app', 'Add Carrier Accessorial Pay') ?></a>
        </div>
      </div>
      <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top"
              title="<?= Yii::t('app', 'Stop Details') ?>"><i class="fas fa-list-alt"></i></button>
      <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top"
              title="<?= Yii::t('app', 'Post to clipboard') ?>"><i class="fas fa-clipboard"></i></button>
    </div>
  </div>
  <div class="row my-1">
    <div class="col-4">
      <div class="card">
        <div class="card-body bp-0 py-1">
          <table class="table table-sm table-borderless max-width">
            <tr>
              <th><?= Yii::t('app', 'Load') ?></th>
              <td><?= $model->id ?></td>
              <td></td>
              <th><?= Yii::t('app', 'X Stops') ?></th>
              <td><?= count($model->loadStops) < 3 ? Yii::t('app', 'No') : count($model->loadStops) - 2 ?></td>
            </tr>
            <tr>
              <th><?= Yii::t('app', 'Status') ?></th>
              <td><?= $model->status ?></td>
              <td></td>
              <th><?= Yii::t('app', 'HazMat') ?></th>
              <td><?= Yii::t('app', 'No') ?></td>
            </tr>
            <tr>
              <th><?= Yii::t('app', 'Type') ?></th>
              <td><small><?= $model->type->description ?></small></td>
              <td></td>
              <th><?= Yii::t('app', 'Tailgates') ?></th>
              <td>No</td>
            </tr>
            <tr>
              <th><?= Yii::t('app', 'Seal') ?></th>
              <td><?= $model->seal_no ?></td>
              <td></td>
              <th><?= Yii::t('app', 'Variance') ?></th>
              <td><?= Yii::t('app', 'No') ?></td>
            </tr>
            <tr>
              <th><?= Yii::t('app', 'Miles') ?></th>
              <td class="text-right"><?= Yii::$app->formatter->asInteger($model->bill_miles) ?></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th><?= Yii::t('app', 'Office') ?></th>
              <td><?= $model->office->office ?></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-8">
      <div class="card">
        <!--                        <blockquote class="blockquote text-center">Dispatch and Accessorial Pay</blockquote>-->
          <?php $coDriverPercent = false; ?>
        <div class="card-header py-1 text-center"><? Yii::t('app', 'Dispatch and Accessorial Pay') ?></div>
        <div class="card-body">
          <table class="table table-sm table-bordered max-width table-responsive">
            <tr>
              <th><?= Yii::t('app', 'Date') ?></th>
              <th><?= Yii::t('app', 'Name') ?></th>
              <th><?= Yii::t('app', 'Status') ?></th>
              <th><?= Yii::t('app', 'Pay Code') ?></th>
              <th><?= Yii::t('app', 'Type') ?></th>
              <th><?= Yii::t('app', 'Miles') ?></th>
              <th><?= Yii::t('app', 'Rate') ?></th>
              <th><?= Yii::t('app', 'Pay') ?></th>
                <?php if ($model->dispatchAssignment) : ?>
                    <?php $da = $model->dispatchAssignment; ?>
                    <?php if ($da->driver->co_driver_id && $da->driver->co_driver_id > 0 &&
                        $da->driver->co_driver_earning_percent && $da->driver->co_driver_earning_percent > 0) {
                        $coDriverPercent = true; ?>
                    <th><?= Yii::t('app', 'Percent') ?></th>
                    <th><?= Yii::t('app', 'Amount') ?></th>
                    <?php } ?>
                <?php endif ?>
              <th><?= Yii::t('app', 'Batch') ?></th>
            </tr>
              <?php if ($model->dispatchAssignment) : ?>
                  <?php $da = $model->dispatchAssignment; ?>
                <tr>
                  <td><?= Yii::$app->formatter->asDate($da->dispatch_start_date, Utils::getParam('formatter.date.short')); ?></td>
                  <td><?= $da->driver->_label ?></td>
                  <td></td>
                  <td></td>
                  <td><?= $da->driver_pay_type ?></td>
                  <td><?= Yii::$app->formatter->asInteger($da->driver_loaded_miles) ?></td>
                  <td><?= $da->driver_loaded_rate ?></td>
                  <td><?= Yii::$app->formatter->asDecimal($da->driver_total_pay) ?></td>
                    <?php if ($coDriverPercent == true): ?>
                      <td><?php $driverPercent = 1 - $da->driver->co_driver_earning_percent;
                          echo $driverPercent; ?></td>
                      <td><?= Yii::$app->formatter->asDecimal($da->driver_total_pay * $driverPercent) ?></td>
                    <?php endif ?>
                  <td></td>
                </tr>
                  <?php if (!empty($da->codriver_id)) : ?>
                  <tr>
                    <td><?= Yii::$app->formatter->asDate($da->dispatch_start_date, Utils::getParam('formatter.date.short')); ?></td>
                    <td><?= $da->codriver->_label ?></td>
                    <td></td>
                    <td></td>
                    <td><?= $da->codriver_pay_type ?></td>
                    <td><?= Yii::$app->formatter->asInteger($da->codriver_loaded_miles); ?></td>
                    <td><?= $da->driver_loaded_rate //$da->codriver_loaded_rate  ?></td>
                    <td><?= $da->driver_total_pay//$da->codriver_total_pay  ?></td>
                      <?php if ($coDriverPercent == true): ?>
                        <td><?= $da->driver->co_driver_earning_percent ?></td>
                        <td><?= Yii::$app->formatter->asDecimal($da->driver_total_pay * $da->driver->co_driver_earning_percent) ?></td>
                      <?php endif ?>
                    <td></td>
                  </tr>
                  <?php endif; else : ?>
                <tr>
                    <?= str_repeat("<td></td>", 9) ?>
                </tr>
              <?php endif; ?>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="row my-1">
    <div class="col-4">
      <div class="card">
        <div class="card-body bp-0 py-1">
          <table class="table table-sm table-borderless max-width">
            <tr>
              <th></th>
              <th><?= Yii::t('app', 'Freight') ?></th>
              <th><?= Yii::t('app', 'Access') ?></th>
              <th><?= Yii::t('app', 'Total') ?></th>
            </tr>
            <tr>
              <td><?= Yii::t('app', 'Rev') ?></td>
              <td class="text-right"><?= number_format($model->freight, 2) ?></td>
              <td class="text-right"><?= number_format($model->accessories, 2) ?></td>
              <td class="text-right"><?= number_format($model->total, 2) ?></td>
            </tr>
            <tr>
              <td>Exp</td>
              <td class="text-right">0.00</td>
              <td class="text-right">0.00</td>
              <td class="text-right">0.00</td>
            </tr>
            <tr>
              <td>Net</td>
              <td class="text-right"><?= number_format($model->freight, 2) ?></td>
              <td class="text-right"><?= number_format($model->accessories, 2) ?></td>
              <td class="text-right"><?= number_format($model->total, 2) ?></td>
            </tr>
            <tr>
              <td>%</td>
              <td class="text-right">100.00%</td>
              <td class="text-right">100.00%</td>
              <td class="text-right">100.00%</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-8">
      <div class="card">
        <div class="card-header py-1 text-center"><?= Yii::t('app', 'Accessorials Billed') ?></div>
        <div class="card-body">
          <table class="table table-sm table-bordered max-width">
            <tr>
              <th><?= Yii::t('app', 'Description') ?></th>
              <th><?= Yii::t('app', 'Units') ?></th>
              <th><?= Yii::t('app', 'Rate') ?></th>
              <th><?= Yii::t('app', 'Amount') ?></th>
              <th><?= Yii::t('app', 'Reference') ?></th>
              <th><?= Yii::t('app', 'Avail Pay') ?></th>
            </tr>
              <?php foreach ($model->loadAccessories as $accessory) : ?>
                  <?php /** @var LoadAccessory $accessory */ ?>
                <tr>
                  <td><?= $accessory->accessorial->accessorialRating->description; ?></td>
                  <td class="text-right"><?= $accessory->units; ?></td>
                  <td class="text-right"><?= $accessory->rate_each; ?></td>
                  <td class="text-right"><?= $accessory->amount; ?></td>
                  <td><?= $accessory->reference; ?></td>
                  <td class="text-right">0.00</td>
                </tr>
              <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="row my-1">
    <div class="col-12">
      <div class="card">
        <div class="card-header py-1"><?= Yii::t('app', 'Load movement summary') ?></div>
        <div class="card-body">
          <table class="table table-sm table-bordered max-width">
            <tr>
              <th></th>
              <th><?= Yii::t('app', 'Action') ?></th>
              <th><?= Yii::t('app', 'Company') ?></th>
              <th><?= Yii::t('app', 'City/St') ?></th>
              <th><?= Yii::t('app', 'Date') ?></th>
              <th><?= Yii::t('app', 'From') ?></th>
              <th><?= Yii::t('app', 'To') ?></th>
              <th><?= Yii::t('app', 'Unit') ?></th>
              <th><?= Yii::t('app', 'Carried By') ?></th>
              <th><?= Yii::t('app', 'Truck') ?></th>
              <th><?= Yii::t('app', 'Trailer') ?></th>
            </tr>
              <?php
              $i = 0;
              foreach ($loadMovements as $loadMovement) :
                  $i++;
                  if ($loadMovement->loadStop) {
                      //$location = $loadMovement->loadStop->company;
                      $driver = $model->dispatchAssignment ? $model->dispatchAssignment->driver : null;
                      $truck = $model->dispatchAssignment ? $model->dispatchAssignment->truck : null;
                      $trailer = $model->dispatchAssignment ? $model->dispatchAssignment->trailer : null;
                  } else {
                      $location = $loadMovement->location;
                      $driver = $loadMovement->driver;
                      $truck = $loadMovement->truck;
                      $trailer = $loadMovement->trailer;
                  }
                  ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $loadMovement->action ?></td>
                  <td><?= $loadMovement->loadStop ? $loadMovement->loadStop->getCompanyName() : $location->company_name ?></td>
                  <td><?= $loadMovement->loadStop ? $loadMovement->loadStop->getCity() : $location->city ?>
                    , <?= $loadMovement->loadStop ? $loadMovement->loadStop->getStateCode() : $location->state->state_code ?></td>
                  <td><?= Yii::$app->formatter->asDate($loadMovement->arrived_date, Yii::$app->params['formats'][1]); ?></td>
                  <td><?= Yii::$app->formatter->asTime($loadMovement->arrived_time_in, Yii::$app->params['formats']['12h']) ?></td>
                  <td><?= Yii::$app->formatter->asTime($loadMovement->arrived_time_out, Yii::$app->params['formats']['12h']) ?></td>
                  <td><?= $loadMovement->unit_id ?></td>
                  <td><?= $driver ? $driver->_label : '' ?></td>
                  <td><?= $truck ? $truck->get_label() : '' ?></td>
                  <td><?= $trailer ? $trailer->get_label() : '' ?></td>
                </tr>
              <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Dispatch Summary {n} TL', ['n' => $model->id]),
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-xl',
    'saveButton' => false
]);
