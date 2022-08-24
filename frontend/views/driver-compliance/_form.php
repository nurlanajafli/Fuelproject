<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use common\enums\I18nCategory;
use common\helpers\Utils;

/**
 * @var \yii\web\View $this
 * @var \common\models\DriverCompliance $model
 */

$driver = $model->driver;
$prompt = Yii::t('app', 'Select');
?>
                      <section class="edit-form driver-compliance-form">
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'DriverCompliance',
                            'layout' => 'horizontal',
                            'enableClientValidation' => true,
                            'errorSummaryCssClass' => 'error-summary alert alert-danger',
                            'fieldConfig' => Yii::$app->params['activeForm']['fieldConfig']
                        ]);
                        ?>
                        <?php $this->beginBlock('summary') ?>
                            <div class="col-6">
                              <div class="form-fieldset">
                                <div class="col-10 pl-3">
                                  <p class="form-readonly-text"><?= strtoupper("{$driver->first_name}, {$driver->last_name}") ?></p>
                                  <p class="form-readonly-text"><?= strtoupper($driver->address_1) ?></p>
                                  <p class="form-readonly-text"><?= strtoupper($driver->city).', '.$driver->state->state_code.' '.$driver->zip ?></p>
<!--                                  <p class="form-readonly-text">Owner Operator</p>-->
                                  <div class="row">
                                    <div class="col-4">
                                      <p><?=Yii::t('app', 'Phone')?></p>
                                    </div>
                                    <div class="col-8">
                                        <p class="form-readonly-text"><?= $driver->telephone ?></p>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-4">
                                      <p><?=Yii::t('app', 'Cell')?></p>
                                    </div>
                                    <div class="col-8">
                                      <p class="form-readonly-text"><?= $driver->cell_phone ?></p>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-4">
                                      <p><?=Yii::t('app', 'Other')?></p>
                                    </div>
                                    <div class="col-8">
                                        <p class="form-readonly-text"><?= $driver->other_phone ?></p>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-4">
                                      <p><?=Yii::t('app', 'Email')?></p>
                                    </div>
                                    <div class="col-8">
                                      <p class="form-readonly-text"><?= $driver->email_address ?></p>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-4">
                                      <p><?=Yii::t('app', 'Hired')?></p>
                                    </div>
                                    <div class="col-8">
                                      <p class="form-readonly-text"><?= Yii::$app->formatter->asDate($driver->hire_date, Utils::getParam('formatter.date.short')) ?></p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-fieldset"><span class="form-legend"><?= Yii::t('app', 'CDL') ?></span>
                                <div class="col pl-4">
                                  <div class="form-row text-center">
                                    <?= $form->field($model, 'cdl_number', ['options' => ['class' => 'col-5']])->textInput() ?>
                                    <?= $form->field($model, 'cdl_state_id', ['template' => '{label}{input}{error}', 'options' => ['class' => 'col-3']])->dropdownList(
                                        ArrayHelper::map(\common\models\State::find()->all(), 'id', '_label'),
                                        ['prompt' => $prompt]
                                    ) ?>
                                    <?= $form->field($model, 'cdl_expires', ['options' => ['class' => 'col-4']])->textInput(['type' => 'date']) ?>
                                  </div>
                                  <hr>
                                  <div class="form-row align-items-center">
                                    <?= $form->field($model, 'haz_mat', ['options' => ['class' => 'col-3 offset-2']])->checkbox(['template' => '<div class="custom-control custom-checkbox">{input}{label}{error}</div>']) ?>
                                    <div class="col-5">
                                      <?= $form->field($model, 'haz_mat_expires', ['options' => ['class' => 'd-flex align-items-center']])
                                          ->textInput(['type' => 'date', 'class' => 'form-control ml-3'])
                                          ->label($model->getAttributeLabel('haz_mat_expires'), ['class' => 'mb-0']) ?>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-fieldset"><span class="form-legend"><?= Yii::t('app', 'Access') ?></span>
                                <div class="form-row text-center">
                                  <?= $form->field($model, 'ace_id', ['options' => ['class' => 'col-4']])->textInput() ?>
                                  <?= $form->field($model, 'fast_id', ['options' => ['class' => 'col-4']])->textInput() ?>
                                  <?= $form->field($model, 'twic_exp', ['options' => ['class' => 'col-4']])->textInput(['type' => 'date']) ?>
                                </div>
                              </div>
                              <div class="form-fieldset"><span class="form-legend"><?= Yii::t('app', 'Activity Tracking') ?></span>
                                <div class="form-row text-center mb-3">
                                  <?= $form->field($model, 'last_drug_test', ['options' => ['class' => 'col-3']])->textInput(['type' => 'date', 'class' => 'form-control text-center']) ?>
                                  <?= $form->field($model, 'last_alcohol_test', ['options' => ['class' => 'col-3']])->textInput(['type' => 'date', 'class' => 'form-control text-center']) ?>
                                  <?= $form->field($model, 'next_dot_physical', ['options' => ['class' => 'col-3']])->textInput(['type' => 'date', 'class' => 'form-control text-center']) ?>
                                  <?= $form->field($model, 'work_auth_expires', ['options' => ['class' => 'col-3']])->textInput(['type' => 'date', 'class' => 'form-control text-center']) ?>
                                </div>
                                <div class="form-row text-center">
                                  <?= $form->field($model, 'next_ffd_evaluation', ['options' => ['class' => 'col-3']])->textInput(['type' => 'date', 'class' => 'form-control text-center']) ?>
                                  <?= $form->field($model, 'next_h2s_certification', ['options' => ['class' => 'col-3']])->textInput(['type' => 'date', 'class' => 'form-control text-center']) ?>
                                  <?= $form->field($model, 'next_vio_review', ['options' => ['class' => 'col-3']])->textInput(['type' => 'date', 'class' => 'form-control text-center']) ?>
                                  <?= $form->field($model, 'next_mvr_review', ['options' => ['class' => 'col-3']])->textInput(['type' => 'date', 'class' => 'form-control text-center']) ?>
                                </div>
                              </div>
                            </div>
                            <div class="col-6"></div>
                        <?php $this->endBlock() ?>

                        <?php $this->beginBlock('claims-and-accidents') ?>
                            <div class="col-12"></div>
                        <?php $this->endBlock() ?>

                        <?php $this->beginBlock('log-violations') ?>
                            <div class="col-12"></div>
                        <?php $this->endBlock() ?>

                        <?php echo \yii\bootstrap4\Tabs::widget([
                            'encodeLabels' => false,
                            'items' => [
                                [
                                    'label' => Yii::t('app', 'Summary'),
                                    'content' => $this->blocks['summary'],
                                    'active' => true
                                ],
                                [
                                    'label' => Yii::t('app', 'Claims & Accidents'),
                                    'content' => $this->blocks['claims-and-accidents'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Log Violations'),
                                    'content' => $this->blocks['log-violations'],
                                ],
                            ],
                        ]); ?>
                        <?= \yii\helpers\Html::submitButton('<i class="fas fa fa-check"></i> ' . Yii::t('app', 'Save Changes'),
                            ['id' => 'save-' . $model->formName(), 'class' => 'btn btn-success']) ?>
                        <?php ActiveForm::end() ?>
                      </section>