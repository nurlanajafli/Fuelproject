<?php

use common\enums\FuelChargeTarget;
use common\models\Account;
use common\models\Vendor;
use common\widgets\DataTables\DataColumn;
use common\widgets\ModalForm\ModalForm;
use common\widgets\tdd\Dropdown;
use frontend\forms\fuel\ImportFuelForm;
use yii\bootstrap4\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var string $provider
 * @var array $fileNames
 */

$formConfig = [];

$form = ActiveForm::begin(ArrayHelper::merge($formConfig, [
    'layout' => 'horizontal',
    'fieldConfig' => Yii::$app->params['activeForm']['horizontalLoadFormConfig'],
    'options' => ['data-pjax' => true, 'class' => ''],
    'enableAjaxValidation' => false,
    'id' => 'dispatchload-form'
]));

$model = new ImportFuelForm();
$model->provider = $provider;

$this->beginBlock('content'); ?>

    <?= $form->field($model, 'provider')->hiddenInput()->label(false);?>

    <div class="form-fieldset">

        <div class="field row">
            <div class="col-12">
                <?= $form->field($model,'data_file')
                    ->dropdownList($fileNames)
                ?>
            </div>
        </div>

        <div class="field row">
            <div class="col-6">
                <?= $form->field($model, 'us_vendor')->widget(Dropdown::class, [
                    'grid' => [
                        'dataProvider' => new ActiveDataProvider([
                            'query' => Vendor::find(),
                            'pagination' => false
                        ]),
                        'columns' => [
                            'id',
                            'name',
                            new DataColumn([
                                'attribute' => 'address',
                                'value' => function ($model) { return $model->address_1 . $model->address_2; },
                            ]),
                            'city',
                            'state_id|rel=state._label',
                        ]
                    ],
                    'lookupColumnIndex' => 0,
                    'displayColumnIndex' => 1,
                    'callback' => 'displfunitch'
                ]) ?>
            </div>
            <!--
            <div class="col-6">
                <?= $form->field($model, 'cn_vendor')->widget(Dropdown::class, [
                    'grid' => [
                        'dataProvider' => new ActiveDataProvider([
                            'query' => Vendor::find(),
                            'pagination' => false
                        ]),
                        'columns' => [
                            'id',
                            'name',
                            new DataColumn([
                                'attribute' => 'address',
                                'value' => function ($model) { return $model->address_1 . $model->address_2; },
                            ]),
                            'city',
                            'state_id|rel=state._label',
                        ]
                    ],
                    'lookupColumnIndex' => 0,
                    'displayColumnIndex' => 1,
                    'callback' => 'displfunitch'
                ]) ?>
            </div>
            -->
        </div>

        <div class="form-fieldset">
            <span class="form-legend">
                <?=Yii::t('app', 'Import Period')?>
            </span>
            <div class="field row">
                <div class="col-6">
                    <?= $form->field($model, 'start_date')->textInput(['type' => 'date']) ?>
                </div>
            </div>
            <div class="field row">
                <div class="col-6">
                    <?= $form->field($model, 'end_date')->textInput(['type' => 'date']) ?>
                </div>
            </div>
            <?= $form->field($model, 'separate_by_date')->checkbox() ?>
        </div>

    </div>

    <div class="form-fieldset">
        <span class="form-legend"><?=Yii::t('app', 'Discount Recapture')?></span>
        <div class="field row">
            <div class="col-4">
                <?= $form->field($model, 'pct')->textInput(['type' => 'number', 'step' => 0.01]) ?>
            </div>
            <div class="col-8">
                <?= $form->field($model, 'cn_vendor')->widget(Dropdown::class, [
                    'grid' => [
                        'dataProvider' => new ActiveDataProvider([
                            'query' => Account::find()->where(['in', 'account_type', [2,3]]),
                            'pagination' => false
                        ]),
                        'columns' => [
                            'account',
                            'description',
                            'account_type|rel=accountType.type',
                        ]
                    ],
                    'lookupColumnIndex' => 0,
                    'displayColumnIndex' => 0,
                    'callback' => 'displfunitch'
                ])->label(Yii::t('app', 'GL Acct')) ?>
            </div>
        </div>
    </div>

    <div class="form-fieldset">
        <span class="form-legend">
            <?=Yii::t('app', 'Charge Fuel Purchases To')?>
        </span>
        <div class="field row">
            <div class="col-10 offset-2">
                <?= $form
                    ->field($model, 'charge_to', ['inline'=>true, 'enableLabel'=>false])
                    ->radioList(
                        [FuelChargeTarget::LOAD => ucfirst(FuelChargeTarget::LOAD), FuelChargeTarget::TRUCK => ucfirst(FuelChargeTarget::TRUCK)],
                        ['class' => 'btn-group']
                    )
                ?>
            </div>
        </div>

    </div>

<?php $this->endBlock();

echo ModalForm::widget([
    'content' => $this->blocks['content'],
    'dialogCssClass' => 'modal-lg',
    'saveButton' => false,
    'title' => Yii::t('app', 'Fuel Card Data Import Worksheet'),
    'beforeSaveButtonHtml' => '<button class="btn btn-primary" type="submit" >Save</button>',
    'beforeBodyHtml' => Html::beginForm(['fuel/process-import']),
    'afterFooterHtml' => Html::endForm(),
]);