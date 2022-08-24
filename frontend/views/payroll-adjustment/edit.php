<?php
/**
 * @var \common\components\View $this
 * @var \common\models\PayrollAdjustment $model
 * @var array $formConfig
 * @var string $type
*/

use common\enums\PayrollBatchType;
use common\widgets\DataTables\CheckboxColumn;
use common\widgets\tdd\Dropdown;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use common\widgets\DataTables\DataColumn;
use common\models\State;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

$prompt = Yii::t('app', 'Select');

$this->beginBlock('tb');
?>
<div class="edit-form-toolbar">
    <button class="btn btn-link js-submit" data-tooltip="tooltip" data-placement="top" data-id="save">
        <i class="fas fa-save"></i>
    </button>
    <?php if ($model->id) : ?>
    <button class="btn btn-link js-submit" data-tooltip="tooltip" data-placement="top" data-id="delete">
        <i class="fas fa-trash-alt"></i>
    </button>
    <?php endif; ?>
</div>
<?php
$this->endBlock();
$this->beginBlock('form');
$form = ActiveForm::begin(ArrayHelper::merge($formConfig, [
    'fieldConfig' => [
        'options' => ['class' => 'form-group row'],
        'template' => '<div class="col-sm-4">{label}</div><div class="col-sm-7">{input}{error}</div>'
    ],
    'options' => ['data-cb' => 'padjFormCb']
]));
if ($type == PayrollBatchType::D_DRIVER):
?>
    <?= $form->field($model, 'd_payroll_adjustment_code')->widget(Dropdown::class, [
    'grid' => [
        'dataProvider' => new ActiveDataProvider([
            'query' => \common\models\PayrollAdjustmentCode::find()->orderBy('code'),
            'pagination' => false
        ]),
        'columns' => [
            'post_to_carrier_id|visible=false',
            'post_to_driver_id|visible=false',
            'post_to_vendor_id|visible=false',
            'code',
            'account|title=Acct',
            'percent|title=Pct',
            'amount|visible=false',
            'amount|title=Flat',
            new CheckboxColumn([
                'title' => Yii::t('app', 'Mile'),
            ]),
            new CheckboxColumn([
                'title' => Yii::t('app', 'Empr'),
                'attribute' => 'empr_paid'
            ])
        ],
        'attributes' => ['data-callback' => 'payrollAdjCodeChanged']
    ],
    'lookupColumnIndex' => 3,
    'displayColumnIndex' => 3,
    ]) ?>
    <?= $form->field($model, 'd_post_to_carrier_id')->widget(Dropdown::class, [
    'grid' => [
        'id' => 'padj-post-to',
        'dataProvider' => new \yii\data\SqlDataProvider([
            'sql' => \common\models\Carrier::find()
                ->select(new \yii\db\Expression("t0.id,t0.name,t0.address_1,t0.address_2,t0.city,state_code,0 AS type"))
                ->alias('t0')
                ->leftJoin(State::tableName(), 't0.state_id=' . State::tableName() . '.id')
                ->union(\common\models\Driver::find()
                    ->select(new \yii\db\Expression("t0.id,last_name||', '||first_name AS name,t0.address_1,t0.address_2,t0.city,state_code,1 AS type"))
                    ->alias('t0')
                    ->leftJoin(State::tableName(), 't0.state_id=' . State::tableName() . '.id')
                )
                ->union(\common\models\Vendor::find()
                    ->select(new \yii\db\Expression("t0.id,t0.name,t0.address_1,t0.address_2,t0.city,state_code,2 AS type"))
                    ->alias('t0')
                    ->leftJoin(State::tableName(), 't0.state_id=' . State::tableName() . '.id')
                )->createCommand()->getRawSql(),
            'pagination' => false
        ]),
        'columns' => [
            'type|visible=false|searchable=false',
            'id|visible=false|searchable=false',
            'name',
            'address_1|title=Address|coalesce=address_2',
            'city',
            'state_code|title=St',
            new DataColumn([
                'title' => Yii::t('app', 'Type'),
                'value' => function ($model) {
                    $messages = ['Carrier', 'Driver', 'Vendor'];
                    return Yii::t('app', $messages[$model->type]);
                }
            ])
        ],
        'order' => [[2, 'asc']],
        'rowAttributes' => 'padjPostToAttrs'
    ],
    'destAttribute' => [['d_post_to_carrier_id' => 0, 'd_post_to_driver_id' => 1, 'd_post_to_vendor_id' => 2], 0, 1],
    'displayColumnIndex' => 2,
    'resetBtn' => ['attr' => ['style' => 'display:none;']]
    ]) ?>
    <?= $form->field($model, 'd_account')->widget(Dropdown::class, [
    'grid' => [
        'id' => 'padj-account',
        'dataProvider' => new ActiveDataProvider([
            'query' => \common\models\Account::getFilterByType(null, true),
            'pagination' => false
        ]),
        'columns' => [
            'account',
            'description|title=Desc',
            'account_type|rel=accountType.type'
        ],
        'order' => [[0, 'asc']],
        'rowAttributes' => 'padjAccountAttrs'
    ],
    'lookupColumnIndex' => 0,
    'displayColumnIndex' => 0,
    'resetBtn' => ['attr' => ['style' => 'display:none;']]
    ]) ?>
    <?= $form->field($model, 'd_calc_by')->dropdownList(\common\enums\AdjustmentCalcBy::getUiEnums(), ['prompt' => $prompt, 'class' => 'custom-select']) ?>
    <?= $form->field($model, 'd_amount')->textInput(['type' => 'number', 'min' => '0.00', 'step' => '0.01']) ?>
    <?= $form->field($model, 'd_load_id')->dropdownList(ArrayHelper::map(\common\models\Load::find()->orderBy('id')->all(), 'id', 'id'), ['prompt' => $prompt, 'class' => 'custom-select']) ?>
    <?= $form->field($model, 'd_description')->textarea(['rows' => 3]) ?>
<?php
endif;
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Input Adjustment'),
    'toolbar' => $this->blocks['tb'],
    'content' => $this->blocks['form'],
    'saveButton' => false,
    'closeButton' => false
]);
