<?php
/**
 * @var \common\components\View $this
 * @var \common\models\WorkOrder $model
 * @var array $formConfig
 * @var callable $field
 */

use common\enums\WorkOrderStatus;
use common\enums\WorkOrderType;
use common\models\Trailer;
use common\models\Truck;
use common\models\Vendor;
use common\widgets\DataTables\DataColumn;
use common\widgets\tdd\Dropdown;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->beginBlock('form');
$form = ActiveForm::begin(ArrayHelper::merge($formConfig, ['fieldConfig' => ['errorOptions' => ['class' => 'help-block']]]));
?>
    <div class="row">
        <div class="col-7">
            <div class="work-orders__settings setting">
                <div class="setting__info">
                    <div class="setting__info-item">
                        <div class="setting__info-item--title">
                            <p><?= $model->getAttributeLabel('id') ?></p>
                        </div>
                        <div class="setting__info-item--val">
                            <p><?= $model->id ?></p>
                        </div>
                    </div>
                    <div class="setting__info-item">
                        <div class="setting__info-item--title">
                            <p><?= Yii::t('app', 'Completed') ?></p>
                        </div>
                        <div class="setting__info-item--val">
                            <p><?= Yii::t('app', ($model->status == WorkOrderStatus::COMPLETED) ? 'Yes' : 'No') ?></p>
                        </div>
                    </div>
                    <div class="setting__info-item">
                        <div class="setting__info-item--title">
                            <p><?= Yii::t('app', 'Total') ?></p>
                        </div>
                        <div class="setting__info-item--val">
                            <p><?= Yii::$app->formatter->asDecimal($model->total) ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <?= $field($form, $model, 'order_date')->textInput(['type' => 'date']) ?>
                        <?= $field($form, $model, 'order_type')->dropdownList(WorkOrderType::getUiEnums(), ['class' => 'custom-select']) ?>
                        <?= $field($form, $model, 'truck_id')->widget(\common\widgets\tdd\Dropdown::class, [
                            'items' => Truck::find()->all(),
                            'class' => Truck::class,
                            'lookupColumnIndex' => 0,
                            'displayColumnIndex' => 1,
                            'grid' => [
                                'columns' => [
                                    'id|visible=false',
                                    'truck_no|title=Truck No',
                                    'type|title=Type',
                                ],
                                'order' => [[1, 'asc']]
                            ]
                        ]) ?>
                        <?= $field($form, $model, 'trailer_id')->widget(Dropdown::class, [
                            'items' => Trailer::find()->all(),
                            'class' => Trailer::class,
                            'lookupColumnIndex' => 0,
                            'displayColumnIndex' => 1,
                            'grid' => [
                                'columns' => [
                                    'id|visible=false',
                                    'trailer_no|title=Trailer No',
                                    'type|title=Type',
                                ],
                                'order' => [[1, 'asc']]
                            ]
                        ]) ?>
                    </div>
                    <div class="col-6">
                        <?= $field($form, $model, 'vendor_id')->widget(Dropdown::class, [
                            'items' => Vendor::find()->joinWith('state')->all(),
                            'class' => Vendor::class,
                            'lookupColumnIndex' => 0,
                            'displayColumnIndex' => 1,
                            'grid' => [
                                'columns' => [
                                    'id|visible=false',
                                    'name|title=Name',
                                    'city|title=City',
                                    'state_id|rel=state.state_code|title=ST',
                                    new DataColumn([
                                        'title' => Yii::t('app', 'Type'),
                                        'value' => function ($vendor) {
                                            return Yii::t('app', 'Vendor');
                                        },
                                    ])
                                ],
                                'order' => [[1, 'asc']]
                            ]
                        ]) ?>
                        <?= $field($form, $model, 'authorized_by')->textInput() ?>
                        <?= $field($form, $model, 'odometer')->textInput(['type' => 'number']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="work-orders__settings p-0"><span
                        class="form-legend"><?= Yii::t('app', 'Description of Problem or Condition') ?></span>
                <div style="height: 100%">
                    <?php
                    echo $form->field($model, 'description', ['options' => ['tag' => false], 'template' => '{input}{error}'])->textarea(['class' => 'work-orders__textarea']);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-12" style="margin: 20px 0 0 0">
            <div class="work-orders__table">
                <div class="work-orders__table--title"><?= Yii::t('app', 'Services performed on this work order') ?></div>
                <hr>
                <?= \common\widgets\DataTables\Grid::widget([
                    'id' => 'workOrderRes',
                    'ajax' => Url::toRoute(['index-service', 'id' => $model->id]),
                    'orderCellsTop' => true,
                    'colReorder' => true,
                    'stateSave' => true,
                    'columns' => [
                        new DataColumn(['title' => Yii::t('app', 'Svc No')]),
                        new DataColumn(['title' => Yii::t('app', 'Date')]),
                        new DataColumn(['title' => Yii::t('app', 'Code')]),
                        new DataColumn(['title' => Yii::t('app', 'Description')]),
                        new DataColumn(['title' => Yii::t('app', 'Cost')]),
                        new DataColumn(['title' => Yii::t('app', 'Comp')]),
                        new DataColumn(['title' => Yii::t('app', 'Notes')]),
                    ],
                    'buttons' => [['extend' => 'colvis', 'columns' => ':gt(0)']],
                    'dom' => 'tp',
                    'autoWidth' => false,
                    'conditionalPaging' => true,
                    'draw' => false,
                ]) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php
$this->endBlock();
$this->beginBlock('toolbar');
?>
    <div class="card-header">
        <div class="edit-form-toolbar work-orders__toolbar-modal">
            <button class="btn btn-link js-ajax-modal" data-tooltip="tooltip" data-placement="top"
                    title="<?= Yii::t('app', 'New Service') ?>"
                    data-url="<?= Url::toRoute(['update-service', 'parentId' => $model->id, 'id' => 0]) ?>"><i
                        class="fas fa-plus-square"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Import"><i
                        class="fas fa-cloud-download-alt"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Print"><i
                        class="fas fa-print"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Memorize"><i
                        class="fas fa-lightbulb"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Take Equipment Down"><i
                        class="fas fa-briefcase"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Create Purchase Order"><i
                        class="fas fa-file-medical"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Scans"><i
                        class="fas fa-image"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Cancel"><i
                        class="fas fa-eraser"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Complete"><i
                        class="fas fa-check"></i></button>
        </div>
    </div>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'dialogCssClass' => 'modal-xl',
    'headerCssClass' => '',
    'title' => Yii::t('app', 'Work Order'),
    'content' => $this->blocks['form'],
    'beforeBodyHtml' => $this->blocks['toolbar'],
    'id' => 'work-orders-modal',
    'footerCssClass' => '',
]);
