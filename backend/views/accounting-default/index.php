<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\AccountingDefault $model
 */

use dmstr\bootstrap\Tabs;
use common\widgets\tdd\Dropdown;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use common\models\Account;
use common\models\PaymentTermCode;
use yii\helpers\ArrayHelper;
use common\models\InvoiceItemCode;
use yii\db\Expression;
use common\models\State;
use common\enums\DateType;
use common\models\Vendor;
use \common\enums\CheckPrinting;

$widgetConfig = function ($type) {
    return [
        'grid' => [
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => Account::getFilterByType($type, true),
                'pagination' => false
            ]),
            'columns' => [
                'account',
                'description|title=Desc',
                'account_type|rel=accountType.type'
            ],
            'order' => [[0, 'asc']]
        ]
    ];
};

$this->title = Yii::t('app', 'Accounting Default');
$this->params['breadcrumbs'][] = $this->title;
$prompt = Yii::t('app', 'Select');
?>
<style>
    .the-legend {
        border-style: none;
        border-width: 0;
        font-size: 14px;
        line-height: 20px;
        margin-bottom: 0;
        width: auto;
        padding: 0 10px;
    }

    .the-fieldset {
        border: 1px solid #555;
        padding: 10px;
    }

    .the-fieldset ~ .the-fieldset,
    .tab-pane > .row + .row,
    .the-fieldset + .row {
        margin-top: 10px;
    }

