<?php

use common\widgets\DataTables\Grid;
use common\widgets\ModalForm\ModalForm;

/**
 * @var string $provider
 */

$this->beginBlock('content'); ?>

<?= Grid::widget([
    'ajax' => \yii\helpers\Url::toRoute(['ajax-setup-product-codes', 'provider' => $provider, 'data' => 1]),
    'dom' => 'tp',
    'searching' => false,
    'paging' => false,
    'ordering' => false,
    'id' => 'setup-product-codes-table',
    'stateSave' => false,
    'info' => false,
    'lengthChange' => false,
    'autoWidth' => false,
    'cssClass' => 'js-documents-table',
    'attributes' => ['style' => 'margin:0 !important;'],
    'template' => Yii::$app->params['dt']['templates'][0],
    'colReorder' => null,
    'foot' => false,
    'columns' => [
        'id|visible=false',
        'description',
        'oo_acct|title=O/O Acct',
        'cd_acct|title=C/D Acct',
        'fee_amt',
        'fee_acct',
    ],
    'doubleClick' => ['modal', \yii\helpers\Url::toRoute(['update-product-code', 'id' => 'col:0'])],
]) ?>

<?php $this->endBlock();

echo ModalForm::widget([
    'content' => $this->blocks['content'],
    'cssClass' => 'modal--no-scroll',
    'dialogCssClass' => 'modal-full',
    'saveButton' => false,
    'title' => Yii::t('app', 'Setup Product Codes'),
    'toolbar' => $this->blocks['tb']
]);