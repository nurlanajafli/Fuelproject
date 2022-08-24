<?php
/**
 * /var/www/html/frontend/runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Tabs;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use common\models\State;
use common\models\Zone;
use common\models\CarrierProfile;
use common\models\LanePreference;
use common\models\Lane;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var common\models\Carrier $model
 * @var CarrierProfile $profileModel
 * @var LanePreference $lanePreferenceModel
 * @var Lane[] $laneModels
 * @var yii\widgets\ActiveForm $form
 */

$prompt = Yii::t('app', 'Select');
$states = ArrayHelper::map(State::find()->all(), 'id', '_label');
$f = function ($form, $prefix) use ($laneModels, $states, $prompt) {
    for ($i = 1; $i <= Yii::$app->params['lane']['max']; $i++) {
        $j = $i - 1;
        $m = ($i == 1) ? current($laneModels) : next($laneModels);
        if ($m === false) {
            $m = new Lane();
        }
        echo "<div class=\"row\"><div class=\"col\">";
        echo $form->field($m, "[$j]{$prefix}city")->textInput(['maxlength' => true])->label(false);
        echo "</div><div class=\"col\">";
        echo $form->field($m, "[$j]{$prefix}state_id")->dropDownList(
            $states,
            [
                'prompt' => $prompt,
                'class' => 'custom-select',
            ]
        )->label(false);
        echo "</div><div class=\"col\">";
        echo $form->field($m, "[$j]{$prefix}zone")->dropDownList(
            ArrayHelper::map(Zone::find()->all(), 'code', 'code'),
            [
                'prompt' => $prompt,
                'class' => 'custom-select',
            ]
        )->label(false);
        echo "</div></div>";
    }
};

