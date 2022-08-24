<?php
/**
 * /var/www/html/frontend/runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22
 *
 * @package default
 */


use common\models\Load;
use common\widgets\DataTables\ActionColumn;
use common\widgets\DataTables\DataColumn;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app', 'Loads');
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo Yii::t('app', 'Load Clearing') ?></h1>
  </div>
<?php

echo common\widgets\DataTables\Grid::widget([
    'id' => 'load-clearing',
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        new DataColumn([
            'attribute' => 'office_id',
            'value' => function ($model) {
                if ($rel = $model->office) {
                    return $rel->_label;
                }
                return '';
            },
            'filterable' => true,
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'P/U'),
            'value' => function ($model) {
                /** @var Load $model */
                $stops = $model->getLoadStopsOrdered();
                $firstStop = $stops[0];
                return Yii::$app->formatter->asDate($firstStop->available_from, "php:Y/m/d");
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Del/Arrived'),
            'value' => function ($model) {
                /** @var Load $model */
                $stops = $model->getLoadStopsOrdered();
                $lastStop = $stops[count($stops) - 1];
                return Yii::$app->formatter->asDate($model->arrived_date ? $model->arrived_date : $lastStop->available_from, "php:Y/m/d");
            }
        ]),
        new DataColumn([
            'attribute' => 'bill_to',
            'value' => function ($model) {
                if ($rel = $model->billTo) {
                    return $rel->name;
                }
                return '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Origin'),
            'value' => function ($model) {
                /** @var Load $model */
                $stops = $model->getLoadStopsOrdered();
                $firstStop = $stops[0];
                return $firstStop->getCompanyName();
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'St'),
            'value' => function ($model) {
                /** @var Load $model */
                $stops = $model->getLoadStopsOrdered();
                $firstStop = $stops[0];
                return $firstStop->getStateCode();
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Dest'),
            'value' => function ($model) {
                /** @var Load $model */
                $stops = $model->getLoadStopsOrdered();
                $lastStop = $stops[count($stops) - 1];
                return $lastStop->getCompanyName();
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'St'),
            'value' => function ($model) {
                /** @var Load $model */
                $stops = $model->getLoadStopsOrdered();
                $lastStop = $stops[count($stops) - 1];
                return $lastStop->getStateCode();
            }
        ]),
        'notes',
        'status',
        new DataColumn([
            'title' => Yii::t('app', 'Delivered by'),
            'value' => function ($model) {
                /** @var Load $model */
                $da = $model->dispatchAssignment;
                return $da->driver ? $da->driver->get_label() : "";
            }
        ]),
        // Clearing - row[12]
        new DataColumn([
            'title' => Yii::t('app', 'Clearing'),
            'value' => function (Load $model) {
                return Yii::t('app', $model->loadcleared ? 'Cleared' : 'Open');
            },
            'filterable' => true,
        ]),
        new ActionColumn([
            'html' => function ($model) {
                $url = Url::toRoute(['clear-load', 'id' => $model->id]);
                return \yii\helpers\Html::a('<i class="fas fa-eraser"></i>', '#', ['class' => 'js-ajax-modal px-1', 'data-url' => $url, 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Clear')]);
            }
        ])
    ],
    'rightToolbarHtml' => '
<div class="dt-toolbar-actions js-daterange d-flex" data-column-index="2">
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-filter"></i></button>
        <div class="dropdown-menu">
            <a class="dropdown-item js-daterange-this-month" href="#">This Month</a>
            <a class="dropdown-item js-daterange-last-month" href="#">Last Month</a>
            <a class="dropdown-item js-daterange-this-quarter" href="#">This Quarter</a>
            <a class="dropdown-item js-daterange-last-quarter" href="#">Last Quarter</a>
            <a class="dropdown-item js-daterange-this-year" href="#">This Year</a>
            <a class="dropdown-item js-daterange-last-year" href="#">Last Year</a>
            <a class="dropdown-item js-daterange-today" href="#">Today</a>
        </div>
    </div>
    <input class="form-control js-daterange-min" type="text" name="min" placeholder="from date">
    <input class="form-control js-daterange-max" type="text" name="max" placeholder="to date">
</div>',
]);

