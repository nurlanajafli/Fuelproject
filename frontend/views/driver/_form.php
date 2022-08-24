<?php
/**
 * @var yii\web\View $this
 * @var common\models\Driver $model
 * @var yii\widgets\ActiveForm $form
 */

use common\models\Account;
use common\models\State;
use common\widgets\DataTables\DataColumn;
use common\widgets\tdd\Dropdown;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$prompt = Yii::t('app', 'Select');
?>
<section class="edit-form driver-form">
    <?php $form = ActiveForm::begin([
            'id' => 'Driver',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-danger',
            'fieldConfig' => Yii::$app->params['activeForm']['horizontalFormConfig'],
    ]); ?>
    <?php $this->beginBlock('personal'); ?>
    <div class="row">
        <div class="col-lg-6">
            <!-- attribute last_name -->
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
            <!-- attribute first_name -->
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
            <!-- attribute middle_name -->
            <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>
            <!-- attribute address_1 -->
            <?= $form->field($model, 'address_1')->textInput(['maxlength' => true]) ?>
            <!-- attribute address_2 -->
            <?= $form->field($model, 'address_2')->textInput(['maxlength' => true]) ?>
            <div class="form-group row">
                <label class="col-sm-2"><?=Yii::t('app', 'City, State, Zip')?></label>
                <!-- attribute city -->
                <?= $form->field($model, 'city', ['template' => '<div class="col-4">{input}{error}{hint}</div>', 'options' => ['tag' => false]])
                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'City')])
                ?>
                <!-- attribute state_id -->
                <?= // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::activeField
                $form->field($model, 'state_id', ['options' => ['tag' => false], 'template' => '<div class="col-2">{input}{error}{hint}</div>',])
                    ->dropDownList(
                        ArrayHelper::map(common\models\State::find()->all(), 'id', '_label'),
                        [
                            'prompt' => Yii::t('app', 'State'),
                            'disabled' => (isset($relAttributes) && isset($relAttributes['state_id'])),
                        ]
                    )->label(false); ?>
                <!-- attribute zip -->
                <?= $form->field($model, 'zip', ['template' => '<div class="col-2">{input}{error}{hint}</div>', 'options' => ['tag' => false]])
                    ->textInput(['placeholder' => 'Zip']) ?>
            </div>
            <!-- attribute telephone -->
            <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>
            <!-- attribute cell_phone -->
            <?= $form->field($model, 'cell_phone')->textInput(['maxlength' => true]) ?>
            <!-- attribute other_phone -->
            <?= $form->field($model, 'other_phone')->textInput(['maxlength' => true]) ?>
            <!-- attribute office_id -->
            <?= // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::activeField
            $form->field($model, 'office_id')->dropDownList(
                ArrayHelper::map(common\models\Office::find()->all(), 'id', '_label'),
                [
                    'prompt' => Yii::t('app', 'Select Office'),
                    'disabled' => (isset($relAttributes) && isset($relAttributes['office_id'])),
                ]
            ); ?>
            <!-- attribute web_id -->
            <?= $form->field($model, 'web_id')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg">
            <!-- attribute email_address -->
            <?= $form->field($model, 'email_address')->textInput(['maxlength' => true]) ?>
            <!-- attribute user_defined_1 -->
            <?= $form->field($model, 'user_defined_1')->textInput(['maxlength' => true]) ?>
            <!-- attribute user_defined_2 -->
            <?= $form->field($model, 'user_defined_2')->textInput(['maxlength' => true]) ?>
            <!-- attribute user_defined_3 -->
            <?= $form->field($model, 'user_defined_3')->textInput(['maxlength' => true]) ?>
            <!-- attribute social_sec_no -->
            <?= $form->field($model, 'social_sec_no')->textInput(['maxlength' => true]) ?>
            <!-- attribute passport_no -->
            <?= $form->field($model, 'passport_no')->textInput(['maxlength' => true]) ?>
            <!-- attribute passport_exp -->
            <?= $form->field($model, 'passport_exp')->textInput(["type" => "date"]) ?>
            <!-- attribute date_of_birth -->
            <?= $form->field($model, 'date_of_birth')->textInput(["type" => "date"]) ?>
            <!-- attribute hire_date -->
            <?= $form->field($model, 'hire_date')->textInput(["type" => "date"]) ?>
            <!-- attribute mail_list -->
            <?= $form->field($model, 'mail_list')->checkbox() ?>
            <!-- attribute maintenance -->
            <?= $form->field($model, 'maintenance')->checkbox() ?>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php $this->beginBlock('pay_defaults'); ?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'type')->dropDownList(
                \common\enums\DriverType::getUiEnums(),
                [
                    'prompt' => $prompt,
                    'class' => 'custom-select'
                ]
            ) ?>
            <?= $form->field($model, 'pay_to_carrier_id')->widget(Dropdown::class, [
                'destAttribute' => [['pay_to_carrier_id' => 0, 'pay_to_driver_id' => 1, 'pay_to_vendor_id' => 2], 5, 0],
                'displayColumnIndex' => 1,
                'grid' => [
                    'dataProvider' => new \yii\data\SqlDataProvider([
                        'sql' => \common\models\Carrier::find()
                            ->select(new \yii\db\Expression("t0.id,'' AS last_name,t0.name AS first_name,t0.address_1,t0.address_2,t0.city,state_code,0 AS type"))
                            ->alias('t0')
                            ->leftJoin(State::tableName(), 't0.state_id=' . State::tableName() . '.id')
                            ->union(\common\models\Driver::find()
                                ->select(new \yii\db\Expression("t0.id,t0.last_name,t0.first_name,t0.address_1,t0.address_2,t0.city,state_code,1 AS type"))
                                ->alias('t0')
                                ->leftJoin(State::tableName(), 't0.state_id=' . State::tableName() . '.id')
                                ->where(['<>', 't0.id', $model->id])
                            )
                            ->union(\common\models\Vendor::find()
                                ->select(new \yii\db\Expression("t0.id,'' AS last_name,t0.name AS first_name,t0.address_1,t0.address_2,t0.city,state_code,2 AS type"))
                                ->alias('t0')
                                ->leftJoin(State::tableName(), 't0.state_id=' . State::tableName() . '.id')
                            )->createCommand()->getRawSql()
                    ]),
                    'columns' => [
                        new DataColumn([
                            'title' => 'Id',
                            'attribute' => 'id',
                            'visible' => false
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Name'),
                            'value' => function ($model) {
                                switch ($model->type) {
                                    case 0:
                                    case 2:
                                    case 1: // FIXME IF COMMENTED - ERROR: Call to undefined method stdClass::getFullName()
                                        return $model->first_name;
                                    case 1:
                                        return join(', ', array_filter([$model->last_name, $model->first_name]));
                                }
                            }
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Address'),
                            'value' => function($model) {
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
                    'order' => [[6, 'asc']]
                ]
            ])->label(Yii::t('app', 'Pay To (other)')) ?>
            <?= $form->field($model, 'expense_acct')->widget(Dropdown::class, [
                'items' => Account::find()->innerJoinWith('accountType')->andWhere(['type' => ['Cost Of Sales', 'Expense']])->all(),
                'modelClass' => Account::class,
                'lookupColumnIndex' => 0,
                'displayColumnIndex' => 0,
                'grid' => [
                    'columns' => [
                        'account',
                        new DataColumn([
                            'title' => Yii::t('app', 'Desc'),
                            'attribute' => 'description'
                        ]),
                        new DataColumn([
                            'attribute' => 'account_type',
                            'value' => function($model){
                                /** @var Account $model */
                                return $model->accountType->type;
                            }
                        ])
                    ],
                    'order' => [[0, 'asc']]
                ]
            ]) ?>
            <?= $form->field($model, 'bank_acct')->widget(Dropdown::class, [
                'items' => Account::find()->innerJoinWith('accountType')->andWhere(['type' => 'Bank'])->all(),
                'modelClass' => Account::class,
                'lookupColumnIndex' => 0,
                'displayColumnIndex' => 0,
                'grid' => [
                    'columns' => [
                        'account',
                        new DataColumn([
                            'title' => Yii::t('app', 'Desc'),
                            'attribute' => 'description'
                        ])
                    ],
                    'order' => [[0, 'asc']]
                ]
            ]) ?>
            <?php
            $a = \common\models\Driver::find()->where(['<>', 'id', $model->id])->all();
            $coDrivers = [];
            foreach ($a as $value) {
                $coDrivers[$value->id] = $value->_label;
            }
            echo $form->field($model, 'co_driver_id')->dropDownList($coDrivers, ['prompt' => $prompt, 'class' => 'custom-select']);
            ?>
            <div class="coDriver_percent<?=is_null($model->co_driver_id) ? ' d-none' : '' ?>">
                <?php echo $form->field($model,'co_driver_earning_percent')->textInput(['type'=>'number','step'=>0.01]);?>
            </div>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'pay_standard')->dropDownList(\common\enums\PayStandard::getUiEnums(), ['prompt' => $prompt, 'class' => 'custom-select']) ?>
            <?= $form->field($model, 'period_salary')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'hourly_rate')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'addl_ot_pay')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'addl_ot_pay_2')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'base_hours')->textInput(['type' => 'number']) ?>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php $this->beginBlock('dispatch_pay'); $form->fieldConfig = Yii::$app->params['activeForm']['fieldConfig']; ?>
    <div class="row">
        <div class="col-8">
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'pay_source')->dropDownList(\common\enums\PaySource::getUiEnums(), ['prompt' => $prompt, 'class' => 'custom-select']) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'loaded_miles')->dropDownList(\common\enums\Mile::getUiEnums(), ['prompt' => $prompt, 'class' => 'custom-select']) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'empty_miles')->dropDownList(\common\enums\Mile::getUiEnums(), ['prompt' => $prompt, 'class' => 'custom-select']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-5">
            <div class="form-fieldset">
                <span class="form-legend"><?= Yii::t('app', 'Truckload') ?></span>
                <?php $form->fieldConfig = Yii::$app->params['activeForm']['horizontalFormConfig']; ?>
                <?= $form->field($model, 'loaded_pay_type')->dropDownList(\common\enums\PayType::getUiEnums(), ['prompt' => $prompt, 'class' => 'custom-select']) ?>
                <?= $form->field($model, 'loaded_per_mile')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                <?= $form->field($model, 'empty_per_mile')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                <?= $form->field($model, 'percentage')->textInput(['type' => 'number', 'step' => '0.01']) ?>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php $this->beginBlock('fuel_cards'); ?>
    <div class="row">
        <div class="col-3"></div>
        <div class="col-3"></div>
        <div class="col-3"><?= Yii::t('app', 'Discount Recapture Pct') ?></div>
        <div class="col-3"><?= Yii::t('app', 'Discount Recapture GL Acct') ?></div>
    </div>
    <?php
    $fuelCards = $model->driverFuelCards;
    $fuelCardTypes = \common\enums\FuelCardType::getEnums();
    $options = ['template' => '{input}{error}', 'options' => ['class' => '']];
    foreach ($fuelCardTypes as $fuelCardType) :
        $tempModel = new \common\models\DriverFuelCard();
        if (Yii::$app->request->isPost) {
            $formName = $tempModel->formName();
            $formName = substr($formName, 0, strpos($formName, '['));
            $tempModel->load(Yii::$app->request->post($formName), $fuelCardType);
            $tempModel->card_type = $fuelCardType;
        } else {
            $tempModel->card_type = $fuelCardType;
            foreach ($fuelCards as $fuelCard) {
                if ($fuelCard->card_type == $fuelCardType) {
                    $tempModel = $fuelCard;
                    break;
                }
            }
        }
    ?>
    <div class="row" style="margin-bottom: 2px;">
        <div class="col-3"><?= Yii::t('app', $fuelCardType) ?></div>
        <div class="col-3">
            <?= $form->field($tempModel, 'card_id', $options)->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-3"><?= $form->field($tempModel, 'discount_recapture_pct', $options)->textInput(['maxlength' => true, 'type' => 'number']) ?></div>
        <div class="col-3">
            <?php
            echo $form->field($tempModel, 'discount_recapture_gl_acct', $options)->widget(Dropdown::class, [
                'items' => Account::find()->innerJoinWith('accountType')->andWhere(['type' => ['Income', 'Other Income']])->all(),
                'modelClass' => Account::class,
                'lookupColumnIndex' => 0,
                'displayColumnIndex' => 0,
                'grid' => [
                    'columns' => [
                        'account',
                        new DataColumn([
                            'title' => Yii::t('app', 'Desc'),
                            'attribute' => 'description'
                        ]),
                        new DataColumn([
                            'attribute' => 'account_type',
                            'value' => function ($model) {
                                /** @var Account $model */
                                return $model->accountType->type;
                            }
                        ]),
                    ],
                    'order' => [[0, 'asc']]
                ]
            ]);
            ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php $this->endBlock(); ?>
    <?php $this->beginBlock('settlement_processing'); ?>
    	<div class="row">
    		<div class="col-lg-6">
    			<div class="form-fieldset">
                	<span class="form-legend"><?= Yii::t('app', 'Options') ?></span>
                	<?php echo Html::checkbox('settlement_option_1', false,['label' => Yii::t('app', 'Hide Mileage On Printed Pay Settlents')]);?>
                	<?php echo Html::checkbox('settlement_option_2', false,['label' => Yii::t('app', 'Hide Empty Pay On Printed Pay Settlents')]);?>
                	<?php echo Html::checkbox('settlement_option_3', false,['label' => Yii::t('app', 'Show load Revenue On Printed Pay Settlents')]);?>
                	<?php echo Html::checkbox('settlement_option_4', false,['label' => Yii::t('app', 'Exclude Revenue For Non-percentage Pay Loads')]);?>
                </div>
                <div class="form-group row print_on_check">
					<?php echo Html::label(Yii::t('app', 'Print On Check'), 'print_on_check', ['class' => 'col-sm-3']);?>
					<div class="col-sm-7">
						<?php echo Html::textInput('print_on_check', '', ['id' => 'print_on_check', 'class' => 'form-control'])?>
					</div>
				</div>
    			
    		</div>
    		<div class="col-5">
    			<div class="form-fieldset">
                	<span class="form-legend"><?= Yii::t('app', 'Direct deposit') ?></span>
                	<div class="form-group row direct_deposit_provider">
						<?php echo Html::label(Yii::t('app', 'Provider'), 'direct_deposit_provider');?>
						<div class="col-sm-8">
							<?php echo Html::dropDownList(
    							    'direct_deposit_provider', 
    							    null, 
    							    \common\enums\DepositProvider::getUiEnums(), 
    							    [
    							        'class' => 'custom-select',
    							        'prompt' => $prompt
    							        
    							    ]
							    );
							?>
						</div>
					</div>
					<div class="form-group row direct_deposit_id">
						<?php echo Html::label(Yii::t('app', 'Deposit ID'), 'direct_deposit_id');?>
						<div class="col-sm-8">
							<?php echo Html::textInput('direct_deposit_id', '', ['id' => 'direct_deposit_id', 'class' => 'form-control'])?>
						</div>
					</div>
                </div>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col">
    			<div class="form-fieldset">
                	<span class="form-legend"><?php echo Html::checkbox('multiple_truck_owner', false,['label' => Yii::t('app', 'Multiple truck Owner')]);?></span>
                	<div class="row">
                		<div class="col"><?= Yii::t('app', 'Compensation is based on:') ?></div>
                	</div>
                	<div class="row">
                		<div class="col-sm-2">
                    		<?php echo Html::radioList('compensation', 'compensation_1', [
                    		    'compensation_1' => Yii::t('app', 'Driver Activity'),
                    		    'compensation_2' => Yii::t('app', 'Truck Activity')
                        	]);?>
                		</div>
                	</div>
                </div>
    		</div>
    	</div>
    <?php $this->endBlock(); ?>
    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => [
            [
                'label' => Yii::t('app', 'Personal'),
                'content' => $this->blocks['personal'],
                'active' => true,
            ],
            [
                'label' => Yii::t('app', 'Pay Defaults'),
                'content' => $this->blocks['pay_defaults'],
                'active' => false
            ],
            [
                'label' => Yii::t('app', 'Dispatch Pay'),
                'content' => $this->blocks['dispatch_pay'],
                'active' => false
            ],
            [
                'label' => Yii::t('app', 'Fuel Cards'),
                'content' => $this->blocks['fuel_cards'],
                'active' => false
            ],
            [
                'label' => Yii::t('app', 'Sattlement Processing'),
                'content' => $this->blocks['settlement_processing'],
                'active' => false
            ],
        ]
    ]); ?>
    <hr/>
    <div class="row">
        <div class="col-lg-12">
            <!-- attribute notes -->
            <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <?= $form->errorSummary($model) ?>
    <?= Html::submitButton(
        '<i class="fas fa fa-check"></i> ' .
        ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')),
        ['id' => 'save-' . $model->formName(), 'class' => 'btn btn-success']
    ); ?>
    <?php ActiveForm::end(); ?>
</section>