$firstTabFldConf = [
    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
    'horizontalCssClasses' => [
        'label' => 'col-sm-2',
        'wrapper' => 'col-sm-8',
        'error' => '',
        'hint' => '',
    ]
];
?>
<section class="edit-form carrier-form">

    <?php $form = ActiveForm::begin([
		    'id' => 'Carrier',
		    'layout' => 'horizontal',
		    'enableClientValidation' => true,
		    'errorSummaryCssClass' => 'error-summary alert alert-danger',
		    'fieldConfig' => Yii::$app->params['activeForm']['horizontalFormConfig'],
	    ]
    );

    $this->beginBlock('company');
    ?>
    <div class="row">
        <div class="col-lg">

            <!-- attribute name -->
            <?php echo $form->field($model, 'name', $firstTabFldConf)->textInput(['maxlength' => true]) ?>

            <!-- attribute address_1 -->
            <?php echo $form->field($model, 'address_1', $firstTabFldConf)->textInput(['maxlength' => true]) ?>

            <!-- attribute address_2 -->
            <?php echo $form->field($model, 'address_2')->textInput(['maxlength' => true]) ?>

            <div class="form-group row">
                <label class="col-sm-2"><?= $model->getAttributeLabel('city') . ', ' . $model->getAttributeLabel('state_id') . ', ' . $model->getAttributeLabel('zip') ?></label>
                <!-- attribute city -->
                <?php echo $form->field($model, 'city', ['template' => '<div class="col-4">{input}{error}{hint}</div>', 'options' => ['tag' => false]])
                    ->textInput(['maxlength' => true]) ?>
                <!-- attribute state_id -->
                <?php echo // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::activeField
                $form->field($model, 'state_id', ['template' => '<div class="col-2">{input}{error}{hint}</div>', 'options' => ['tag' => false]])->dropDownList(
                    $states = \yii\helpers\ArrayHelper::map(common\models\State::find()->all(), 'id', '_label'),
                    [
                        'prompt' => $prompt,
                        'class' => 'custom-select',
                    ]
                ); ?>
                <!-- attribute zip -->
                <?php echo $form->field($model, 'zip', ['template' => '<div class="col-2">{input}{error}{hint}</div>', 'options' => ['tag' => false]])
                    ->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-6">
            <!-- attribute main_phone -->
            <?php echo $form->field($model, 'main_phone')->textInput(['maxlength' => true]) ?>

            <!-- attribute main_800 -->
            <?php echo $form->field($model, 'main_800')->textInput(['maxlength' => true]) ?>

            <!-- attribute main_fax -->
            <?php echo $form->field($model, 'main_fax')->textInput(['maxlength' => true]) ?>

            <!-- attribute email -->
            <?php echo $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <!-- attribute website -->
            <?php echo $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6">
			<!-- attribute disp_contact -->
			<?php echo $form->field($model, 'disp_contact')->textInput(['maxlength' => true]) ?>

			<!-- attribute ar_contact -->
			<?php echo $form->field($model, 'ar_contact')->textInput(['maxlength' => true]) ?>

            <!-- attribute other_contact -->
            <?php echo $form->field($model, 'other_contact')->textInput(['maxlength' => true]) ?>

            <!-- attribute account_no -->
			<?php echo $form->field($model, 'account_no')->textInput(['maxlength' => true]) ?>

			<!-- attribute mail_list -->
			<?php echo $form->field($model, 'mail_list')->checkbox() ?>

            <!-- attribute federal_id -->
            <?php //echo $form->field($model, 'federal_id')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('profile'); ?>
    <?php $form->fieldConfig = Yii::$app->params['activeForm']['fieldConfig']; ?>
    <div class="row">
    <div class="col-6">
        <div class="form-fieldset">
            <span class="form-legend"><?php echo Yii::t('app', 'Carrier Profile') ?></span>
            <div class="row">
                <div class="col">
                    <!-- attribute mcid -->
                    <?php echo $form->field($profileModel, 'mcid')->textInput(['maxlength' => true]) ?>
                    <!-- attribute dotid -->
                    <?php echo $form->field($profileModel, 'dotid')->textInput(['maxlength' => true]) ?>
                    <!-- attribute scac -->
                    <?php echo $form->field($profileModel, 'scac')->textInput(['maxlength' => true]) ?>
                    <!-- attribute type -->
                    <?php echo $form->field($profileModel, 'type')->dropDownList(
                        \yii\helpers\ArrayHelper::map(common\models\CarrierType::find()->all(), 'code', 'code'),
                        [
                            'prompt' => $prompt,
                            'class' => 'custom-select',
                        ]
                    ); ?>
                    <!-- attribute ranking -->
                    <?php echo $form->field($profileModel, 'ranking')->dropDownList(
                        \yii\helpers\ArrayHelper::map(common\models\CarrierRanking::find()->all(), 'code', 'code'),
                        [
                            'prompt' => $prompt,
                            'class' => 'custom-select',
                        ]
                    ); ?>
                    <!-- attribute carb_id -->
                    <?php echo $form->field($profileModel, 'carb_id')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col">
                    <!-- attribute approved -->
                    <?php echo $form->field($profileModel, 'approved')->checkbox(Yii::$app->params['activeForm']['noIndentCheckbox']) ?>
                    <!-- attribute contract_on_fire -->
                    <?php echo $form->field($profileModel, 'contract_on_fire')->checkbox(Yii::$app->params['activeForm']['noIndentCheckbox']) ?>
                    <!-- attribute contract_date -->
                    <?php echo $form->field($profileModel, 'contract_date')->textInput(['type' => 'date']) ?>
                    <!-- attribute authority -->
                    <?php echo $form->field($profileModel, 'authority')->dropDownList(
                        \common\enums\Authority::getUiEnums(),
                        [
                            'prompt' => $prompt,
                            'class' => 'custom-select',
                        ]
                    ); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-fieldset">
            <span class="form-legend"><?php echo Yii::t('app', 'Insurance Policies') ?></span>
            <div class="row">
                <div class="col"></div>
                <div class="col"><?php echo Yii::t('app', 'Policy No') ?></div>
                <div class="col"><?php echo Yii::t('app', 'Expires') ?></div>
                <div class="col"><?php echo Yii::t('app', 'Amount') ?></div>
            </div>
            <div class="row">
                <div class="col"><?php echo Yii::t('app', 'Liability Ins') ?></div>
                <div class="col"><?php echo $form->field($profileModel, 'liability_ins_policy_no')->textInput(['maxlength' => true])->label(false) ?></div>
                <div class="col"><?php echo $form->field($profileModel, 'liability_ins_expires')->textInput(['type' => 'date'])->label(false) ?></div>
                <div class="col"><?php echo $form->field($profileModel, 'liability_ins_amount')->textInput(['maxlength' => true])->label(false) ?></div>
            </div>
            <div class="row">
                <div class="col"><?php echo Yii::t('app', 'Cargo Ins') ?></div>
                <div class="col"><?php echo $form->field($profileModel, 'cargo_ins_policy_no')->textInput(['maxlength' => true])->label(false) ?></div>
                <div class="col"><?php echo $form->field($profileModel, 'cargo_ins_expires')->textInput(['type' => 'date'])->label(false) ?></div>
                <div class="col"><?php echo $form->field($profileModel, 'cargo_ins_amount')->textInput(['maxlength' => true])->label(false) ?></div>
            </div>
            <div class="row">
                <div class="col"><?php echo Yii::t('app', 'Trailer Inter') ?></div>
                <div class="col"><?php echo $form->field($profileModel, 'trailer_inter_policy_no')->textInput(['maxlength' => true])->label(false) ?></div>
                <div class="col"><?php echo $form->field($profileModel, 'trailer_inter_expires')->textInput(['type' => 'date'])->label(false) ?></div>
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col"><?php echo Yii::t('app', 'Work Comp') ?></div>
                <div class="col"><?php echo $form->field($profileModel, 'work_comp_policy_no')->textInput(['maxlength' => true])->label(false) ?></div>
                <div class="col"><?php echo $form->field($profileModel, 'work_comp_expires')->textInput(['type' => 'date'])->label(false) ?></div>
                <div class="col"></div>
            </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="form-fieldset">
                <span class="form-legend"><?php echo Yii::t('app', 'Lane Preferences') ?></span>
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col"><?php echo Yii::t('app', 'Origin Cities') ?></div>
                                    <div class="col"><?php echo Yii::t('app', 'States') ?></div>
                                    <div class="col"><?php echo Yii::t('app', 'Zones') ?></div>
                                </div>
                                <?php $f($form, 'origin_'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col"><?php echo $form->field($lanePreferenceModel, 'forty_eight_states')->checkbox(Yii::$app->params['activeForm']['noIndentCheckbox']) ?></div>
                            <div class="col"><?php echo $form->field($lanePreferenceModel, 'state_id')->dropDownList(
                                    $states,
                                    [
                                        'prompt' => $prompt,
                                        'class' => 'custom-select'
                                    ]
                                ) ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col"><?php echo Yii::t('app', 'Destination Cities') ?></div>
                            <div class="col"><?php echo Yii::t('app', 'States') ?></div>
                            <div class="col"><?php echo Yii::t('app', 'Zones') ?></div>
                        </div>
                        <?php $f($form, 'destination_'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="form-fieldset">
                <span class="form-legend"><?php echo Yii::t('app', 'Fleet Description') ?></span>
                <div class="row">
                    <div class="col"><?php echo $form->field($profileModel, 'trucks_count')->textInput(['type' => 'number']) ?></div>
                    <div class="col"><?php echo $form->field($profileModel, 'trucks_type_1')->dropDownList(
                            $truckTypes = \yii\helpers\ArrayHelper::map(\common\models\TruckType::find()->all(), 'type', '_label'),
                            [
                                'prompt' => $prompt,
                                'class' => 'custom-select',
                            ]
                        ) ?></div>
                    <div class="col"><?php echo $form->field($profileModel, 'trucks_type_2')->dropDownList(
                            $truckTypes,
                            [
                                'prompt' => $prompt,
                                'class' => 'custom-select',
                            ]
                        ) ?></div>
                </div>
                <div class="row">
                    <div class="col"><?php echo $form->field($profileModel, 'trailers_count')->textInput(['type' => 'number']) ?></div>
                    <div class="col"><?php echo $form->field($profileModel, 'trailers_type_1')->dropDownList(
                            $trailerTypes = \yii\helpers\ArrayHelper::map(\common\models\TrailerType::find()->all(), 'type', '_label'),
                            [
                                'prompt' => $prompt,
                                'class' => 'custom-select',
                            ]
                        ) ?></div>
                    <div class="col"><?php echo $form->field($profileModel, 'trailers_type_2')->dropDownList(
                            $trailerTypes,
                            [
                                'prompt' => $prompt,
                                'class' => 'custom-select',
                            ]
                        ) ?></div>
                </div>
                <div class="row">
                    <div class="col"><?php echo $form->field($profileModel, 'o_o')->checkbox(Yii::$app->params['activeForm']['noIndentCheckbox']) ?></div>
                    <div class="col"><?php echo $form->field($profileModel, 'haz_mat')->checkbox(Yii::$app->params['activeForm']['noIndentCheckbox']) ?></div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('contacts'); ?>

    <div class="clearfix">
        <button type="button" class="btn btn-sm btn-secondary float-right js-ajax-modal" data-url="<?= Url::toRoute(['carrier/contact-form', 'carrier' => $model->id]) ?>">
            <i class="fas fa fa-plus"></i> Add
        </button>
    </div>

    <br/>

    <?php $carrierId = $model->id; ?>
    <?= yii\grid\GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->carrierContacts,
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'contact_name',
            [
                'attribute' => 'department.name',
                'label' => Yii::t('app', 'Department'),
            ],
            [
                'attribute' => 'direct_line',
                'label' => Yii::t('app', 'Phone'),
            ],
            'email',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($name, $model, $key) use ($carrierId) {
                        return Html::a('<i class="fas fa-pencil-alt" aria-hidden="true"></i>', '#', [
                            'class' => 'js-ajax-modal',
                            'data-url' => Url::toRoute(['carrier/contact-form', 'carrier' => $carrierId, 'id' => $model->id])
                        ]);
                    },
                    'delete' => function ($name, $model, $key) use ($carrierId) {
                        return Html::a('<i class="fas fa-trash" aria-hidden="true"></i>',
                            ['contact-delete', 'id' => $model->id, 'carrier' => $carrierId],
                            [
                                'data' => [
                                    'confirm' => 'Are you sure to delete this contact?',
                                    'method' => 'post'
                                ]
                            ]
                        );
                    }
                ],
            ],
        ],
    ]); ?>

    <?php $this->endBlock(); ?>

    <?php $activeTab = Yii::$app->request->get('tab', 'company'); ?>
	<?php echo Tabs::widget([
		'encodeLabels' => false,
		'items' => [
			[
				'label' => Yii::t('app', 'Company'),
				'content' => $this->blocks['company'],
				'active' => ($activeTab == 'company'),
			],
			[
			    'label' => Yii::t('app', 'Defaults'),
            ],
			[
			    'label' => Yii::t('app', 'Profile'),
                'content' => $this->blocks['profile'],
                'active' => ($activeTab == 'profile'),
            ],
			[
			    'label' => Yii::t('app', 'Contacts'),
                'content' => $this->blocks['contacts'],
                'active' => ($activeTab == 'contacts'),
                'disabled' => !isset($model->id),
            ],
		]]); ?>

    <hr/>

    <div class="row">
        <div class="col-6">
            <!-- attribute notes -->
            <?php echo $form->field($model, 'notes')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-6">
            <!-- attribute special_instructions -->
            <?php echo $form->field($model, 'special_instructions')->textarea(['rows' => 6]) ?>
        </div>
    </div>
	<?php echo $form->errorSummary($model); ?>
	<?php echo Html::submitButton('<i class="fas fa fa-check"></i> ' . ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')), ['id' => 'save-' . $model->formName(), 'class' => 'btn btn-success']); ?>
	<?php ActiveForm::end(); ?>
</section>
