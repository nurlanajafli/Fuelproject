<?php
/**
 * @var \yii\web\View $this
 * @var array $formConfig
 * @var \common\models\Driver $model
 */

use \yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

$this->beginBlock('tb');
?>
    <button class="btn btn-link dropdowwn-toggle" data-tooltip="tooltip"
            data-toggle="dropdown" data-placement="top"><i class="fas fa-plus mr-1"></i><i
                class="fas fa-caret-down"></i></button>
    <div class="dropdown-menu">
        <a href="#" class="dropdown-item js-ajax-modal"
           data-url="<?= Url::toRoute(['driver-adjustment/create', 'id' => $model->id]) ?>">
            <?= Yii::t('app', 'Adjustments') ?>
        </a>
    </div>
<?php
$this->endBlock();
$this->beginBlock('form');
$form = ActiveForm::begin($formConfig);
?>
    <div class="row">
        <div class="col-5">
            <div class="form-fieldset">
                <div class="row">
                    <div class="col-sm-6 text-right">
                        <label>
                            Non Taxable <input type="checkbox" checked disabled>
                        </label>
                    </div>
                    <div class="col-sm-6 text-right">
                        <label>
                            Exempt 1099 <input type="checkbox">
                        </label>
                    </div>
                </div>
                <?php echo $form->field($model, 'state_id', [
                    'options' => ['class' => 'form-group row'],
                    'template' => '<div class="col-sm-5 text-right">{label}</div><div class="col-sm-7">{input}{error}</div>',
                    'inputOptions' => ['id' => 'tax-state_id']
                ])->widget(\common\widgets\tdd\Dropdown::class, [
                    'grid' => [
                        'dataProvider' => new \yii\data\ActiveDataProvider([
                            // TODO: hardcode ("US" is constant)
                            'query' => \common\models\State::find()->where(['country_code' => 'US']),
                            'pagination' => false
                        ]),
                        'columns' => [
                            'id|visible=false',
                            'state_code|title=Abb',
                            'state|title=Name'
                        ],
                        'order' => [[1, 'asc']]
                    ]
                ])->label(Yii::t('app', 'State/Province')) ?>
                <?php echo $form->field($model, 'pay_frequency', [
                    'options' => ['class' => 'form-group row'],
                    'template' => '<div class="col-sm-5 text-right">{label}</div><div class="col-sm-7">{input}{error}</div>',
                    'inputOptions' => ['id' => 'tax-pay_frequency']
                ])->dropdownList(\common\enums\PayFrequency::getUiEnums(), ['prompt' => '', 'class' => 'custom-select']) ?>
            </div>
        </div>
        <div class="col-7">
            <div class="form-fieldset">
                <span class="form-legend"><?php echo Yii::t('app', 'Automatic Adjustments Per Pay Period') ?></span>
                <?php
                echo common\widgets\DataTables\Grid::widget([
                    'id' => 'automatic-adjustments',
                    'ajax' => Url::toRoute(['driver-adjustment/index', 'id' => $model->id]),
                    'columns' => [
                        'id|visible=false',
                        'driver_id|visible=false',
                        'adj_code|title=Adj Code',
                        'post_to|title=Post To',
                        'pct|title=Pct',
                        'flat|title=Flat',
                        'mile|title=Mile',
                        'empr|title=Empr'
                    ],
                    'template' => Yii::$app->params['dt']['templates'][0],
                    'info' => false,
                    'attributes' => ['style' => 'margin:0!important;'],
                    'searching' => false,
                    'lengthChange' => false,
                    'autoWidth' => false,
                    'foot' => false,
                    'colReorder' => null,
                    'ordering' => false,
                    'paging' => false,
                    'dom' => 't',
                    'doubleClick' => ['modal', Url::toRoute(['driver-adjustment/update', 'id' => 'col:0', 'driverId' => 'col:1'])]
                ]);
                ?>
            </div>
        </div>
    </div>
<?php
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'dialogCssClass' => 'modal-xl',
    'title' => Yii::t('app', 'Setup Filing Status & Adjustments'),
    'toolbar' => $this->blocks['tb'],
    'content' => $this->blocks['form']
]);

