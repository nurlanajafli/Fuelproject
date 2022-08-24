<?php
/**
 * @var \common\components\View $this
 * @var \common\models\Payroll $model
 * @var array $formConfig
 * @var array $gridConfig
 * @var array $journalPostings
 */

use common\widgets\DataTables\Grid;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->beginBlock('tb');
?>
    <div class="edit-form-toolbar">
        <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Link"><i
                    class="fas fa-arrow-left"></i></button>
        <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Link"><i
                    class="fas fa-arrow-right"></i></button>
        <button class="btn btn-link js-ajax-modal" data-tooltip="tooltip" data-placement="top" data-url="<?php echo Url::toRoute(['pay-calculator/import-dispatch-pay', 'id' => $model->id]) ?>">
            <i class="fas fa-arrow-down"></i>
        </button>
        <?php if (!$model->posted) : ?>
        <button class="btn btn-link js-ajax-modal" data-tooltip="tooltip" data-placement="top" data-url="<?php echo Url::toRoute(['payroll-adjustment/create', 'id' => $model->id]) ?>">
            <i class="fas fa-pen"></i>
        </button>
        <?php endif; ?>
        <?php if (!$model->posted) : ?>
        <button class="btn btn-link js-submit" data-tooltip="tooltip" data-placement="top" data-id="save">
            <i class="fas fa-save"></i>
        </button>
        <a class="btn btn-link" href="<?php echo Url::toRoute(['pay-calculator/delete', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top">
            <i class="fas fa-trash-alt"></i>
        </a>
        <?php endif; ?>
    </div>
<?php
$this->endBlock();

$this->beginBlock('paySummary');
$form = ActiveForm::begin(ArrayHelper::merge($formConfig, [
    'id' => 'payroll-calculator-form',
    'fieldConfig' => [
        'options' => ['class' => 'form-group row'],
        'template' => $template = '<div class="col-sm-4">{label}</div><div class="col-sm-5">{input}{error}</div>'
    ]
]));
$array = $model->calcOutcomeFields();
?>
  <div class="row top-table">
    <div class="col-4 cell">
      <div class="row h-100">
        <div class="col">
          <div class="form-fieldset">
            <span class="form-legend"><?php echo Yii::t('app', 'Salary') ?></span>
              <?php echo $form->field($model, 'salary_amount')->
              textInput(['type' => 'number', 'step' => 0.01])->label(Yii::t('app', 'Amount')) ?>
          </div>
          <div class="form-fieldset">
            <span class="form-legend"><?php echo Yii::t('app', 'Hourly Pay') ?></span>
              <?php echo $form->field($model, 'total_hours')->textInput(['type' => 'number', 'step' => 0.01]) ?>
              <?php echo $form->field($model, 'ot_hours')->textInput(['type' => 'number', 'step' => 0.01]) ?>
              <?php echo $form->field($model, 'ot_2_hours')->textInput(['type' => 'number', 'step' => 0.01]) ?>
              <?php echo $form->field($model, 'st_rate', [
                  'template' => $template . '<div class="col-sm-3 text-right" id="payroll-st_rate-c">' . $array['ap'] . '</div>'])->
              textInput(['type' => 'number', 'step' => 0.01]) ?>
              <?php echo $form->field($model, 'ot_rate', [
                  'template' => $template . '<div class="col-sm-3 text-right" id="payroll-ot_rate-c">' . $array['bp'] . '</div>'])->
              textInput(['type' => 'number', 'step' => 0.01]) ?>
              <?php echo $form->field($model, 'ot_2_rate', [
                  'template' => $template . '<div class="col-sm-3 text-right" id="payroll-ot_2_rate-c">' . $array['cp'] . '</div>'])->
              textInput(['type' => 'number', 'step' => 0.01]) ?>
            <div class="form-group row ">
              <div class="col-sm-5 text-center">
                  <?php echo Yii::t('app', 'Total Hourly') ?>
              </div>
              <div class="col-sm-7 text-right" id="payroll-total_hourly-c">
                <?php echo $array['dp'] ?>
              </div>
            </div>
          </div>
          <div class="form-fieldset">
            <span class="form-legend"><?php echo Yii::t('app', 'Other Pay') ?></span>
              <?php $form->fieldConfig['template'] = '<div class="col-sm-5">{label}</div><div class="col-sm-7">{input}{error}</div>'; ?>
              <?php echo $form->field($model, 'description')->dropdownList(\common\enums\OtherPayType::getUiEnums(), ['prompt' => '']) ?>
              <?php echo $form->field($model, 'other_pay_amount')->
              textInput(['type' => 'number', 'step' => 0.01])->label(Yii::t('app', 'Amount')) ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-8 cell">
      <div class="row h-100">
        <div class="col">
            <?php echo Grid::widget($gridConfig) ?>
        </div>
      </div>
    </div>
  </div>
