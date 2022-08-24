<?php
/**
 * /var/www/html/frontend/runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */


use common\enums\PayrollAdjustmentClass;
use common\enums\PayrollAdjustmentType;
use common\models\Account;
use common\models\State;
use common\widgets\DataTables\DataColumn;
use dmstr\bootstrap\Tabs;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var common\models\PayrollAdjustmentCode $model
 * @var yii\widgets\ActiveForm $form
 */

$prompt = Yii::t('app', 'Select');
?>

<div class="payroll-adjustment-code-form">

    <?php $form = ActiveForm::begin([
        'id' => 'PayrollAdjustmentCode',
        'layout' => 'horizontal',
        'enableClientValidation' => true,
        'errorSummaryCssClass' => 'error-summary alert alert-danger',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                //'offset' => 'col-sm-offset-4',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]); ?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>

            <!-- attribute adj_type -->
            <?php echo $form->field($model, 'adj_type')->hiddenInput()->label(false); ?>

            <!-- attribute code -->
            <?php echo $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

            <!-- attribute account -->
            <!--            --><?php //echo $form->field($model, 'post_to')->textInput(['maxlength' => true]) ?>





            <?= $form->field($model, 'post_to_carrier_id')->widget(\common\widgets\tdd\Dropdown::class, [
                'displayColumnIndex' => 2,
                'grid' => [
                    'dataProvider' => new \yii\data\SqlDataProvider([
                        'sql' => \common\models\Carrier::find()
                            ->select(new \yii\db\Expression("t0.id,t0.name,t0.address_1,t0.address_2,t0.city,state_code,0 AS type"))
                            ->alias('t0')
                            ->leftJoin(State::tableName(), 't0.state_id=' . State::tableName() . '.id')
                            ->union(\common\models\Driver::find()
                                ->select(new \yii\db\Expression("t0.id,last_name||', '||first_name AS name,t0.address_1,t0.address_2,t0.city,state_code,1 AS type"))
                                ->alias('t0')
                                ->leftJoin(State::tableName(), 't0.state_id=' . State::tableName() . '.id')
                            //->where(['<>', 't0.id', $model->id])
                            )
                            ->union(\common\models\Vendor::find()
                                ->select(new \yii\db\Expression("t0.id,t0.name,t0.address_1,t0.address_2,t0.city,state_code,2 AS type"))
                                ->alias('t0')
                                ->leftJoin(State::tableName(), 't0.state_id=' . State::tableName() . '.id')
                            )->createCommand()->getRawSql()
                    ]),
                    'columns' => [
                        'type|visible=false|searchable=false',
                        'id|visible=false|searchable=false',
                        'name|title=Name',
                        new DataColumn([
                            'title' => Yii::t('app', 'Address'),
                            'value' => function ($model) {
                                return $model->address_1 ? $model->address_1 : $model->address_2;
                            }
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'City'),
                            'attribute' => 'city'
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'St'),
                            'attribute' => 'state_code',
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Type'),
                            'attribute' => 'type',
                            'visible' => false
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Type'),
                            'value' => function ($model) {
                                $message = '';
                                switch ($model->type) {
                                    case 0:
                                        $message = 'Carrier';
                                        break;
                                    case 1:
                                        $message = 'Driver';
                                        break;
                                    case 2:
                                        $message = 'Vendor';
                                        break;
                                }
                                return Yii::t('app', $message);
                            }
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Inactive'),
                            'value' => function ($model) {
                                return '<input type="checkbox" class="editor-active" onclick="return false;">';
                            },
                            'className' => 'dt-body-center text-center',
                            'searchable' => false,
                            'orderable' => true,
                        ]),
                    ],
                    'order' => [[7, 'asc']]
                ],
                'destAttribute' => [['post_to_carrier_id' => 0, 'post_to_driver_id' => 1, 'post_to_vendor_id' => 2], 0, 1],
                'displayColumnIndex' => 2
            ])->label(Yii::t('app', 'Post To')) ?>


            <!-- attribute account -->
            <?php echo $form->field($model, 'account')->dropDownList(ArrayHelper::map(Account::find()->all(), 'account', '_label'), ['prompt' => 'Select']) ?>


            <!-- attribute adj_class -->
        <div id="loc-tax-adj-block"
             style="<?= $model->adj_type == PayrollAdjustmentType::NON_TAX_ADJUSTMENT ? 'display: none' : 'display: block' ?>">
            <?= $form
                ->field($model, 'adj_class')
                ->dropDownList(PayrollAdjustmentClass::getByType(PayrollAdjustmentType::LOCAL_INCOME_TAX), [
                    'prompt' => 'Select',
                    'id' => 'loc-tax-adj',
                    'disabled' => $model->adj_type == PayrollAdjustmentType::NON_TAX_ADJUSTMENT,
                    'style' => $model->adj_type == PayrollAdjustmentType::NON_TAX_ADJUSTMENT ? 'display: none' : 'display: block',
                ])
            ?>
        </div>

        <!-- attribute adj_class -->
        <div id="non-tax-adj-block"
             style="<?= $model->adj_type != PayrollAdjustmentType::NON_TAX_ADJUSTMENT ? 'display: none' : 'display: block' ?>">
            <?= $form
                ->field($model, 'adj_class')
                ->dropDownList(PayrollAdjustmentClass::getByType(PayrollAdjustmentType::NON_TAX_ADJUSTMENT), [
                    'prompt' => 'Select',
                    'id' => 'non-tax-adj',
                    'disabled' => $model->adj_type != PayrollAdjustmentType::NON_TAX_ADJUSTMENT,
                    'style' => $model->adj_type != PayrollAdjustmentType::NON_TAX_ADJUSTMENT ? 'display: none' : 'display: block'
                ])
            ?>
        </div>

        <!-- attribute based_on -->
        <div id="based_on" style="visibility: hidden">
            <?php echo $form->field($model, 'based_on')->dropDownList(\common\enums\PayrollAdjustmentCodeBase::getUiEnums(), ['prompt' => 'Select']) ?>
        </div>

        <!-- attribute post_to_carrier_id -->
        <?php //echo $form->field($model, 'post_to_carrier_id')->textInput() ?>

        <!-- attribute post_to_driver_id -->
        <?php //echo $form->field($model, 'post_to_driver_id')->textInput() ?>

        <!-- attribute post_to_vendor_id -->
        <?php //echo $form->field($model, 'post_to_vendor_id')->textInput() ?>

        <!-- attribute percent -->
        <?php echo $form->field($model, 'percent')->textInput() ?>

        <!-- attribute amount -->
        <?php echo $form->field($model, 'amount')->textInput() ?>

        <!-- attribute empr_paid -->
        <?php echo $form->field($model, 'empr_paid')->checkbox() ?>

        <!-- attribute inactive -->
        <?php echo $form->field($model, 'inactive')->checkbox() ?>

        </p>
        <?php $this->endBlock(); ?>

        <?php echo Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => [
                    [
                        'label' => Yii::t('app', 'PayrollAdjustmentCode'),
                        'content' => $this->blocks['main'],
                        'active' => true,
                    ],
                ]
            ]
        ); ?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?php echo Html::submitButton('<span class="glyphicon glyphicon-check"></span> '
            . ($model->isNewRecord ? 'Create' : 'Save'),
            [
                'id' => 'save-' . $model->formName(),
                'class' => 'btn btn-success'
            ]
        ); ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$showModal = <<<ENDJS
