<?php

use common\models\Load;
use common\models\Unit;
use common\widgets\DataTables\DataColumn;
use common\helpers\Utils;
use yii\helpers\ArrayHelper;
use common\enums\LoadStopType;
use common\models\LoadStop;

/**
 * @var \yii\web\View $this
 * @var Load $load
 * @var Unit[] $units
 * @var array $calculatedRows
*/

$dispatchAssignment = $load->dispatchAssignment;
$loadStops = $load->getLoadStopsOrdered();
$formatter = Yii::$app->getFormatter();
/** @var LoadStop $pickup */
/** @var LoadStop $deliver */
$pickup = $deliver = null;
if ($array = array_filter($loadStops, function (LoadStop $loadStop) { return $loadStop->stop_type = LoadStopType::SHIPPER; }))
{
    $pickup = $array[0];
}
if ($array = array_filter($loadStops, function (LoadStop $loadStop) { return $loadStop->stop_type = LoadStopType::CONSIGNEE; }))
{
    $deliver = end($array);
}
?>
                  <h1 class="h3 mb-4 text-gray-800"><?= Yii::t('app', 'Find Unit') ?></h1>
                  <div class="card shadow mb-4">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12 mb-5">
                          <?= \common\widgets\DataTables\Grid::widget([
                              'cssClass' => 'table-sm',
                              'dataProvider' => new \yii\data\ArrayDataProvider(['modelClass' => Unit::class, 'allModels' => $units]),
                              'columns' => [
                                  'id|title=Unit',
                                  'driver_id|title=Driver|rel=driver.getFullName()',
                                  'truck_id|title=Truck|default=No Truck',
                                  'trailer_id|title=Trailer|rel=trailer.trailer_no|default=No Trailer',
                                  'trailer_id|title=Type|rel=trailer.type|default=No Trailer',
                                  new DataColumn([
                                      'title' => Yii::t('app', 'Team'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['team'];
                                      }
                                  ]),
                                  'status|filter=\\common\\helpers\\Utils::abbreviation',
                                  new DataColumn([
                                      'title' => Yii::t('app', 'Date'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['date'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'City'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['city'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'St'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['stateCode'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'Z'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['zone'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'Next'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['nextDate'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'City'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['nextCity'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'St'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['nextStateCode'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'Act'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['act'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'DH P'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['dhp'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'DH D'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['dhd'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'Total'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['total'];
                                      }
                                  ]),
                                  new DataColumn([
                                      'title' => Yii::t('app', 'Pct'),
                                      'value' => function (Unit $unit) use ($calculatedRows) {
                                          return $calculatedRows[$unit->id]['pct'];
                                      }
                                  ])
                              ],
                              'order' => [[15, 'asc']]
                          ]) ?>
                        </div>
                        <div class="col-3">
                          <div class="form-fieldset">
                            <div class="col"></div>
                          </div>
                        </div>
                        <div class="col-9">
                          <div class="form-fieldset">
                            <div class="row">
                              <div class="col-2">
                                <div class="row">
                                  <div class="col-5"><?= Yii::t('app', 'Load') ?></div>
                                  <div class="col-7"><span class='form-readonly-text'><?= $load->id ?></span></div>
                                  <div class="col-5"><?= Yii::t('app', 'Status') ?></div>
                                  <div class="col-7"><span class='form-readonly-text'><?= $load->status ?></span></div>
                                  <?php if ($equip = \yii\helpers\ArrayHelper::getValue($dispatchAssignment, ['trailer', 'type'])): ?>
                                  <div class="col-5"><?= Yii::t('app', 'Equip') ?></div>
                                  <div class="col-7"><span class='form-readonly-text'><?= $equip ?></span></div>
                                  <?php endif; ?>
                                </div>
                              </div>
                              <?php if ($pickup): ?>
                              <div class="col-4">
                                <div class="row">
                                  <div class="col-3"><?= Yii::t('app', 'Pickup') ?></div>
                                  <div class="col-9"><pre class='form-readonly-text'><?= $formatter->asDate($pickup->available_from, Utils::getParam('formatter.date.short')) . ($pickup->time_from ? ' ' . $formatter->asTime($pickup->time_from, Utils::getParam('formatter.time.24h')) : '') ?></pre></div>
                                  <?php if ($pickup->address): ?>
                                  <div class="col-3"></div>
                                  <div class="col-9"><span class='form-readonly-text'><?= $pickup->address ?></span></div>
                                  <?php endif; ?>
                                  <div class="col-3"></div>
                                  <div class="col-9"><span class='form-readonly-text'><?= sprintf('%s, %s %s', $pickup->city, $pickup->state_id ? $pickup->state->state_code : '', $pickup->zip) ?></span></div>
                                </div>
                              </div>
                              <?php endif; if ($deliver): ?>
                              <div class="col-4">
                                <div class="row">
                                  <div class="col-3"><?= Yii::t('app', 'Deliver') ?></div>
                                  <div class="col-9"><pre class='form-readonly-text'><?= $formatter->asDate($deliver->available_from, Utils::getParam('formatter.date.short')) . ($deliver->time_from ? ' ' . $formatter->asTime($deliver->time_from, Utils::getParam('formatter.time.24h')) : '') ?></pre></div>
                                  <?php if ($deliver->address): ?>
                                  <div class="col-3"></div>
                                  <div class="col-9"><span class='form-readonly-text'><?= $deliver->address ?></span></div>
                                  <?php endif; ?>
                                  <div class="col-3"></div>
                                  <div class="col-9"><span class='form-readonly-text'><?= sprintf('%s, %s %s', $deliver->city, $deliver->state_id ? $deliver->state->state_code : '', $deliver->zip) ?></span></div>
                                </div>
                              </div>
                              <?php endif; ?>
                              <div class="col-2">
                                <div class="row">
                                  <div class="col-5"><?= Yii::t('app', 'Miles') ?></div>
                                  <div class="col-7"><span class='form-readonly-text'><?= $load->bill_miles ?></span></div>
                                  <div class="col-5"><?= Yii::t('app', 'Weight') ?></div>
                                  <div class="col-7"><span class='form-readonly-text'><?= $load->commodity_weight ?></span></div>
                                  <div class="col-5"><?= Yii::t('app', 'Pieces') ?></div>
                                  <div class="col-7"><span class='form-readonly-text'><?= $load->commodity_pieces ?></span></div>
                                  <div class="col-5"><?= Yii::t('app', 'HazMat') ?></div>
                                  <div class="col-7"><span class='form-readonly-text'><?= $load->hazMatCode() ?></span></div>
                                  <div class="col-5"><?= Yii::t('app', 'X Stops') ?></div>
                                  <div class="col-7"><span class='form-readonly-text'><?= $load->XStops() ?></span></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>