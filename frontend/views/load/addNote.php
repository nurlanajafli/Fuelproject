<?php
/**
 * @var \yii\web\View $this
 * @var \frontend\forms\load\AddNote $model
 * @var array $formConfig
 */

use common\enums\I18nCategory;
use common\models\LoadNoteType;
use common\widgets\ModalForm\ModalForm;
use yii\bootstrap4\ActiveForm;

$this->beginBlock('content');
$formConfig['options']['class'] .= ' modal-form';
$formConfig['options']['data-cb'] = 'automaticNoteListReload';
ModalForm::registerAjaxReloadCallback('.js-load-notes-table', $this, $formConfig);
$form = ActiveForm::begin($formConfig);
?>
    <div class="col-6">
        <div class="form-group">
            <label><?= $model->getAttributeLabel('date') ?></label>
            <div class="input-group">
                <?= $form->field($model, 'date', ['template' => '{input}', 'options' => ['tag' => false]])->textInput(['type' => 'date']) ?>
                <?= $form->field($model, 'time', ['template' => '{input}', 'options' => ['tag' => false]])->textInput(['data-mask' => '00:00', 'placeholder' => '00:00']) ?>
            </div>
        </div>
        <?php
        $items = [];
        $rows = LoadNoteType::find()->all();
        foreach ($rows as $row) {
            /** @var LoadNoteType $row */
            $items[$row->id] = $row->description;
        }
        echo $form->field($model, 'lastAction')->dropDownList($items, ['prompt' => Yii::t('app', 'Select')]);
        ?>
    </div>
    <div class="col">
        <div class="form-group">
            <div class="label"><?= $model->getAttributeLabel('notes') ?></div>
            <?= $form->field($model, 'notes', ['template' => '{input}{error}', 'options' => ['tag' => false]])->textarea(['rows' => 5]) ?>
        </div>
    </div>
<?php
ActiveForm::end();
$this->endBlock();
echo ModalForm::widget([
    'title' => Yii::t('app', 'Load Notes'),
    'content' => $this->blocks['content'],
    'options' => [
        'dialog' => [
            'class' => 'modal-lg'
        ]
    ]
]);