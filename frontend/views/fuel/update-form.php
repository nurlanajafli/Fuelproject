<?php

use common\models\State;
use common\widgets\DataTables\DataColumn;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Tabs;
use yii\helpers\{Html, ArrayHelper};

/**
 *
 * @var yii\web\View $this
 * @var common\models\FuelImport $model
 * @var yii\bootstrap4\ActiveForm $form
 */

?>
<section class="edit-form truck-form">
    <?php $form = ActiveForm::begin([
            'id' => 'FileImport',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-danger',
            'fieldConfig' => Yii::$app->params['activeForm']['horizontalFormConfig'],
        ]
    );
    ?>
    <?php $this->beginBlock('info'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group row">
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'transaction_id')->textInput(['type' => 'number']) ?>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'card_check_no')->textInput(['type' => 'number']) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'load_no')->textInput(['type' => 'number']) ?>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'driver_name_reported')->textInput() ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'purchase_date')->textInput(['type' => 'date']) ?>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'purchase_time')->textInput(['type' => 'time']) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'trip_no')->textInput(['type' => 'number']) ?>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'unit_id')->widget(\common\widgets\tdd\Dropdown::class, [
                        'grid' => [
                            'dataProvider' => new \yii\data\ActiveDataProvider([
                                'query' => \common\models\Unit::find()->joinWith(['driver', 'coDriver', 'truck', 'trailer', 'trailer2']),
                                'pagination' => false
                            ]),
                            'columns' => [
                                'id',
                                'driver_id|rel=driver._label|default=No driver',
                                'driver_id|visible=false',
                                'co_driver_id|rel=coDriver._label|default=No co driver',
                                'co_driver_id|visible=false',
                                new DataColumn([
                                    'attribute' => 'truck_id',
                                    'value' => function ($model) {
                                        return $model->truck ? $model->truck->id . ' (' . $model->truck->_label . ')' : Yii::t('app', 'No truck');
                                    },
                                ]),
                                'truck_id|visible=false',
                                new DataColumn([
                                    'attribute' => 'trailer_id',
                                    'value' => function ($model) {
                                        return $model->trailer ? $model->trailer->id . ' (' . $model->trailer->_label . ')' : Yii::t('app', 'No trailer');
                                    },
                                ]),
                                'trailer_id|visible=false',
                                new DataColumn([
                                    'attribute' => 'trailer_2_id',
                                    'value' => function ($model) {
                                        return $model->trailer2 ? $model->trailer2->id . ' (' . $model->trailer2->_label . ')' : Yii::t('app', 'No trailer 2');
                                    },
                                ]),
                                'trailer_2_id|visible=false',
                                'null|rel=driver.loaded_pay_type|visible=false',
                                'null|rel=driver.loaded_per_mile|visible=false',
                                'null|rel=driver.empty_per_mile|visible=false',
                                'null|rel=driver.percentage|visible=false',
                                'status'
                            ]
                        ],
                        'lookupColumnIndex' => 0,
                        'displayColumnIndex' => 0,
                    ])->label('Unit') ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 col-xs-12">
                    <div class="form-group row">
                        <?= $form->field($model, 'driver_id', [
                                'options' => ['tag' => false],
                            ])->dropDownList(
                                ArrayHelper::map(common\models\Driver::find()->all(), 'id', '_label'),
                                [
                                    'prompt' => Yii::t('app', 'Not select'),
                                    'disabled' => (isset($relAttributes) && isset($relAttributes['state_id'])),
                                ]
                            )->label('Driver');
                        ?>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <div class="form-group row">
                        <?= $form->field($model, 'truck_id', [
                                'options' => ['tag' => false],
                            ])->dropDownList(
                                ArrayHelper::map(common\models\Truck::find()->all(), 'id', '_label'),
                                [
                                    'prompt' => Yii::t('app', 'Not select'),
                                    'disabled' => (isset($relAttributes) && isset($relAttributes['state_id'])),
                                ]
                            )->label('Truck');
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 col-xs-12">
                    <div class="form-group row">
                        <?= $form->field($model, 'trailer_id', [
                                'options' => ['tag' => false],
                            ])->dropDownList(
                                ArrayHelper::map(common\models\Trailer::find()->all(), 'id', '_label'),
                                [
                                    'prompt' => Yii::t('app', 'Not select'),
                                    'disabled' => (isset($relAttributes) && isset($relAttributes['state_id'])),
                                ]
                            )->label('Trailer');
                        ?>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'vendor')->textInput() ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'city')->textInput() ?>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <div class="row">
                        <?= $form->field($model, 'state_id', [
                                'options' => ['tag' => false],
                            ])->dropDownList(
                                ArrayHelper::map(common\models\State::find()->all(), 'id', '_label'),
                                [
                                    'prompt' => Yii::t('app', 'State'),
                                    'disabled' => (isset($relAttributes) && isset($relAttributes['state_id'])),
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'product_code')->textInput(['type' => 'number']) ?>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'quantity')->textInput(['type' => 'number', 'step' => 0.01]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'cost')->textInput(['type' => 'number', 'step' => 0.01]) ?>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'ppg')->textInput(['type' => 'number', 'step' => 0.01]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'description')->textInput() ?>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <?= $form->field($model, 'err')->textInput() ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php echo Tabs::widget([
        'encodeLabels' => false,
        'items' => [
            [
                'label' => Yii::t('app', 'Info'),
                'content' => $this->blocks['info'],
                'active' => true,
            ]
        ]
    ]);
    echo "\n<hr/>\n";
    echo $form->errorSummary($model);
    echo Html::submitButton(
        '<i class="fas fa fa-check"></i> ' .
        ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')),
        ['id' => 'save-' . $model->formName(), 'class' => 'btn btn-success']
    );
    ActiveForm::end();
    ?>
</section>