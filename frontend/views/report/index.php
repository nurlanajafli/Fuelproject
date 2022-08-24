<?php
use common\widgets\DataTables\ActionColumn;
use common\widgets\DataTables\ActiveColumn;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use yii\helpers\Html;
use yii\helpers\Url;


$this->title = Yii::t('app', 'Report list');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo Yii::t('app', 'Report list') ?></h1>
    </div>
<?php
echo Grid::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'type',
        'status',
        new DataColumn([
            'attribute' => 'driver',
            'value' => function ($model) {
                if ($rel = $model->driver) {
                    return $rel->_label;
                }
                return '';
            },
            'filterable' => true,
        ]),
        new DataColumn([
            'attribute' => 'truck',
            'value' => function ($model) {
                if ($rel = $model->truck) {
                    return $rel->_label;
                }
                return '';
            },
            'filterable' => true,
        ]),
        new DataColumn([
            'attribute' => 'trailer',
            'value' => function ($model) {
                if ($rel = $model->trailer) {
                    return $rel->_label;
                }
                return '';
            },
            'filterable' => true,
        ]),
        'def_level',
        'fuel_level',
        'mileage',
        new ActiveColumn([
            'attributes' => ['id', 'type','status'],
            'filterable' => true,
        ]),
        new ActionColumn([
            'html' => function ($model) {
                return Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')]);
                 //   Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], ['data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'), 'class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Delete')]);
            }
        ])
    ],
]);