<?php
/**
 * /var/www/html/frontend/runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22
 *
 * @package default
 */

use common\models\TrackingLog;
use common\models\Unit;
use common\widgets\DataTables\ActionColumn;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Units');
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo Yii::t('app', 'Units') ?></h1>
  </div>
<?php echo Grid::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        new DataColumn([
            'attribute' => 'driver_id',
            'value' => function ($model) {
                /** @var Unit $model */
                return ($rel = $model->driver) ? $rel->getFullName() : '';
            }
        ]),
        new DataColumn([
            'attribute' => 'co_driver_id',
            'value' => function ($model) {
                /** @var Unit $model */
                return ($rel = $model->coDriver) ? $rel->getFullName() : '';
            }
        ]),
        new DataColumn([
            'attribute' => 'truck_id',
            'value' => function ($model) {
                /** @var Unit $model */
                return ($rel = $model->truck) ? $rel->truck_no : '';
            }
        ]),
        new DataColumn([
            'attribute' => 'trailer_id',
            'value' => function ($model) {
                /** @var Unit $model */
                return ($rel = $model->trailer) ? $rel->trailer_no : '';
            }
        ]),
        new DataColumn([
            'attribute' => 'trailer_2_id',
            'value' => function ($model) {
                /** @var Unit $model */
                return ($rel = $model->trailer2) ? $rel->trailer_no : '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Off'),
            'filterable' => true,
            'value' => function ($model) {
                /** @var Unit $model */
                return $model->office ? $model->office->_label : '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Active'),
            'filterable' => true,
            'value' => function ($model) {
                /** @var Unit $model */
                return Yii::$app->formatter->asDate($model->active, 'short');
            }
        ]),
        'status',
        new DataColumn([
            'title' => Yii::t('app', 'Destination'),
            'value' => function ($model) {
                /** @var Unit $model */
                $loc = TrackingLog::getLatestUnitLocation($model->id);
                return $loc ? $loc->company_name : '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'City'),
            'value' => function ($model) {
                /** @var Unit $model */
                $loc = TrackingLog::getLatestUnitLocation($model->id);
                return $loc ? $loc->city : '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'St'),
            'value' => function ($model) {
                /** @var Unit $model */
                $loc = TrackingLog::getLatestUnitLocation($model->id);
                return $loc ? $loc->state->state_code : '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Zip'),
            'value' => function ($model) {
                /** @var Unit $model */
                $loc = TrackingLog::getLatestUnitLocation($model->id);
                return $loc ? $loc->zip : '';
            }
        ]),
        'notes',
        new ActionColumn([
            'html' => function ($model) {
                return Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')]);
            }
        ]),
    ],
    'toolbarHtml' => $this->render('_toolbarIndex')
]);