$("#select-type").modal({backdrop: 'static', keyboard: false});

$('input[type=radio][name=type]').change(function() {          
    $("#payrolladjustmentcode-adj_type").val(this.value);
    if (this.value == "Local Income Tax") {        
        $("#based_on").css("visibility", "hidden");       
        $("#non-tax-adj").attr("disabled", "disabled");                               
        $("#non-tax-adj").css("display", "none");
        $("#non-tax-adj-block").css("display", "none");
        $("#loc-tax-adj").css("display", "block");                       
        $("#loc-tax-adj-block").css("display", "block");
        $("#loc-tax-adj").removeAttr("disabled");                                       
    } else {
        $("#based_on").css("visibility", "visible");                                                
        $("#non-tax-adj").removeAttr("disabled");
        $("#non-tax-adj").css("display", "block");
        $("#non-tax-adj-block").css("display", "block");
        $("#loc-tax-adj").css("display", "none");                
        $("#loc-tax-adj-block").css("display", "none");
        $("#loc-tax-adj").attr("disabled", "disabled");                        
    }        
});

$('#select-type-btn').on('click', function(e) {
    e.preventDefault();
    $("#select-type").modal("hide");
});
ENDJS;
if (!$model->code)
    $this->registerJs($showModal);
?>

<div id="select-type" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <h4 class="modal-title"><?= Yii::t('app', "What Type Of Adjustments Is To Be Created?") ?></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="radio">
                        <label>
                            <input type="radio" name="type" value="<?= PayrollAdjustmentType::LOCAL_INCOME_TAX ?>"
                                   checked>
                            <?= PayrollAdjustmentType::LOCAL_INCOME_TAX ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="type" value="<?= PayrollAdjustmentType::NON_TAX_ADJUSTMENT ?>">
                            <?= PayrollAdjustmentType::NON_TAX_ADJUSTMENT ?>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="select-type-btn" type="button"
                        class="btn btn-primary"><?= Yii::t('app', "Continue") ?></button>
                <a href="<?= Url::to(['payroll-adjustment-code/index']) ?>"
                   class="btn btn-secondary"><?= Yii::t('app', "Cancel") ?></a>
            </div>
        </div>
    </div>
</div>