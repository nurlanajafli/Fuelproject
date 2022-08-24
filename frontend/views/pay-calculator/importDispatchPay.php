<?php
/**
 * @var \common\components\View $this
 * @var \frontend\forms\payCalculator\ImportDispatchPay $model
 * @var array $formConfig
 * @var array $gridConfig
 * @var array $gridConfigCo
 * @var bool $saveBtn
 */

use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

$this->beginBlock('tb');
?>
<div class="edit-form-toolbar">
    <button class="btn btn-link js-submit" data-tooltip="tooltip" data-placement="top" data-id="save">
        <i class="fas fa-save"></i>
    </button>
</div>
<?php
$this->endBlock();
$this->beginBlock('form');
$form = ActiveForm::begin(ArrayHelper::merge($formConfig, ['options' => ['data-cb' => 'payrollPayCalcImportCb']]));
echo $form->field($model, 'ids', ['options' => ['class' => 'd-none']])->dropdownList(ArrayHelper::map($gridConfig['dataProvider']->getModels(), 'id', 'id'), ['multiple' => 'multiple']);
ActiveForm::end();
echo \common\widgets\DataTables\Grid::widget($gridConfig);
?>
    <table class="w-50 m-3" id="importdispatchpay-sum">
        <tbody class="text-center">
        <tr>
            <td></td>
            <td>Loaded Miles</td>
            <td>Empty Miles</td>
            <td>Total Pay</td>
        </tr>
        <tr>
            <td class="text-right">Listed</td>
            <td class="form-readonly-text"><?php echo Yii::$app->formatter->asInteger(0) ?></td>
            <td class="form-readonly-text"><?php echo Yii::$app->formatter->asInteger(0) ?></td>
            <td class="form-readonly-text"><?php echo Yii::$app->formatter->asDecimal(0) ?></td>
        </tr>
        <tr>
            <td class="text-right">Selected</td>
            <td class="form-readonly-text"><?php echo Yii::$app->formatter->asInteger(0) ?></td>
            <td class="form-readonly-text"><?php echo Yii::$app->formatter->asInteger(0) ?></td>
            <td class="form-readonly-text"><?php echo Yii::$app->formatter->asDecimal(0) ?></td>
        </tr>
        </tbody>
    </table>
<?php
$this->endBlock();

echo \common\widgets\ModalForm\ModalForm::widget([
    'toolbar' => $saveBtn ? $this->blocks['tb'] : '',
    'content' => $this->blocks['form'],
    'cssClass' => 'modal--no-padding',
    'dialogCssClass' => 'modal-full',
    'bodyCssClass' => 'p-0',
    'title' => Yii::t('app', 'Import Dispatch Pay'),
    'closeButton' => false,
    'saveButton' => false,
]);
