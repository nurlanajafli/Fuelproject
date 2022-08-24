<?php

/** @var \yii\data\ActiveDataProvider $dataProvider */

use common\enums\LoadStatus;
use common\models\DispatchAssignment;
use common\widgets\DataTables\DataColumn;

$this->title = Yii::t('app', 'Board');
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= Yii::t('app', 'Board') ?></h1>
  </div>
<?php
echo common\widgets\DataTables\Grid::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'load_id|title=Load',
        'truck_id|title=Truck|rel=truck.truck_no',
        'trailer_id|title=Trailer #|rel=trailer.trailer_no',
        new DataColumn([
            'title' => Yii::t('app', 'Driver Name'),
            'value' => function (DispatchAssignment $model) {
                return implode(' / ', [$model->driver ? $model->driver->getFullName() : '', $model->codriver ? $model->codriver->getFullName() : '']);
            }
        ]),
        'load_id|title=Status|rel=load.status',
        new DataColumn([
            'title' => Yii::t('app', 'Origin / Delivery'),
            'value' => function (DispatchAssignment $model) {
                $i = 0;
                $j = count($model->load->loadStops);
                while (($i < $j) && (!$model->load->loadStops[$i]->status)) {
                    $i++;
                }
                if ($i == $j) {
                    $i = $j - 1;
                }
                return $model->load->loadStops[$i]->getCity() . " " . $model->load->loadStops[$i]->getStateCode();
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'ETA'),
            'value' => function (DispatchAssignment $model) {
                $eta = '';
                $i = 0;
                $j = count($model->load->loadStops);
                while (($i < $j) && (!$model->load->loadStops[$i]->status)) {
                    $i++;
                }
                if ($i == $j) {
                    $i = $j - 1;
                }
                switch ($model->load->status) {
                    case LoadStatus::AVAILABLE:
                        $eta = 'READY ' . Yii::$app->formatter->asDate($model->created_at, 'E') . ' ' . Yii::$app->formatter->asTime($model->created_at, "HH':'mm");
                        break;
                    case LoadStatus::ENROUTED:
                        if ($model->load->loadStops[$i]->available_from || $model->load->loadStops[$i]->time_from) {
                            if ($i == 1) {
                                $eta = 'AT PU';
                            } else {
                                $eta = 'ETA TO ' . ($i == 0 ? 'PU' : 'DEL');
                                if ($model->load->loadStops[$i]->available_from) {
                                    $eta .= ' ' . Yii::$app->formatter->asDate($model->load->loadStops[$i]->available_from, 'E');
                                }
                                if ($model->load->loadStops[$i]->time_from) {
                                    $eta .= ' ' . Yii::$app->formatter->asTime($model->load->loadStops[$i]->available_from, "HH':'mm");
                                }
                            }
                        }
                        break;
                    case LoadStatus::COMPLETED:
                        $eta = 'REST ' . Yii::$app->formatter->asDate($model->load->loadStops[$j - 1]->arrived_date, 'E') . ' ' . Yii::$app->formatter->asTime($model->load->loadStops[$j - 1]->arrived_time_in, "HH':'mm");
                        break;
                }
                return $eta;
            }
        ]),
        'notes|title=Notes',
        new DataColumn([
            'title' => Yii::t('app', 'Phone'),
            'value' => function (DispatchAssignment $model) {
                return implode(' / ', [$model->driver ? ($model->driver->cell_phone ?: $model->driver->telephone) : '', $model->codriver ? ($model->codriver->cell_phone ?: $model->codriver->telephone) : '']);
            }
        ]),
        'created_by|title=Dispatcher|rel=createdBy.getFullName()',
        new DataColumn([
            'title' => Yii::t('app', 'Email'),
            'value' => function (DispatchAssignment $model) {
                return implode(' / ', [$model->driver ? $model->driver->email_address : '', $model->codriver ? $model->codriver->email_address : '']);
            }
        ]),
        new \common\widgets\DataTables\ActionColumn([
            'html' => function (DispatchAssignment $model) {
                return \yii\helpers\Html::a('<i class="fas fa-edit"></i>', ['edit', 'id' => $model->load_id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')]);
            }
        ])
    ],
]);
