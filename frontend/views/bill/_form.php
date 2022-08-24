<?php
/**
 * @var yii\web\View $this
 * @var common\models\Bill $model
 * @var array $formConfig
 */

use common\models\BillItem;
use common\models\State;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use common\widgets\tdd\Dropdown;
use yii\bootstrap4\ActiveForm;

$form = ActiveForm::begin(\yii\helpers\ArrayHelper::merge($formConfig, ['options' => ['data-cb' => 'billsRefresh']]));
$billItem = new BillItem();
?>
<div class="form-fieldset bill-window">
  <div class="form-row justify-content-between mb-5">
      <?= str_replace('dropdown-toggle', 'dropdown-toggle rounded-0',
          $form->field($model, 'from_carrier_id', ['options' => ['class' => 'col-3']])->widget(Dropdown::class, [
              'displayColumnIndex' => 2,
              'destAttribute' => [['from_carrier_id' => 0, 'from_vendor_id' => 1,], 0, 1],
              'grid' => [
                  'dataProvider' => new \yii\data\SqlDataProvider([
                      'sql' => \common\models\Carrier::find()
                          ->select(new \yii\db\Expression('t0.id,t0.name,t0.address_1,t0.address_2,t0.city,state_code,0 AS type'))
                          ->alias('t0')
                          ->leftJoin(State::tableName(), 't0.state_id=' . State::tableName() . '.id')
                          ->union(
                              \common\models\Vendor::find()
                                  ->select(new \yii\db\Expression('t1.id,t1.name,t1.address_1,t1.address_2,t1.city,state_code,1 AS type'))
                                  ->alias('t1')
                                  ->leftJoin(State::tableName(), 't1.state_id=' . State::tableName() . '.id')
                          )
                          ->createCommand()
                          ->getRawSql(),
                      'pagination' => false,
                  ]),
                  'columns' => [
                      'type|visible=false|searchable=false',
                      'id|visible=false|searchable=false',
                      'name|title=Name',
                      new DataColumn([
                          'title' => Yii::t('app', 'Address'),
                          'value' => function ($model) {
                              return $model->address_1 ? $model->address_1 : $model->address_2;
                          }
                      ]),
                      'city|title=City',
                      new DataColumn([
                          'title' => Yii::t('app', 'St'),
                          'attribute' => 'state_code',
                      ]),
                      new DataColumn([
                          'title' => Yii::t('app', 'Type'),
                          'value' => function ($model) {
                              return Yii::t('app', ($model->type == 0) ? 'Carrier' : 'Vendor');
                          }
                      ]),
                  ],
                  'order' => [[6, 'asc']]
              ]
          ])) ?>

    <div class="col-5">
      <div class="form-row">
          <?= $form->field($model, 'bill_no', ['options' => ['class' => 'col-4']])->textInput(['class' => 'form-control rounded-0']) ?>
          <?= $form->field($model, 'posting_date', ['options' => ['class' => 'col-4']])->textInput(['class' => 'form-control rounded-0', 'type' => 'date']) ?>
          <?= $form->field($model, 'bill_date', ['options' => ['class' => 'col-4']])->textInput(['class' => 'form-control rounded-0', 'type' => 'date']) ?>
      </div>
    </div>
  </div>
  <div class="form-row justify-content-between">
      <?= str_replace('dropdown-toggle', 'dropdown-toggle rounded-0',
          $form->field($model, 'ap_account', ['options' => ['class' => 'col-1']])->widget(Dropdown::class, [
              'displayColumnIndex' => 0,
              'lookupColumnIndex' => 0,
              'grid' => [
                  'dataProvider' => new \yii\data\ActiveDataProvider([
                      'query' => \common\models\Account::getFilterByType('Accounts Payable', true),
                      'pagination' => false,
                  ]),
                  'columns' => [
                      'account',
                      'description|title=Desc',
                      'account_type|rel=accountType.type',
                  ],
                  'order' => [[0, 'asc']]
              ]
          ])) ?>
      <?= $form->field($model, 'payment_terms', ['options' => ['class' => 'col-2']])->dropdownList(
          \yii\helpers\ArrayHelper::map(\common\models\PaymentTermCode::find()->all(), 'id', 'description'),
          ['class' => 'form-control rounded-0']
      ) ?>
      <?= $form->field($model, 'due_date', ['options' => ['class' => 'col-2']])->textInput(['class' => 'form-control rounded-0', 'type' => 'date']) ?>
      <?= str_replace('dropdown-toggle', 'dropdown-toggle rounded-0',
          $form->field($model, 'office_id', ['options' => ['class' => 'col-1']])->widget(Dropdown::class, [
              'displayColumnIndex' => 1,
              'lookupColumnIndex' => 0,
              'grid' => [
                  'dataProvider' => new \yii\data\ActiveDataProvider([
                      'query' => \common\models\Office::find(),
                      'pagination' => false,
                  ]),
                  'columns' => [
                      'id',
                      'office',
                  ],
                  'ordering' => false,
              ]
          ])) ?>
      <?= $form->field($model, 'memo', ['options' => ['class' => 'col-6']])->textInput(['class' => 'form-control rounded-0']) ?>
  </div>