</style>
<?php $form = ActiveForm::begin([
        'id' => 'AccountingDefault',
        'layout' => 'horizontal',
        'enableClientValidation' => true,
        'errorSummaryCssClass' => 'error-summary alert alert-danger',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'options' => ['class' => 'form-group row'],
            'labelOptions' => ['class' => 'col-sm-3'],
            'horizontalCssClasses' => [
                //'label' => 'col-sm-3',
                //'offset' => 'col-sm-offset-4',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]
);
?>
<?php $this->beginBlock('general'); ?>
<hr/>
<fieldset class="the-fieldset">
    <legend class="the-legend"><?= Yii::t('app', 'Defaults') ?></legend>
    <?= $form->field($model, 'default_unclassified_account_id')->widget(Dropdown::class, $widgetConfig(['Expense', 'Cost Of Sales'])) ?>
    <?= $form->field($model, 'default_escrow_holding_account_id')->widget(Dropdown::class, $widgetConfig('Current Liability')) ?>
    <?= $form->field($model, 'default_advances_issued_account_id')->widget(Dropdown::class, $widgetConfig('Current Asset')) ?>
    <div class="form-group row"></div>
    <?= $form->field($model, 'fiscal_year_start_month')->dropDownList(\common\enums\Month::getUiEnums(), [
        'prompt' => $prompt
    ]) ?>
    <?= $form->field($model, 'require_office_on_all_transactions')->checkbox([], false) ?>
</fieldset>
<fieldset class="the-fieldset">
    <legend class="the-legend"><?= Yii::t('app', 'Maintenance') ?></legend>
    <?= $form->field($model, 'default_work_order_income_account_id')->widget(Dropdown::class, $widgetConfig(['Income', 'Other Income'])) ?>
    <?= $form->field($model, 'default_parts_inventory_account_id')->widget(Dropdown::class, $widgetConfig(['Current Asset', 'Fixed Asset'])) ?>
    <?= $form->field($model, 'default_labor_cost_account_id')->widget(Dropdown::class, $widgetConfig(['Expense', 'Cost Of Sales'])) ?>
    <?= $form->field($model, 'default_misc_cost_account_id')->widget(Dropdown::class, $widgetConfig(['Expense', 'Cost Of Sales'])) ?>
</fieldset>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('receivables'); ?>
<hr/>
<?= $form->field($model, 'primary_ar_account_id')->widget(Dropdown::class, $widgetConfig('Accounts Receivable')) ?>
<?= $form->field($model, 'default_invoice_payment_terms')->dropDownList(ArrayHelper::map(PaymentTermCode::find()->all(), 'id', 'description'), [
    'prompt' => $prompt
]) ?>
<?= $form->field($model, 'default_invoice_write_off_account_id')->widget(Dropdown::class, $widgetConfig(['Expense', 'Cost Of Sales', 'Income', 'Other Income'])) ?>
<?= $form->field($model, 'default_invoice_discounts_account_id')->widget(Dropdown::class, $widgetConfig(['Expense', 'Cost Of Sales', 'Income', 'Other Income'])) ?>
<?= $form->field($model, 'default_unapplied_funds_account_id')->widget(Dropdown::class, $widgetConfig(['Current Asset', 'Fixed Asset', 'Current Liability'])) ?>
<?= $form->field($model, 'default_income_account_id')->widget(Dropdown::class, $widgetConfig(['Income', 'Other Income'])) ?>
<?= $form->field($model, 'default_invoice_item_code')->dropDownList(ArrayHelper::map(InvoiceItemCode::find()->all(), 'id', 'description'), [
    'prompt' => $prompt
]) ?>
<hr/>
<?= $form->field($model, 'name_on_factored_invoices_car_id')->widget(Dropdown::class, [
    'grid' => [
        'dataProvider' => new \yii\data\SqlDataProvider([
            'sql' => \common\models\Carrier::find()
                ->select(new Expression('t0.id,t0.name,t0.address_1,t0.address_2,t0.city,state_code,0 AS type'))
                ->alias('t0')
                ->leftJoin(State::tableName(), sprintf('t0.state_id=%s.id', State::tableName()))
                ->union(\common\models\Customer::find()
                    ->select(new Expression('t0.id,t0.name,t0.address_1,t0.address_2,t0.city,state_code,1 AS type'))
                    ->alias('t0')
                    ->leftJoin(State::tableName(), sprintf('t0.state_id=%s.id', State::tableName()))
                )->union(Vendor::find()
                    ->select(new Expression('t0.id,t0.name,t0.address_1,t0.address_2,t0.city,state_code,2 AS type'))
                    ->alias('t0')
                    ->leftJoin(State::tableName(), sprintf('t0.state_id=%s.id', State::tableName()))
                )->createCommand()->getRawSql()
        ]),
        'columns' => [
            'type|visible=false|searchable=false',
            'id|visible=false|searchable=false',
            'name',
            'address_1|coalesce=address_2|title=Address',
            'city',
            'state_code|title=State',
            new \common\widgets\DataTables\DataColumn([
                'title' => Yii::t('app', 'Type'),
                'value' => function ($model) {
                    return Yii::t('app', ['Carrier', 'Customer', 'Vendor'][$model->type]);
                }
            ])
        ],
        'order' => [[2, 'asc']],
    ],
    'destAttribute' => [['name_on_factored_invoices_car_id' => 0, 'name_on_factored_invoices_cus_id' => 1, 'name_on_factored_invoices_ven_id' => 2], 0, 1],
    'displayColumnIndex' => 2
]) ?>
<?= $form->field($model, 'factoring_account_id')->textInput(['maxlength' => true]) ?>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('invoicing'); ?>
<hr/>
<fieldset class="the-fieldset">
    <legend class="the-legend"><?= Yii::t('app', 'Invoicing Defaults') ?></legend>
    <div class="row">
        <div class="col-xs-7">
            <?= $form->field($model, 'default_invoice_process')->dropDownList(\common\enums\InvoiceProcess::getUiEnums(), [
                'prompt' => $prompt
            ]) ?>
            <?= $form->field($model, 'default_print_paper_type')->dropDownList(\common\enums\PrintPaperType::getUiEnums(), [
                'prompt' => $prompt
            ]) ?>
            <?= $form->field($model, 'default_invoice_print_style')->dropDownList(\common\enums\InvoiceStyle::getUiEnums(), [
                'prompt' => $prompt
            ]) ?>
            <?= $form->field($model, 'default_invoice_aging_date')->dropDownList(DateType::getUiEnums(), [
                'prompt' => $prompt
            ]) ?>
            <?= $form->field($model, 'default_invoice_posting_date')->dropDownList(DateType::getUiEnums(), [
                'prompt' => $prompt
            ]) ?>
            <?= $form->field($model, 'default_date_for_group_invoices')->dropDownList(array_filter(DateType::getUiEnums(), function ($key) {
                return ArrayHelper::isIn($key, [DateType::SYSTEM_DATE, DateType::USER_DEFINED_DATE]);
            }, ARRAY_FILTER_USE_KEY), [
                'prompt' => $prompt
            ]) ?>
            <?= $form->field($model, 'apr_on_overdue_invoices')->textInput(['maxlength' => true, 'type' => 'number', 'min' => 0, 'step' => 0.001]) ?>
        </div>
        <div class="col-xs-5">
            <?= $form->field($model, 'include_load_notes_on_invoices', ['labelOptions' => ['class' => 'col-sm-6'], 'horizontalCssClasses' => ['wrapper' => 'col-sm-1']])->checkbox([], false) ?>
            <?= $form->field($model, 'hide_header_information_on_invoices', ['labelOptions' => ['class' => 'col-sm-6'], 'horizontalCssClasses' => ['wrapper' => 'col-sm-1']])->checkbox([], false) ?>
            <?= $form->field($model, 'show_trucks_and_trailers_on_invoices', ['labelOptions' => ['class' => 'col-sm-6'], 'horizontalCssClasses' => ['wrapper' => 'col-sm-1']])->checkbox([], false) ?>
            <?= $form->field($model, 'show_load_details_on_third_party_invoices', ['labelOptions' => ['class' => 'col-sm-6'], 'horizontalCssClasses' => ['wrapper' => 'col-sm-1']])->checkbox([], false) ?>
            <?= $form->field($model, 'post_invoice_notes_when_emailing_invoices', ['labelOptions' => ['class' => 'col-sm-6'], 'horizontalCssClasses' => ['wrapper' => 'col-sm-1']])->checkbox([], false) ?>
        </div>
    </div>
</fieldset>
<div class="row">
    <div class="col-xs-5">
        <fieldset class="the-fieldset">
            <legend class="the-legend">Income Accounts by Load Type</legend>
            <div class="row">
                <div class="col-sm-12">
                    <select class="form-control">
                        <option></option>
                        <option value="1">Income</option>
                        <option value="2">Outcome</option>
                        <option value="3">All</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Load Type</th>
                            <th>Account</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="active">
                            <td>Type 1</td>
                            <td>Account 1</td>
                        </tr>
                        <tr>
                            <td>Type 2</td>
                            <td>Account 2</td>
                        </tr>
                        <tr class="success">
                            <td>Type 3</td>
                            <td>Account 3</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="col-xs-7">
        <fieldset class="the-fieldset">
            <legend class="the-legend">Destribution</legend>
            <div class="row">
                <label class="col-sm-12" for="text-display-on-emailed-printed">Text To Display on Emailed / Printed
                    Invoice</label>
                <div class="col-sm-12">
                    <textarea class="form-control" id="text-display-on-emailed-printed" rows="5"></textarea>
                </div>
            </div>
        </fieldset>
        <fieldset class="the-fieldset">
            <legend class="the-legend">
                <label>
                    Factor Invoices <input type="checkbox">
                </label>
            </legend>
            <div class="row">
                <label class="col-sm-12">
                    <input type="radio" name="export-type" value="file"> Export To File
                </label>
                <label class="col-sm-12">
                    <input type="radio" name="export-type" value="ftp"> Export To FTP
                </label>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group row">
                        <label class="col-sm-12" for="ftp-address">FTP Address</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="ftp-address">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group row">
                        <label class="col-sm-12" for="ftp-user">FTP User</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="ftp-user">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group row">
                        <label class="col-sm-12" for="ftp-password">FTP Password</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="ftp-password">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-12" for="text-to-print-on-factored">Text To Print On Factored Invoices</label>
                <div class="col-sm-12">
                    <textarea class="form-control" id="text-to-print-on-factored" rows="5"></textarea>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('payables'); ?>
<hr/>
<?= $form->field($model, 'primary_ap_account_id')->widget(Dropdown::class, $widgetConfig('Accounts Payable')) ?>
<?= $form->field($model, 'default_bill_payment_terms')->dropDownList(ArrayHelper::map(PaymentTermCode::find()->all(), 'id', 'description'), [
    'prompt' => $prompt
]) ?>
<?= $form->field($model, 'default_bill_write_off_account_id')->widget(Dropdown::class, $widgetConfig(['Expense', 'Cost Of Sales', 'Income', 'Other Income'])) ?>
<?= $form->field($model, 'default_bill_discounts_account_id')->widget(Dropdown::class, $widgetConfig(['Expense', 'Cost Of Sales', 'Income', 'Other Income'])) ?>
<?= $form->field($model, 'default_carrier_expense_account_id')->widget(Dropdown::class, $widgetConfig(['Expense', 'Cost Of Sales'])) ?>
<?= $form->field($model, 'do_not_pay_carriers_for_uninvoiced_loads')->checkbox([], false) ?>
<?= $form->field($model, 'default_ven_iss_adv')->widget(Dropdown::class, [
    'grid' => [
        'columns' => [
            'id|visible=false|searchable=false',
            'name',
            'address_1|coalesce=address_2|title=Address',
            'city',
            'state_id|title=St|rel=state.state_code'
        ],
        'order' => [[1, 'asc']],
    ],
    'items' => Vendor::find()->joinWith('state')->all(),
    'modelClass' => Vendor::class,
]) ?>
<?= $form->field($model, 'deduct_open_adv_pay_carriers')->checkbox([], false) ?>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('banking'); ?>
<hr/>
<?= $form->field($model, 'default_check_writing_bank_account_id')->widget(Dropdown::class, $widgetConfig('Bank')) ?>
<?= $form->field($model, 'default_undeposited_funds_account_id')->widget(Dropdown::class, $widgetConfig(['Bank', 'Current Asset'])) ?>
<fieldset class="the-fieldset">
    <legend class="the-legend"><?= $model->getAttributeLabel('check_printing') ?></legend>
    <?= $form->field($model, 'check_printing', ['options' => ['class' => 'row'], 'template' => '{input}'])->radioList(CheckPrinting::getUiEnums(),
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                $options = [
                    'label' => Html::encode($label),
                    'value' => $value
                ];
                $radio = \yii\bootstrap\Html::radio($name, $checked, $options);
                $radio = sprintf('<label>%s %s</label>', $options['label'], substr($radio, 7, -9 - strlen($options['label'])));
                return '<div class="col-sm-2' . ($value == CheckPrinting::THREE_PART_LASER ? ' col-sm-offset-4' : '') . '">' . $radio . '</div>';
            }, 'tag' => false
        ])->label(false) ?>
