<?php
/**
 * /var/www/html/frontend/runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22
 *
 * @package default
 */

use common\widgets\DataTables\ActionColumn;
use common\widgets\DataTables\ActiveColumn;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Trailers');
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo Yii::t('app', 'Trailers') ?></h1>
  </div>
<?php
echo Grid::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'trailer_no',
        'type',
        'year',
        'make',
        'model',
        'tare',
        'status',
        'serial',
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
            'attributes' => ['out_of_service', 'marked_as_down'],
            'filterable' => true,
        ]),
        new ActionColumn([
            'html' => function ($model) {
                return Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')]) .
                    Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], ['data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'), 'class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Delete')]);
            }
        ])
    ],
    'toolbarHtml' => '<a class="btn btn-sm btn-secondary" href="' . Url::toRoute('create') . '"><i class="fas fa fa-plus"></i> ' . Yii::t('app', 'New') . '</a>'
]);
