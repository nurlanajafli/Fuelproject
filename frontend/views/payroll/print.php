<?php
/**
 * @var \common\components\View $this
 * @var \frontend\forms\payroll\PrintSettlements $model
 * @var array $formConfig
 * @var \frontend\forms\payroll\Filter $filter
 */

use common\models\Payroll;
use common\widgets\DataTables\Grid;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap4\Html;
use common\models\PayrollBatch;

$this->beginBlock('form');
$form = ActiveForm::begin($formConfig);
echo $form->field($model, 'ids', ['options' => ['class' => 'd-none']])->dropdownList(ArrayHelper::map(
    Payroll::find()->all(), 'id', 'id'
), ['multiple' => 'multiple']);
ActiveForm::end();
?>
    <div class="row">
        <div class="col-3 p-4 border-right">
            <form id="payroll-print-radio__wrapper">
                <div class="custom-control custom-radio">
                    <input class="form-check-input" type="radio" value="#" id="custom-radio" name="print-settlements-radio-buttons" checked>
                    <label class="form-check-label" for="custom-radio"><?= Yii::t('app', 'Batch No') ?></label>
                </div>
                <div class="custom-control custom-radio">
                    <input class="form-check-input" type="radio" value="#" id="custom-radio-1" name="print-settlements-radio-buttons">
                    <label class="form-check-label" for="custom-radio-1"><?= Yii::t('app', 'Date Range') ?></label>
                </div>
            </form>
        </div>
        <div class="col-9 p-4" id="payroll-print-toolbar__wrapper" data-url="<?= Url::toRoute('index') ?>">

            <?php
            $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'enableAjaxValidation' => false
            ]);
            echo '<div class="dt-toolbar">';
            echo $form->field($filter, 'batchId')->dropdownList(ArrayHelper::map(PayrollBatch::find()->all(), 'id', 'id'), [
                'prompt' => '', 'class' => 'custom-select'
            ]);
            echo '</div>';
            ActiveForm::end();

            $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'enableAjaxValidation' => false,
                'options' => ['class' => 'dt-toolbar', 'style' => 'display: none;']
            ]);
            echo $form->field($filter, 'batchType')->dropdownList(\common\enums\PayrollBatchType::getUiEnums(), [
                'prompt' => '', 'class' => 'custom-select'
            ]);
            echo $form->field($filter, 'officeId')->widget(\common\widgets\tdd\Dropdown::class, [
                'grid' => [
                    'dataProvider' => new \yii\data\ActiveDataProvider([
                        'query' => \common\models\Office::find()->orderBy('id'),
                        'pagination' => false
                    ]),
                    'columns' => ['id', 'office']
                ]
            ]);
            echo '<div class="js-daterange d-flex" data-date-format="M/D/YYYY">';
            echo $form->field($filter, 'from')->textInput(['class' => 'form-control js-daterange-min', 'autocomplete' => 'off']);
            echo $form->field($filter, 'to')->textInput(['class' => 'form-control js-daterange-max', 'autocomplete' => 'off']);
            echo '</div>';
            ActiveForm::end();

            echo Grid::widget([
                'ajax' => Url::toRoute(['index', $filter->formName() . '[batchId]' => $filter->batchId]),
                'columns' => ['id|visible=false', 'payrollFor|title=Payroll For', 'settlements|title=Settlements|className=text-center'],
                'colVis' => false,
                'colReorder' => null,
                'info' => false,
                'buttons' => [
                    ['text' => '<i class="fas fa-print"></i>', 'action' => 'js:payrollPrintBtnClick'],
                    Grid::BUTTON_SELECT,
                    Grid::BUTTON_DESELECT,
                ],
                'select' => ['style' => 'multi', 'items' => 'row'],
                'paging' => false,
                'searching' => false,
                'id' => 'dt-payroll-print',
                'order' => [[0, 'asc']],
                'template' => Yii::$app->params['dt']['templates'][0],
                'orderCellsTop' => true,
                'conditionalPaging' => true,
                'autoWidth' => false
            ]);
            ?>
            <button class="d-none js-submit" id="payroll-print-btn">Print</button>
        </div>
    </div>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Print Settlements'),
    'content' => $this->blocks['form'],
    'cssClass' => 'modal--no-padding modal--no-scroll',
    'dialogCssClass' => 'modal-xl',
    'closeButton' => false,
    'saveButton' => false
]);
