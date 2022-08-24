<?php
/**
 * @var \yii\web\View $this
*/

use common\widgets\DataTables\Column;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Bill');
$bill = new \common\models\Bill();
?>
<h1 class="h3 mb-4 text-gray-800"><?= $this->title ?></h1>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <div class="edit-form-toolbar">
      <button class="btn btn-link js-ajax-modal" data-url="<?= Url::toRoute('create') ?>" data-tooltip="tooltip" data-placement="top" title="Add"><i class="fas fa-plus-square"></i></button>
      <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Search"><i class="fas fa-search"></i></button>
      <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Light"><i class="far fa-lightbulb"></i></button>
      <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Down"><i class="fas fa-arrow-down"></i></button>
    </div>
  </div>
  <div class="card-body">
    <div class="table table-responsive bill-grid">
        <?= \common\widgets\DataTables\Grid::widget([
            'ajax' => Url::toRoute('data'),
            'template' => '{table}',
            'columns' => [
                // id
                new Column([
                    'visible' => false,
                ]),
                new Column([
                    'title' => '/',
                    'orderable' => false,
                    'searchable' => false,
                    'className' => 'dt-control',
                ]),
                new Column([
                    'title' => $bill->getAttributeLabel('bill_no'),
                ]),
                new Column([
                    'title' => Yii::t('app', 'Billed'),
                ]),
                new Column([
                    'title' => Yii::t('app', 'Due'),
                ]),
                new Column([
                    'title' => Yii::t('app', 'Company Name'),
                ]),
                new Column([
                    'title' => Yii::t('app', 'Off'),
                ]),
                new Column([
                    'title' => Yii::t('app', 'Account'),
                ]),
                new Column([
                    'title' => Yii::t('app', 'Amount'),
                ]),
                new Column([
                    'title' => Yii::t('app', 'Balance'),
                ]),
                new Column([
                    'title' => $bill->getAttributeLabel('memo'),
                ]),
            ],
            'id' => 'bill-listing',
            'cssClass' => 'w-100',
            'stateSave' => false,
            'orderCellsTop' => true,
            'autoWidth' => true,
            'dom' => 'tp',
            'doubleClick' => ['modal', Url::toRoute(['update', 'id' => 'col:0'])],
            'rowAttributes' => 'billsRowAttrs',
        ]) ?>
    </div>
  </div>
</div>