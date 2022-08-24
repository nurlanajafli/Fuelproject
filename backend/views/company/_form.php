<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\Company $model
 * @var \yii\widgets\ActiveForm $form
*/

use common\enums\BusinessType;
use common\enums\CompanyThumb;
use common\models\BusinessDirection;
use common\models\State;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $this->beginBlock('companyForm');
                $form = ActiveForm::begin([
                    'id' => 'Company',
                    'layout' => 'horizontal',
                    'enableClientValidation' => true,
                    'errorSummaryCssClass' => 'error-summary alert alert-danger',
                    'fieldConfig' => [
                        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                        'horizontalCssClasses' => [
                            'label' => 'col-sm-3',
                            #'offset' => 'col-sm-offset-4',
                            'wrapper' => 'col-sm-9',
                            'error' => '',
                            'hint' => '',
                        ],
                    ],
                    'options' => ['enctype' => 'multipart/form-data']
                ]); ?>
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center">
                                    <?= Yii::t('app', 'Address, Contact and Business Information') ?>
                                </div>
                                <div class="panel-body">
                                    <div class="form-horizontal">
                                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'address_1')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'address_2')->textInput(['maxlength' => true]) ?>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label"><?= Yii::t('app', 'City, St, Zip') ?></label>
                                            <?= $form->field($model, 'city', ['template' => '<div class="col-sm-4">{input}{error}</div>', 'options' => ['tag' => false]])->textInput(['maxlength' => true]) ?>
                                            <?= $form->field($model, 'state_id', ['template' => '<div class="col-sm-2" style="padding: 0">{input}{error}</div>', 'options' => ['tag' => false]])
                                                ->dropDownList(
                                                    ArrayHelper::map(State::find()->all(), 'id', '_label'),
                                                    [
                                                        'prompt' => Yii::t('app', 'Select'),
                                                        'disabled' => isset($relAttributes) && isset($relAttributes['state_id'])
                                                    ]
                                                ) ?>
                                            <?= $form->field($model, 'zip', ['template' => '<div class="col-sm-3">{input}{error}</div>', 'options' => ['tag' => false]])->textInput(['maxlength' => true]) ?>
                                        </div>
                                        <?php
                                        $form->fieldConfig['horizontalCssClasses']['wrapper'] = 'col-sm-4';
                                        ?>
                                        <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'main_phone')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'main_800')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'main_fax')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'accounting_phone')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'ar_contact')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'ap_contact', ['options' => ['class' => 'form-group lg-mb-10']])->textInput(['maxlength' => true]) ?>
                                        <br>
                                        <?= $form->field($model, 'business_type')->dropDownList(
                                            BusinessType::getUiEnums()
                                        ) ?>
                                        <?= $form->field($model, 'federal_id')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'dot_id')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'mc_id')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'scac')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center"><?= Yii::t('app', 'Your Lines Of Business') ?></div>
                                <div class="panel-body">
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-sm-6"></div>
                                            <div class="col-sm-3 text-primary text-center"><?= Yii::t('app', 'Allowed') ?></div>
                                            <div class="col-sm-3 text-primary text-center"><?= Yii::t('app', 'Primary') ?></div>
                                        </div>
                                        <?= $form->field($model, 'business_direction_id', ['template' => '{input}', 'options' => ['tag' => false]])->hiddenInput(['value' => 0]) ?>
                                        <?php
                                        $string = 'AllowedBusinessDirection';
                                        $allowedBusinessDirections = ArrayHelper::map($model->companyBusinessDirections, 'id', 'business_direction_id');
                                        /** @var BusinessDirection[] $businessDirections */
                                        $businessDirections = BusinessDirection::find()->all();
                                        foreach ($businessDirections as $businessDirection) {
                                        ?>
                                        <div class="form-group">
                                            <label class="col-sm-6 text-right"><?= Yii::t('app', $businessDirection->name) ?></label>
                                            <div class="col-sm-3 text-center">
                                                <?= Html::checkbox("{$string}[{$businessDirection->id}]", ArrayHelper::isIn($businessDirection->id, $allowedBusinessDirections)) ?>
                                            </div>
                                            <div class="col-sm-3 text-center">
                                                <?= $form->field($model, 'business_direction_id', ['template' => '{input}', 'options' => ['tag' => false]])
                                                    ->radio(['value' => $businessDirection->id, 'uncheck' => null], false) ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading text-center"><?= Yii::t('app', 'Company Logo File') ?></div>
                                <div class="panel-body">
                                    <div class="form-group col-sm-10 form-inline">
                                        <?= $form->field($model, $imageAttribute = $model->getImageAttribute(), ['template' => '{label}{input}', 'options' => ['tag' => false]])->fileInput()->label(Yii::t('app', 'Upload Logo'), ['class' => '']) ?>
                                    </div>
                                    <?php if ($model->$imageAttribute): ?>
                                    <div class="form-group col-sm-10">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="delete_logo" value="1"<?php if (isset($_POST['delete_logo'])) echo ' checked="checked"'; ?>> <?= Yii::t('app', 'Remove') ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <div class="col-sm-10">
                                            <?php if ($model->$imageAttribute):
                                            echo Html::img($model->getThumbUploadUrl($imageAttribute, CompanyThumb::PREVIEW), ['class' => 'logo-preview']);
                                            endif; ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-default js-ajax-modal" data-css-class="modal-upload-info" data-url="<?= Url::toRoute('logo-requirements') ?>" type="button">?</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $form->errorSummary($model); ?>
                    <?php echo Html::submitButton(
                        '<span class="glyphicon glyphicon-check"></span> ' .
                        ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')),
                        [
                            'id' => 'save-' . $model->formName(),
                            'class' => 'btn btn-success'
                        ]
                    );
                    ?>
                <?php
                ActiveForm::end();
                $this->endBlock();
                echo preg_replace('/form-horizontal/', '', $this->blocks['companyForm'], 1);
                ?>
            </div>
        </div>
    </div>
</div>