</fieldset>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('payroll'); ?>
<hr/>
<div class="row">
    <div class="col-xs-7">
        <fieldset class="the-fieldset">
            <legend class="the-legend"><?= Yii::t('app', 'Default Accounts') ?></legend>
            <?= $form->field($model, 'payroll_bank_account_id')->widget(Dropdown::class, $widgetConfig('Bank')) ?>
            <?= $form->field($model, 'payroll_expense_account_id')->widget(Dropdown::class, $widgetConfig(['Expense', 'Cost Of Sales'])) ?>
            <?= $form->field($model, 'payroll_tax_expense_account_id')->widget(Dropdown::class, $widgetConfig(['Expense', 'Cost Of Sales'])) ?>
            <?= $form->field($model, 'fsm_liability_account_id')->widget(Dropdown::class, $widgetConfig('Current Liability')) ?>
            <?= $form->field($model, 'futa_liability_account_id')->widget(Dropdown::class, $widgetConfig('Current Liability')) ?>
        </fieldset>
    </div>
    <div class="col-xs-5">
        <fieldset class="the-fieldset">
            <legend class="the-legend"><?= Yii::t('app', 'Driver Per Diem') ?></legend>
            <?= $form->field($model, 'calc_method')->dropDownList(\common\enums\CalcMethod::getUiEnums(), [
                'prompt' => $prompt
            ]) ?>
            <?= $form->field($model, 'pay_method')->dropDownList(\common\enums\PayMethod::getUiEnums(), [
                'prompt' => $prompt
            ]) ?>
            <?= $form->field($model, 'per_diem_rate')->textInput(['maxlength' => true, 'type' => 'number', 'min' => 0, 'step' => 0.001]) ?>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-xs-7">
        <fieldset class="the-fieldset">
            <legend class="the-legend"><?= Yii::t('app', 'Truck Owner Batch Settlements') ?></legend>
            <?= $form->field($model, 'to_show_adv_balances_on_settlements', ['labelOptions' => ['class' => 'col-sm-6'], 'horizontalCssClasses' => ['wrapper' => 'col-sm-3']])->checkbox([], false) ?>
            <?= $form->field($model, 'to_show_escrow_balances_on_settlements', ['labelOptions' => ['class' => 'col-sm-6'], 'horizontalCssClasses' => ['wrapper' => 'col-sm-3']])->checkbox([], false) ?>
        </fieldset>
    </div>
    <div class="col-xs-5">
        <fieldset class="the-fieldset">
            <legend class="the-legend"><?= Yii::t('app', 'Driver Batch Settlements') ?></legend>
            <?= $form->field($model, 'd_show_adv_balances_on_settlements', ['labelOptions' => ['class' => 'col-sm-9'], 'horizontalCssClasses' => ['wrapper' => 'col-sm-3']])->checkbox([], false) ?>
            <?= $form->field($model, 'd_show_escrow_balances_on_settlements', ['labelOptions' => ['class' => 'col-sm-9'], 'horizontalCssClasses' => ['wrapper' => 'col-sm-3']])->checkbox([], false) ?>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <fieldset class="the-fieldset">
            <legend class="the-legend"><?= Yii::t('app', 'Sales Commission Rules') ?></legend>
            <?= $form->field($model, 'sales_commissions_calc_base')->dropDownList(\common\enums\CalcBase::getUiEnums(), [
                'prompt' => $prompt
            ]) ?>
            <?= $form->field($model, 'sales_commission_override_percentage')->textInput(['maxlength' => true, 'type' => 'number', 'min' => 0, 'step' => 0.001]) ?>
            <?= $form->field($model, 'deduct_load_expenses_from_commissions', ['horizontalCssClasses' => ['wrapper' => 'col-sm-3']])->checkbox([], false) ?>
        </fieldset>
    </div>