<?php
ActiveForm::end();
$this->endBlock();

$tabItems = [
    [
        'label' => Yii::t('app', 'Pay Summary'),
        'content' => $this->blocks['paySummary'],
        'active' => true
    ]
];

if ($model->posted) {
    $this->beginBlock('journalPostings');
    echo Grid::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $journalPostings['allModels']
        ]),
        'columns' => $journalPostings['columns'],
        'colVis' => false,
        'colReorder' => false,
        'searching' => false,
        'foot' => false,
        'info' => false,
        'order' => [],
        'template' => Yii::$app->params['dt']['templates'][0],
        'attributes' => ['style' => 'margin-bottom:25px!important']
    ]);
    $this->endBlock();

    $tabItems[] = [
        'label' => Yii::t('app', 'Journal Postings'),
        'content' => $this->blocks['journalPostings'],
        'active' => false
    ];
}

$this->beginBlock('content');
?>
  <style>
      .table-payroll-info table {
          width: 100%;
      }

      .edit-form.pay-calc .nav-link:hover {
          border-bottom: solid 1px transparent;
        }
    </style>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-payroll-info">
                <table>
                    <tr>
                        <td>
                            <div class="font-weight-bold"><?php echo Yii::t('app', 'Payroll For') ?></div>
                            <?php echo $model->getPayrollFor() ?>
                        </td>
                        <td>
                            <div class="font-weight-bold"><?php echo Yii::t('app', 'Payable To') ?></div>
                            <?php echo $model->getPayableTo() ?>
                        </td>
                        <td>
                            <div class="font-weight-bold"><?php echo $model->getAttributeLabel('driver_type') ?></div>
                            <?php echo \common\helpers\Utils::abbreviation($model->getCOS()); ?>
                        </td>
                        <td>
                            <div class="font-weight-bold"><?php echo Yii::t('app', 'Bank Acct') ?></div>
                            <?php echo $model->bank_account ?>
                        </td>
                        <td>
                            <div class="font-weight-bold"><?php echo $model->payrollBatch->getAttributeLabel('check_date') ?></div>
                            <?php echo Yii::$app->formatter->asDate($model->payrollBatch->check_date, Yii::$app->params['formats'][0]) ?>
                        </td>
                        <td>
                            <div class="font-weight-bold"><?php echo $model->payrollBatch->getAttributeLabel('period_start') ?></div>
                            <?php echo Yii::$app->formatter->asDate($model->payrollBatch->period_start, Yii::$app->params['formats'][0]) ?>
                        </td>
                        <td>
                            <div class="font-weight-bold"><?php echo $model->payrollBatch->getAttributeLabel('period_end') ?></div>
                            <?php echo Yii::$app->formatter->asDate($model->payrollBatch->period_end, Yii::$app->params['formats'][0]) ?>
                        </td>
                        <td>
                            <div class="font-weight-bold"><?php echo Yii::t('app', 'Batch') ?></div>
                            <?php echo $model->payrollBatch->id ?>
                        </td>
                        <td>
                            <div class="font-weight-bold"><?php echo $model->getAttributeLabel('posted') ?></div>
                            <?php echo Yii::t('app', $model->posted ? 'Yes' : 'No') ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php echo \yii\bootstrap4\Tabs::widget([
        'items' => $tabItems
    ]) ?>
    <div class="row top-table" id="pay-calculator-outcome">
        <?php echo $this->render('_outcome', ['payroll' => $model]); ?>
    </div>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'toolbar' => $this->blocks['tb'],
    'content' => $this->blocks['content'],
    'dialogCssClass' => 'modal-xl',
    'title' => Yii::t('app', 'Pay Calculator'),
    'saveButton' => false,
    'closeButton' => false
]);