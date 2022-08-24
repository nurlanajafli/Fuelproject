<?php

use common\widgets\DataTables\ActionColumn;
use common\widgets\DataTables\ActiveColumn;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Drivers');
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= Yii::t('app', 'Drivers') ?></h1>
  </div>
<?php
echo Grid::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'last_name',
        'first_name',
        'city',
        'telephone',
        'cell_phone',
        'email_address',
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
        new ActiveColumn([
            'attributes' => 'marked_as_down',
            'filterable' => true,
        ]),
        'status',
        'notes',
        new ActionColumn([
            'html' => function ($model) {
                return Html::a('<i class="fas fa-binoculars"></i>', ['view', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'View')])
                    . Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')]);
            }
        ])
    ],
    'toolbarHtml' => '<a class="btn btn-sm btn-secondary" href="' . \yii\helpers\Url::toRoute('create') . '"><i class="fas fa fa-plus"></i> ' . Yii::t('app', 'New') . '</a>'
]);