</div>
<?php $this->endBlock(); ?>

<?php echo Tabs::widget(
    [
        'id' => 'accouning-deault-tabs',
        'encodeLabels' => false,
        'items' => [
            [
                'label' => Yii::t('app', 'General'),
                'content' => $this->blocks['general'],
                'active' => !$model->hasErrors(),
            ],
            [
                'label' => Yii::t('app', 'Receivables'),
                'content' => $this->blocks['receivables'],
                'active' => false,
            ],
            [
                'label' => Yii::t('app', 'Invoicing'),
                'content' => $this->blocks['invoicing'],
                'active' => false,
            ],
            [
                'label' => Yii::t('app', 'Payables'),
                'content' => $this->blocks['payables'],
                'active' => false,
            ],
            [
                'label' => Yii::t('app', 'Banking'),
                'content' => $this->blocks['banking'],
                'active' => false,
            ],
            [
                'label' => Yii::t('app', 'Payroll'),
                'content' => $this->blocks['payroll'],
                'active' => false,
            ],
        ]
    ]); ?>
<hr/>
<?php echo $form->errorSummary($model); ?>

<?php echo Html::submitButton(
    '<span class="glyphicon glyphicon-check"></span> ' .
    Yii::t('app', 'Save'),
    [
        'id' => 'save-' . $model->formName(),
        'class' => 'btn btn-success'
    ]
);
?>

<?php ActiveForm::end(); ?>
	