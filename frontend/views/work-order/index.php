<?php

use common\widgets\DataTables\DataColumn;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Work Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="h3 mb-4 text-gray-800"><?= $this->title ?></h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="edit-form-toolbar work-orders__toolbar">
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top"
                    title="Update"><i class="fas fa-sync"></i></button>
            <button class="btn btn-link work-orders__toolbar__link js-ajax-modal" data-tooltip="tooltip"
                    data-placement="top" title="<?php echo Yii::t('app', 'Add') ?>"
                    data-url="<?php echo Url::toRoute(['update', 'id' => 0]) ?>"><i
                        class="fas fa-plus-square"></i>
            </button>
            <div class="field row" style="margin: 0">
                <div class="work-orders__field">
                    <label class="work-orders__label">Type:</label>
                    <select class="form-control" name="typeWorkOrders"
                            style="padding: 3px 5px">
                        <option selected>Trucks</option>
                        <option value="Trailers">Trailers</option>
                    </select>
                </div>
            </div>
            <div class="field row" style="margin: 0;">
                <div class="work-orders__field">
                    <label class="work-orders__label">Status:</label>
                    <select class="form-control" name="typeWorkOrders"
                            style="padding: 3px 5px; width: 120%">
                        <option selected>Open</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php echo \common\widgets\DataTables\Grid::widget([
            'id' => 'workOrders',
            'ajax' => Url::toRoute(['index', 'data' => 1]),
            'orderCellsTop' => true,
            'colReorder' => true,
            'stateSave' => true,
            'buttons' => [
                [
                    'extend' => 'colvis',
                    'columns' => ':gt(0)'
                ]
            ],
            'dom' => 'tp',
            'autoWidth' => false,
            'conditionalPaging' => true,
            'draw' => false,
            'columns' => [
                new DataColumn([
                    'title' => Yii::t('app', 'WO No'),
                    'orderable' => false,
                    'value' => function ($model) {
                        return '<button class="btn btn-link js-ajax-modal" data-url="' . Url::toRoute(['update', 'id' => $model->id]) . '">' . $model->id . '</button>';
                    },
                ]),
                new DataColumn([
                    'title' => Yii::t('app', 'PO No'),
                ]),
                new DataColumn([
                    'title' => Yii::t('app', 'Date'),
                ]),
                new DataColumn([
                    'title' => Yii::t('app', 'Unit No'),
                ]),
                new DataColumn([
                    'title' => Yii::t('app', 'Cost'),
                ]),
                new DataColumn([
                    'title' => Yii::t('app', 'Charged'),
                ]),
                new DataColumn([
                    'title' => Yii::t('app', 'Mem'),
                    'orderable' => false,
                ]),
                new DataColumn([
                    'title' => Yii::t('app', 'Serviced By'),
                ]),
                'description|orderable=false|title=Description'
            ]
        ]) ?>
    </div>
</div>
