<?php

use common\widgets\DataTables\Grid;
use yii\data\ActiveDataProvider;

/**
 *
 * @var yii\web\View $this
 * @var ActiveDataProvider $dataProvider
 */

echo Grid::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'truck_no',
        'type',
        'year',
        'make',
        'model',
        'status',
    ],
    'toolbarHtml' => '<a class="btn btn-sm btn-secondary" href="' . \yii\helpers\Url::toRoute('truck/create') . '"><i class="fas fa fa-plus"></i> ' . Yii::t('app', 'New') . '</a>'
]);
