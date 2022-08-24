<?php
/**
 * @var \common\components\View $this
 * @var \common\models\WorkOrderService $model
 * @var array $formConfig
 * @var callable $field
 */

use common\models\ServiceCode;
use common\models\Vendor;
use common\widgets\tdd\Dropdown;
use yii\bootstrap4\ActiveForm;
use common\widgets\DataTables\DataColumn;

$this->beginBlock('toolbar');
?>
    <div class="card-header">
        <div class="edit-form-toolbar work-orders__toolbar-modal">
            <button class="btn btn-link work-orders__toolbar__link" data-tooltip="tooltip" data-placement="top"
                    title="New Service" data-toggle="modal" data-target="#parts-used-modal"><i
                        class="fas fa-plus-square"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="???"><i
                        class="fas fa-save"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Print"><i
                        class="fas fa-print"></i></button>
            <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Complete"><i
                        class="fas fa-check"></i></button>
        </div>
    </div>
<?php
$this->endBlock();
$this->beginBlock('form');
$form = ActiveForm::begin($formConfig);
?>
    <div class="row">
        <div class="col-5">
            <div class="work-orders__settings setting" style="padding: 20px 40px">
                <div class="row">
                    <div class="col-12">
                        <label class="setting__fields--label">
                            <p>Service No</p>
                            <div>1576</div>
                        </label>
                        <?= $field($form, $model, 'service_date')->textInput(['type' => 'date']) ?>
                        <?= $field($form, $model, 'service_code')->widget(Dropdown::class, [
                            'items' => ServiceCode::find()->all(),
                            'class' => ServiceCode::class,
                            'lookupColumnIndex' => 0,
                            'displayColumnIndex' => 0,
                            'grid' => [
                                'columns' => [
                                    'id|title=Code',
                                    'description|title=Description',
                                ],
                                'order' => [[0, 'asc']]
                            ],
                        ]) ?>
                        <?= $field($form, $model, 'vendor_id')->widget(Dropdown::class, [
                            'items' => Vendor::find()->all(),
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
                            ],
                        ]) ?>
                        <label class="setting__fields--label">
                            <p>Total Cost</p>
                            <div>500.00</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-7">
            <div class="work-orders__settings p-0"><span class="form-legend">Service Description</span>
                <div>
		    <?php echo $form->field($model, 'description', ['options' => ['tag' => false], 'template' => '{input}{error}'])->textarea(['class' => 'work-orders__textarea']); ?>
                </div>
            </div>
        </div>
        <div class="col-12" style="margin: 20px 0 0 0">
            <div class="work-orders__table">
                <div class="work-orders__table--title">Parts / Labor / Miscellaneous</div>
                <hr>
                <div class="table table-responsive">
                    <table class="table table-bordered js-datatable" id="equipmentServiceRes" cellspacing="0"
                           data-var="equipmentServiceRes">
                        <thead>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'id' => 'equipment-service',
    'dialogCssClass' => 'modal-xl',
    'title' => Yii::t('app', 'Equipment Service'),
    'beforeBodyHtml' => $this->blocks['toolbar'],
    'content' => $this->blocks['form'],
    'headerCssClass' => '',
    'footerCssClass' => '',
]);
