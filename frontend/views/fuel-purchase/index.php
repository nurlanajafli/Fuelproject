<?php

use yii\helpers\Url;

$this->title = Yii::t('app', 'Fuel Purchases');
$this->params['breadcrumbs'][] = $this->title;

$todayDate = new DateTime('now', new DateTimeZone(Yii::$app->formatter->defaultTimeZone));
$format = Yii::$app->params['formats'][13];

$periodPatterns = [];
$periodPatterns[] = [
    'This Month',
    Yii::$app->formatter->asDate((clone $todayDate)->sub(new DateInterval(sprintf('P%dD', $todayDate->format('j') - 1))), $format),
    Yii::$app->formatter->asDate((clone $todayDate)->add(new DateInterval(sprintf('P%dD', $todayDate->format('t') - $todayDate->format('j')))), $format)
];
$date = (clone $todayDate)->sub(new DateInterval('P1M'));
$periodPatterns[] = [
    'Last Month',
    Yii::$app->formatter->asDate((clone $date)->sub(new DateInterval(sprintf('P%dD', $date->format('j') - 1))), $format),
    Yii::$app->formatter->asDate((clone $date)->add(new DateInterval(sprintf('P%dD', $date->format('t') - $date->format('j')))), $format)
];

$quarter = Yii::$app->formatter->asDate($todayDate, 'Q');
$periodPatterns[] = [
    'This Quarter',
    Yii::$app->formatter->asDate((clone $todayDate)->setDate($todayDate->format('Y'), $quarter * 3 - 2, 1), $format),
    Yii::$app->formatter->asDate((clone $todayDate)->setDate($todayDate->format('Y'), $quarter * 3, ($quarter == 2 || $quarter == 3) ? 30 : 31), $format)
];
$date = clone $todayDate;
$quarter--;
if ($quarter == 0) {
    $quarter = 4;
    $date->sub(new DateInterval('P1Y'));
}
$periodPatterns[] = [
    'Last Quarter',
    Yii::$app->formatter->asDate((clone $date)->setDate($date->format('Y'), $quarter * 3 - 2, 1), $format),
    Yii::$app->formatter->asDate((clone $date)->setDate($date->format('Y'), $quarter * 3, ($quarter == 2 || $quarter == 3) ? 30 : 31), $format)
];

$date = (clone $todayDate)->sub(new DateInterval(sprintf('P%dM', $todayDate->format('n') - 1)));
$date->sub(new DateInterval(sprintf('P%dD', $date->format('j') - 1)));
$periodPatterns[] = [
    'This Year',
    Yii::$app->formatter->asDate($date, $format),
    Yii::$app->formatter->asDate((clone $date)->add(new DateInterval('P11M30D')), $format)
];
$date = (clone $todayDate)->sub(new DateInterval(sprintf('P1Y%dM', $todayDate->format('n') - 1)));
$date->sub(new DateInterval(sprintf('P%dD', $date->format('j') - 1)));
$periodPatterns[] = [
    'Last Year',
    Yii::$app->formatter->asDate($date, $format),
    Yii::$app->formatter->asDate((clone $date)->add(new DateInterval('P11M30D')), $format)
];

$periodPatterns[] = ['Today', Yii::$app->formatter->asDate($todayDate, $format), Yii::$app->formatter->asDate($todayDate, $format)];
?>
<h1 class="h3 mb-4 text-gray-800"><?= $this->title ?></h1>

<?php $this->beginBlock('leftToolbarHtml'); ?>
<button class="btn btn-primary" id="fuelpurchase-reload" data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Reload') ?>"><i class="fas fa-sync"></i></button>
<button class="btn btn-primary js-ajax-modal" data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Input Fuel Purchase') ?>" data-url="<?= Url::toRoute('create') ?>"><i class="fas fa-plus"></i></button>
<?php $this->endBlock(); ?>

<?php $this->beginBlock('rightToolbarHtml'); ?>
<div class="dt-toolbar-actions d-flex" data-url="<?= Url::toRoute(['data', 'startDate' => 'value1', 'endDate' => 'value2']) ?>">
  <div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-filter"></i></button>
    <div class="dropdown-menu" id="fuelpurchase-datepatterns">
      <?php foreach ($periodPatterns as $periodPattern) : ?>
      <a href="#" class="dropdown-item" data-value1="<?= $periodPattern[1] ?>" data-value2="<?= $periodPattern[2] ?>"><?= Yii::t('app', $periodPattern[0]) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <input class="form-control fuelpurchase-datefilter" type="text">
  <input class="form-control fuelpurchase-datefilter" type="text">
</div>
<?php $this->endBlock(); ?>

<?= \common\widgets\DataTables\Grid::widget([
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels' => [],
        'pagination' => false,
    ]),
    'columns' => [
        'trip_no|title=Trip|className=text-center',
        'purchase_date|title=Date|className=text-center',
        'purchase_time|title=Time|className=text-center',
        'id|visible=false',
        'driver_id|title=Off|className=text-center|filterable=true',
        'driver_id|title=Driver|filterable=true',
        'truck_id|title=Truck|filterable=true',
        'vendor|title=Vendor',
        'city|title=City',
        'state_id|className=text-center|title=St',
        'quantity|title=Qty|className=text-right',
        'cost|title=Cost|className=text-right',
        'ppg|title=PPG|className=text-right',
    ],
    'template' => '<div class="card shadow mb-4"><div class="card-header py-3">{toolbar}</div><div class="card-body">{table}</div></div>',
    'id' => 'fuelpurchase-index',
    'order' => [[1, 'desc'], [2, 'desc']],
    'doubleClick' => ['modal', Url::toRoute(['update', 'id' => 'col:3'])],
    'dom' => 'tp',
    'orderCellsTop' => true,
    'autoWidth' => false,
    'conditionalPaging' => true,
    'ajax' => Url::toRoute('data'),
    'stateSave' => false,
    'leftToolbarHtml' => $this->blocks['leftToolbarHtml'],
    'rightToolbarHtml' => $this->blocks['rightToolbarHtml'],
]) ?>