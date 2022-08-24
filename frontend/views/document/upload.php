<?php
/**
 * @var \yii\web\View $this
 * @var \frontend\forms\document\Upload $model
 * @var array $formConfig
 * @var int $id
 */

use yii\bootstrap4\ActiveForm;
use common\models\DocumentCode;

$this->beginBlock('content');
$formConfig['options']['class'] .= ' custom-file-container';

$documentModel = new \common\models\Document();
?>
    <div class="form-fieldset mb-3">
        <?php $form = ActiveForm::begin(\yii\helpers\ArrayHelper::merge($formConfig, [
            'options' => ['data-upload-id' => 'fileUploadWithPreview', 'data-cb' => 'documentUploadOK']
        ])); ?>
            <?php if ($model->getScenario() == \frontend\forms\document\Upload::SCENARIO_LOAD): ?>
            <div class="form-group row field">
                <div class="col-2">
                    <label><?= Yii::t('app', 'Source') ?></label>
                </div>
                <div class="col-4"><span class="form-readonly-text"><?= Yii::t('app', 'Loads') ?></span></div>
            </div>
            <div class="form-group row field">
                <div class="col-2">
                    <label><?= Yii::t('app', 'Load No') ?></label>
                </div>
                <div class="col-4"><span class="form-readonly-text"><?= $id ?></span></div>
            </div>
            <?= $form->field($model, 'code', ['template' => '<div class="col-2">{label}</div><div class="col-4">{input}{error}</div>', 'options' => ['class' => 'form-group row']])->widget(\common\widgets\tdd\Dropdown::class, [
                    'items' => DocumentCode::find()->all(),
                    'modelClass' => DocumentCode::class,
                    'grid' => [
                        'columns' => [
                            'code',
                            'description'
                        ],
                        'order' => [[0, 'asc']]
                    ]
                ]) ?>
            <?php endif ?>
            <label><?= Yii::t('app', 'Upload File') ?></label>
            <div class="field justify-content-between">
                <label class="custom-file-container__custom-file">
                    <?= $form->field($documentModel, $documentModel->getImageAttribute(), ['options' => ['tag' => false]])->fileInput([
                        'class' => 'custom-file-container__custom-file__custom-file-input',
                        'aria-label' => Yii::t('app', 'Choose File')
                    ])->label(false) ?>
                    <span class="custom-file-container__custom-file__custom-file-control" data-file-choose="<?= Yii::t('app', 'Choose image...') ?>" data-file-browse="<?= Yii::t('app', 'Select') ?>" data-file-selected="<?= Yii::t('app', ' files selected') ?>"></span>
                </label>
                <label><a class="custom-file-container__image-clear btn btn-outline-danger" href="javascript:void(0)"
                          title="<?= Yii::t('app', 'Clear Image') ?>"><?= Yii::t('app', 'Clear') ?></a></label>
            </div>
            <?= $form->field($model, 'fakeFile', ['template' => '{input}{error}'])->textInput(['style' => 'display: none;']) ?>
            <a class="custom-file-container__image-preview js-lg-single"></a>
            <?= ($model->getScenario() == \frontend\forms\document\Upload::SCENARIO_LOAD) ? '' : $form->field($model, 'description', ['labelOptions' => ['class' => 'col-form-label']])->textarea(['rows' => 4]) ?>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget(
    [
        'id' => 'js-file-upload',
        'dialogCssClass' => 'modal-lg',
        'title' => Yii::t('app', 'Image Upload'),
        'content' => $this->blocks['content']
    ]
);