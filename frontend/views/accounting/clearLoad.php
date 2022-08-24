<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\Load $model
 * @var array $formConfig
 * @var string|false $prev
 * @var string|false $next
 */

use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use common\widgets\ModalForm\ModalForm;
use yii\bootstrap4\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use common\models\Document;

$this->beginBlock('tb');
?>
<div class="js-clear-load-tb">
    <button class="btn btn-link js-set" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', '') ?>">
        <i class="fas fa-check"></i>
    </button>
    <button class="btn btn-link js-undo" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', '') ?>">
        <i class="fas fa-undo"></i>
    </button>
    <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Info"><i class="fas fa-info"></i>
    </button>
    <button class="btn btn-link js-unset" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', '') ?>">
        <i class="fas fa-times"></i>
    </button>
</div>
<?php
$this->endBlock();
$this->beginBlock('content');
$form = ActiveForm::begin(\yii\helpers\ArrayHelper::merge($formConfig, ['options' => ['data-cb' => 'loadClearChangeLabels']]));
?>
    <div class="form-fieldset">
        <div class="row">
            <div class="col-6">
                <div class="field row">
                    <div class="col-2">
                        <label>Carrier</label>
                    </div>
                    <div class="col-10"><span class="form-readonly-text">DM WORLD TRANSPORTATION LLC</span></div>
                </div>
                <div class="field row">
                    <div class="col-2">
                        <label><?= Yii::t('app', 'Truck') ?></label>
                    </div>
                    <div class="col-10"><span
                                class="form-readonly-text"><?= $model->dispatchAssignment->truck->truck_no ?></span>
                    </div>
                </div>
            </div>
            <div class="col-4 js-checkbox-group-1">
                <div class="field row">
                    <div class="col-4">
                        <label>Dispatches</label>
                    </div>
                    <div class="col-8"><span class="form-readonly-text">1</span></div>
                </div>
                <?php
                $label = $model->getAttributeLabel('scans');
                $count = $model->getDocuments()->count();
                echo $form->field(
                    $model,
                    'scans',
                    [
                        'options' => ['class' => 'field row'],
                        'labelOptions' => ['class' => 'custom-control-label', 'style' => 'font-size: 0;']
                    ])->checkbox([
                        'template' => "<div class=\"col-4\"><label>$label</label></div><div class=\"col-4\"><span class=\"form-readonly-text\">$count</span></div><div class=\"col-4\"><div class=\"custom-control custom-checkbox\">{input}{label}</div></div>",
                        'data-init' => $model->scans + 0
                    ]);
                echo \yii\bootstrap4\Html::hiddenInput('_btn', 'save');
                ?>
            </div>
            <div class="col-2 border-left-secondary" data-n0="<?php echo Yii::t('app', 'No') ?>" data-n1="<?php echo Yii::t('app', 'Yes') ?>">
                <div class="text-center mb-1">
                    <label class="mb-0"><?= Yii::t('app', 'Load Cleared?') ?></label>
                    <p class="form-readonly-text"><?php echo Yii::t('app', $model->loadcleared ? 'Yes' : 'No') ?></p>
                </div>
                <div class="text-center">
                    <label class="mb-0"><?= Yii::t('app', 'Backup Cleared?') ?></label>
                    <p class="form-readonly-text"><?php echo Yii::t('app', $model->backupcleared ? 'Yes' : 'No') ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="js-load-cleaning">
        <div class="row">
            <div class="col-6">
                <div class="field">
                    <p><?= Yii::t('app', 'Now Clearing') ?>:</p><span
                            class="form-readonly-text"><?= Yii::t('app', 'Backup') ?></span>
                </div>
                <?php
                $drivers = [];
                if ($model->dispatchAssignment->driver) {
                    $drivers[] = [
                        'id' => $model->dispatchAssignment->driver->id,
                        'cleared' => false,
                        'date' => '',
                        'name' => $model->dispatchAssignment->driver->getFullName(),
                        'pay_code' => $model->dispatchAssignment->pay_code
                    ];
                }
                if ($model->dispatchAssignment->codriver) {
                    $drivers[] = [
                        'id' => $model->dispatchAssignment->codriver->id,
                        'cleared' => false,
                        'date' => '',
                        'name' => $model->dispatchAssignment->codriver->getFullName(),
                        'pay_code' => 'CoDriver'
                    ];
                }
                echo Grid::widget([
                    'dataProvider' => new ArrayDataProvider([
                        'allModels' => $drivers
                    ]),
                    'dom' => 't',
                    'searching' => false,
                    'paging' => true,
                    'ordering' => false,
                    'info' => false,
                    'lengthChange' => false,
                    'autoWidth' => false,
                    'cssClass' => 'w-100',
                    'attributes' => ['style' => 'margin:0 !important;'],
                    'template' => Yii::$app->params['dt']['templates'][0],
                    'colReorder' => null,
                    'foot' => false,
                    'columns' => [
                        'id|visible=false',
                        new DataColumn([
                            'title' => Yii::t('app', 'Clr'),
                            'value' => function ($data) {
                                $checked = $data->cleared ? ' checked="checked"' : '';
                                return '<input class="editor-active"' . $checked . ' type="checkbox" onclick="return false;">';
                            }
                        ]),
                        'date|title=Date',
                        'name|title=Name',
                        'pay_code|title=Pay Code'
                    ],
                ]) ?>
            </div>
            <div class="col-3">
                <div class="field">
                    <p><?= Yii::t('app', 'Scans') ?></p>
                </div>
                <?= Grid::widget([
                    'dom' => 'tp',
                    'searching' => false,
                    'paging' => true,
                    'ordering' => false,
                    'info' => false,
                    'lengthChange' => false,
                    'autoWidth' => false,
                    'cssClass' => 'w-100 js-documents-table',
                    'attributes' => ['style' => 'margin:0 !important;'],
                    'template' => Yii::$app->params['dt']['templates'][0],
                    'colReorder' => null,
                    'foot' => false,
                    'ajax' => Url::toRoute(['document/data', 'type' => 'load', 'id' => $model->id]),
                    'columns' => [
                        'id|visible=false',
                        'code|title=Type',
                        'description',
                        (new Document())->getImageAttribute() . '|visible=false',
                        'created_at|visible=false'
                    ],
                    'dataProvider' => new ArrayDataProvider(['modelClass' => Document::class])
                ]) ?>
            </div>
            <div class="col-3">
                <div class="field">
                    <p>&nbsp;</p>
                </div>
                <div class="border">
                    <table class="table table-sm text-center border-0">
                        <thead>
                        <tr>
                            <td>Required Backup</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="border-0 form-readonly-text">Bill of Lading</td>
                        </tr>
                        <tr>
                            <td class="border-0 form-readonly-text">Rate Confirmations</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-6 clearfix">
                <div class="float-right">
                    <button class="btn btn-primary" data-tooltip="tooltip" data-placement="top" title="Save"><i
                                class="fas fa-save"></i></button>
                    <button class="btn btn-primary" data-tooltip="tooltip" data-placement="top" title="Save"><i
                                class="far fa-save"></i></button>
                    <button class="btn btn-primary" data-tooltip="tooltip" data-placement="top" title="Save"><i
                                class="fas fa-star"></i></button>
                    <button class="btn btn-primary" data-tooltip="tooltip" data-placement="top" title="Star"><i
                                class="far fa-star"></i></button>
                </div>
            </div>
            <div class="col-3 clearfix">
                <div class="float-right">
                    <button class="btn btn-primary js-ajax-modal"
                            data-url="<?= Url::toRoute(['document/index', 'type' => 'load', 'id' => $model->id]) ?>"
                            data-tooltip="tooltip" data-placement="top" title="Image">
                        <i class="fas fa-image"></i></button>
                </div>
            </div>
            <div class="col-3 clearfix">
                <div class="float-right">
                    <?php
                    if ($prev)
                        echo '<a class="btn btn-primary d-none" data-tooltip="tooltip" data-placement="top" title="'
                            . Yii::t('app', 'Left') . '" href="' . $prev . '"><i class="fas fa-arrow-left"></i></a>';
                    if ($next)
                        echo '<a class="btn btn-primary d-none" data-tooltip="tooltip" data-placement="top" title="'
                            . Yii::t('app', 'Right') . '" href="' . $next . '"><i class="fas fa-arrow-right"></i></a>';
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
ActiveForm::end();
$this->endBlock();
echo ModalForm::widget([
    'title' => Yii::t('app', 'Clear Load') . sprintf(' %s - %s', $model->id, Yii::t('app', 'TL')),
    'toolbar' => $this->blocks['tb'],
    'content' => $this->blocks['content'],
    'cssClass' => 'clear-load-modal',
    'dialogCssClass' => 'modal-xl',
    'saveButton' => false
]);