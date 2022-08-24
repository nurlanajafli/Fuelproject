<?php
/**
 * @var \common\components\View $this
 * @var array $formConfig
 * @var \common\models\DriverAdjustment $model
 */

use \yii\bootstrap4\ActiveForm;
use common\widgets\DataTables\DataColumn;
use common\models\State;
use common\widgets\tdd\Dropdown;
use common\widgets\DataTables\CheckboxColumn;

$prompt = Yii::t('app', 'Select');

$this->beginBlock('form');
$form = ActiveForm::begin(\yii\helpers\ArrayHelper::merge($formConfig, [
    'fieldConfig' => [
        'options' => ['class' => 'form-group row'],
        'template' => '<div class="col-sm-4">{label}</div><div class="col-sm-7">{input}{error}</div>'
    ],
    'options' => ['data-cb' => 'automaticAdjReload']
]));
?>
<?= $form->field($model, 'payroll_adjustment_code')->widget(Dropdown::class, [
    'grid' => [
        'dataProvider' => new \yii\data\ActiveDataProvider([
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
            new DataColumn([
                'title' => 'Flat',
                'value' => function ($model) {
                    return $model->amount;
                }
            ]),
            new CheckboxColumn([
                'title' => Yii::t('app', 'Mile'),
            ]),
            new CheckboxColumn([
                'title' => Yii::t('app', 'Empr'),
                'attribute' => 'empr_paid'
            ])
        ],
        'pageLength' => 20,
        'attributes' => ['data-callback' => 'adjCodeChanged']
    ],
    'lookupColumnIndex' => 3,
    'displayColumnIndex' => 3,
]) ?>
<?= $form->field($model, 'post_to_carrier_id')->widget(Dropdown::class, [
    'grid' => [
        'id' => 'dadj-post-to',
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
        'rowAttributes' => 'dadjPostToAttrs'
    ],
    'destAttribute' => [['post_to_carrier_id' => 0, 'post_to_driver_id' => 1, 'post_to_vendor_id' => 2], 0, 1],
    'displayColumnIndex' => 2,
    'resetBtn' => ['attr' => ['style' => 'display:none;']]
]) ?>
<?= $form->field($model, 'account')->widget(Dropdown::class, [
    'grid' => [
        'id' => 'dadj-account',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => \common\models\Account::getFilterByType(null, true),
            'pagination' => false
        ]),
        'columns' => [
            'account',
            'description|title=Desc',
            'account_type|rel=accountType.type'
        ],
        'order' => [[0, 'asc']],
        'rowAttributes' => 'dadjAccountAttrs'
    ],
    'lookupColumnIndex' => 0,
    'displayColumnIndex' => 0,
    'resetBtn' => ['attr' => ['style' => 'display:none;']]
]) ?>
<?= $form->field($model, 'calc_by')->dropdownList(\common\enums\AdjustmentCalcBy::getUiEnums(), ['class' => 'custom-select']) ?>
<?= $form->field($model, 'amount')->textInput(['type' => 'number', 'min' => '0.00', 'step' => '0.01']) ?>
<?= $form->field($model, 'cap_id')->widget(Dropdown::class, [
    'grid' => [
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => \common\models\Cap::find(),
            'pagination' => false
        ]),
        'columns' => [
            'id',
            'description'
        ],
        'order' => [[0, 'asc']],
        'head' => false
    ]
]) ?>
<?= $form->field($model, 'truck_id')->widget(Dropdown::class, [
    'grid' => [
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => \common\models\Truck::find(),
            'pagination' => false
        ]),
        'columns' => [
            'id|visible=false|searchable=false',
            'truck_no',
            'type',
        ],
        'order' => [[1, 'asc']]
    ]
]) ?>
<?php
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Setup Adjustments'),
    'content' => $this->blocks['form'],
    'beforeSaveButtonHtml' => $model->isNewRecord ? '' :
        ('<button class="btn btn-danger js-submit" type="button" data-code="delete" data-id="delete">' . Yii::t('app', 'Delete') . '</button>')
]);
