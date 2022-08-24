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

$this->title = Yii::t('app', 'Trucks');
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo Yii::t('app', 'Trucks') ?></h1>
  </div>
<?php
echo Grid::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'truck_no',
        'type',
        'year',
        'make',
        'model',
        'license',
        'vin',
        'in_svc|date=' . Yii::$app->params['formatter']['date']['short'],
        'status',
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
            'attributes' => ['out_of_service', function ($model) {
                /** @var \common\models\Truck $model */
                if (!$model->is_down) {
                    return false;
                }
                if (!$model->returns_to_service) {
                    return true;
                }
                $d = new DateTime($model->returns_to_service);
                $now = new DateTime('NOW', $d->getTimezone());
                return $d > $now;
            }],
            'filterable' => true,
        ]),
        new ActionColumn([
            'html' => function ($model) {
                return Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')]) .
                    Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], ['data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'), 'class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Delete')]);
            }
        ])
    ],
    'toolbarHtml' => '<a class="btn btn-sm btn-secondary" href="' . \yii\helpers\Url::toRoute('create') . '"><i class="fas fa fa-plus"></i> ' . Yii::t('app', 'New') . '</a>'
]);