</div>
<?php ActiveForm::end(); ?>
<?= Grid::widget([
    'cssClass' => 'W-100',
    'autoWidth' => true,
    'conditionalPaging' => true,
    'dom' => 't',
    'id' => 'bill-items-table',
    'scrollCollapse' => true,
    'paging' => false,
    'scrollY' => '500px',
    'stateSave' => false,
    'orderCellsTop' => true,
    'template' => '<div class="table table-responsive">{table}</div>',
    'ajax' => \yii\helpers\Url::toRoute(['bill-item/data', 'billId' => $model->id + 0, 'mode' => 0]),
    'columns' => [
        new DataColumn([
            'title' => $billItem->getAttributeLabel('account'),
        ]),
        new DataColumn([
            'title' => $billItem->getAttributeLabel('driver_id'),
        ]),
        new DataColumn([
            'title' => $billItem->getAttributeLabel('amount'),
        ]),
        new DataColumn([
            'title' => 'Office',
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Our Ref'),
            'value' => function (BillItem $billItem) {
                return '';
            }
        ]),
        new DataColumn([
            'title' => 'UD',
        ]),
        new DataColumn([
            'title' => $billItem->getAttributeLabel('special'),
        ]),
        new DataColumn([
            'title' => $billItem->getAttributeLabel('memo'),
        ]),
    ],
]) ?>
<div class="row justify-content-between">
  <div class="col-2">
    <div class="row">
      <div class="col-5"><?= $model->getAttributeLabel('transaction_id') ?>:</div>
      <div class="col-7"><span class="form-readonly-text"><?= $model->transaction_id ?></span></div>
    </div>
  </div>
  <div class="col-2">
    <div class="row">
      <div class="col-3">Input:</div>
      <div class="col-9"><span class="form-readonly-text">7/3/2019</span></div>
    </div>
  </div>
  <div class="col-2">
    <div class="row">
      <div class="col-2">By:</div>
      <div class="col-10"><span class="form-readonly-text">YULDUZ</span></div>
    </div>
  </div>
  <div class="col-3">
    <div class="row">
      <div class="col-4">Disc Date:</div>
      <div class="col-8"><span class="form-readonly-text">7/3/19</span></div>
    </div>
    <div class="row">
      <div class="col-4">Disc Amt:</div>
      <div class="col-8"><span class="form-readonly-text">0.00</span></div>
    </div>
  </div>
  <div class="col-3">
    <div class="row">
      <div class="col-5"><?= Yii::t('app', 'Bill Total') ?></div>
      <div class="col-7"><span class="form-readonly-text"><?= Yii::$app->formatter->asDecimal($model->amount) ?></span>
      </div>
    </div>
    <div class="row">
      <div class="col-5">Balance:</div>
      <div class="col-7"><span class="form-readonly-text">188,600.2</span></div>
    </div>
  </div>
</div>