<?php

use common\enums\Bill;
use common\models\Customer;
use common\models\LoadStop;
use common\models\LoadType;
use common\models\Office;
use common\models\TrailerType;
use common\widgets\DataTables\DataColumn;
use common\widgets\tdd\Dropdown;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var ActiveForm $form
 * @var common\models\Load $model
 */
$this->registerCssFile('https://maps-sdk.trimblemaps.com/v3/trimblemaps-3.4.0.css');
$this->registerJsFile('https://maps-sdk.trimblemaps.com/v3/trimblemaps-3.4.0.js', ['position' => View::POS_HEAD]);
?>
  <div class="row mb-2">
    <div class="col-2"><p class="form-readonly-text"><?= $model->id ?></p></div>
    <div class="col-2"><p class="form-readonly-text"><?= $model->status ?></p></div>
  </div>
  <div class="row">
    <div class="col">
      <table class="table table-bordered table-sm">
        <thead>
        <tr>
          <th scope="col"><?= Yii::t('app', 'Stop') ?></th>
          <th scope="col"><?= Yii::t('app', 'No') ?></th>
          <th scope="col"><?= Yii::t('app', 'Av') ?></th>
          <th scope="col"><?= Yii::t('app', 'Thru') ?></th>
          <th scope="col"><?= Yii::t('app', 'From') ?></th>
          <th scope="col"><?= Yii::t('app', 'To') ?></th>
          <th scope="col"><?= Yii::t('app', 'Appt') ?></th>
          <th scope="col"><?= Yii::t('app', 'Appt&nbsp;Ref') ?></th>
          <th scope="col"><?= Yii::t('app', 'Shipper') ?></th>
          <th scope="col"><?= Yii::t('app', 'Address') ?></th>
          <th scope="col"><?= Yii::t('app', 'City') ?></th>
          <th scope="col"><?= Yii::t('app', 'St') ?></th>
          <th scope="col"><?= Yii::t('app', 'Zip') ?></th>
          <th scope="col"><?= Yii::t('app', 'Z') ?></th>
          <th scope="col"><?= Yii::t('app', 'Ml') ?></th>
          <th scope="col"><?= Yii::t('app', 'Reference') ?></th>
          <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td scope="row" colspan="17">
            <button class="btn btn-sm btn-outline-primary js-ajax-modal"
                    data-url="<?= Url::toRoute(['load-stop/create', 'id' => $model->id]) ?>"
                    data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Add stop') ?>">
                <?= Yii::t('app', 'Add') ?>
            </button>
          </td>
        </tr>
        <?php $stops = $model->getLoadStops()->orderBy(['stop_number' => SORT_ASC])->all(); ?>
        <?php foreach ($stops as $stop) : ?>
            <?php /** @var LoadStop $stop */ ?>
          <tr>
            <td><?= $stop->stop_type; ?></td>
            <td><?= $stop->stop_number; ?></td>
            <td><?= Yii::$app->formatter->asDate($stop->available_from, 'short'); ?></td>
            <td><?= Yii::$app->formatter->asDate($stop->available_thru, 'short'); ?></td>
            <td><?= Yii::$app->formatter->asTime($stop->time_from, 'short'); ?></td>
            <td><?= Yii::$app->formatter->asTime($stop->time_to, 'short'); ?></td>
            <td><?= Yii::$app->formatter->asBoolean($stop->appt_required); ?></td>
            <td><?= $stop->appt_reference; ?></td>
            <td><?= $stop->getCompanyName(); ?></td>
            <td><?= $stop->getAddress(); ?></td>
            <td><?= $stop->getCity(); ?></td>
            <td><?= $stop->getStateCode(); ?></td>
            <td><?= $stop->getZip(); ?></td>
            <td><?= $stop->zone0->code ?></td>
            <td></td>
            <td><?= $stop->reference ?></td>
            <td>
              <a class="btn btn-default btn-sm shadow-sm js-ajax-modal"
                 data-url="<?= Url::toRoute(['load-stop/update', 'id' => $stop->id, 'loadId' => $model->id]) ?>">
                <i class="fas fa-sm text-red-50 fa-edit"></i> <?= Yii::t('app', 'Edit') ?>
              </a>
              <a class="btn btn-default btn-sm shadow-sm"
                 href="<?= Url::toRoute(['load-stop/delete', 'id' => $stop->id, 'loadId' => $model->id]) ?>"
                 data-confirm="<?= Yii::t('app', 'Are you sure to delete this item?') ?>" data-method="post">
                <i class="fas fa-sm text-red-50 fa-trash"></i> <?= Yii::t('app', 'Delete') ?>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php
$form = ActiveForm::begin([
    'id' => 'Load',
    'layout' => 'horizontal',
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'fieldConfig' => Yii::$app->params['activeForm']['horizontalFormConfig'],
    /*'options' => [
        'class' => 'js-fp'
    ]*/
]);
?>
<?= $form->field($model, 'id')->hiddenInput()->label(false); ?>
  <div class="row">
    <div class="col-5">
      <div class="form-fieldset">
          <?= $form->field($model, 'bill')->dropDownList(Bill::getUiEnums()); ?>
          <?= $form->field($model, 'bill_to')->widget(Dropdown::class, [
              'items' => Customer::find()->all(),
              'modelClass' => Customer::class,
              'lookupColumnIndex' => 0,
              'displayColumnIndex' => 1,
              'grid' => [
                  'columns' => [
                      new DataColumn(['attribute' => 'id', 'visible' => false]),
                      'name',
                      'address_1',
                      'city',
                      new DataColumn(['attribute' => 'main_phone', 'visible' => false]),
                      new DataColumn(['attribute' => 'state_id', 'visible' => false]),
                      new DataColumn(['attribute' => 'zip', 'visible' => false]),
                      new DataColumn([
                          'attribute' => 'state_id',
                          'value' => function ($model) {
                              /** @var Customer $model */
                              if ($rel = $model->state) {
                                  return $rel->_label;
                              }
                              return '';
                          }
                      ]),
                  ]
              ],
              'callback' => 'function(rowData) {
                    var formName = "' . $model->formName() . '";
                    $("#bill_to_address").html(rowData[2]);
                    $("#bill_to_city_state_zip").html(rowData[3] + ", " + rowData[7] + " " + rowData[6]);
                    $("#bill_to_phone").html(rowData[4]);
//                    jQuery("input[name=\'"+formName+"[address]\']").val(rowData[2]);
//                    jQuery("input[name=\'"+formName+"[city]\']").val(rowData[3]);
//                    jQuery("select[name=\'"+formName+"[state_id]\']").val(rowData[4]);
//                    jQuery("input[name=\'"+formName+"[zip]\']").val(rowData[5]);                    
                }'
          ]); ?>

        <div class="row">
          <div class="col-4 offset-2">
            <div class="field">
              <p id="bill_to_address" class="form-readonly-text">
                  <?php if ($model->billTo) : ?>
                      <?= $model->billTo->address_1 ?>
                  <?php endif; ?>
              </p>
            </div>
            <div class="field">
              <p id="bill_to_city_state_zip" class="form-readonly-text">
                  <?php if ($model->billTo) : ?>
                      <?= $model->billTo->city ?>, <?= $model->billTo->state->state_code ?> <?= $model->billTo->zip ?>
                  <?php endif; ?>
              </p>
            </div>
            <div class="field">
              <p id="bill_to_phone" class="form-readonly-text">
                  <?= $model->billTo->main_phone ?>
              </p>
            </div>
          </div>
        </div>
          <?= $form->field($model, 'customer_reference')->textInput(); ?>
      </div>
    </div>
    <div class="col-7">
      <div class="form-fieldset">
        <div class="row">
          <div class="col-6">
              <?php $form->fieldConfig = Yii::$app->params['activeForm']['horizontalLoadFormConfig'] ?>
              <?= $form->field($model, 'bill_miles')->textInput(['readonly' => true]); ?>
              <?= $form->field($model, 'received')->textInput(['type' => 'date']); ?>
              <?= $form->field($model, 'release')->textInput(['type' => 'date']); ?>
              <?= $form->field($model, 'office_id')->dropDownList(ArrayHelper::map(Office::find()->all(), 'id', '_label')); ?>
              <?= $form->field($model, 'type_id')->dropDownList(ArrayHelper::map(LoadType::find()->all(), 'id', '_label'), ['prompt' => 'Select']); ?>
              <?= $form->field($model, 'trailer_type')->dropDownList(ArrayHelper::map(TrailerType::find()->all(), 'type', '_label'), ['prompt' => 'Select']); ?>
              <?= $form->field($model, 'seal_no')->textInput(); ?>
          </div>
          <div class="col-6">
            <div class="field row">
              <div class="col-4">
                <label>Salesman</label>
              </div>
              <div class="col-8 input-group">
                <select readonly class="form-control">
                  <option selected>Choose...</option>
                  <option value="#">XZ</option>
                  <option value="#">XZ</option>
                  <option value="#">XZ</option>
                </select>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary">...</button>
                </div>
              </div>
            </div>
              <?= $form->field($model, 'notes')->textarea(['rows' => 11]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-1"><i class="fas fa-2x fa-money-bill-alt mt-4"></i></div>
    <div class="col">
      <div class="field-col">
        <label>Disp Pay</label>
        <input class="form-control" type="text" readonly>
      </div>
    </div>
    <div class="col">
      <div class="field-col">
        <label>Acc Pay</label>
        <input class="form-control" type="text" readonly>
      </div>
    </div>
    <div class="col">
      <div class="field-col">
        <label>Comment</label>
        <input class="form-control" type="text" readonly>
      </div>
    </div>
    <div class="col-7">
      <div class="field-col">
        <label>Broadcast Notes</label>
        <input class="form-control" type="text" readonly>
      </div>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-6">
        <?= $this->render('_commodity', ['form' => $form, 'model' => $model]) ?>
    </div>
    <div class="col-6">
        <?= $this->render('_accessorials', ['model' => $model]) ?>
    </div>
  </div>
<?php ActiveForm::end(); ?>
<?= $this->render("_notes", ['model' => $model]) ?>
<?= $this->render("_stat-footer", ['model' => $model]) ?>
<?php
$this->beginBlock('updateAddressConfirm');
?>
  <div class="location-confirm-inner">
    <div class="location-confirm-left"><i class="fas fa fa-question-circle fa-3x"></i></div>
    <div class="location-confirm-right">
      <p><?= Yii::t('app', 'Update Address') ?>:</p>
      <p class="location-confirm-old"> <?= Yii::t('app', 'old address') ?></p>
      <p><?= Yii::t('app', 'To') ?>:</p>
      <p class="location-confirm-new"> <?= Yii::t('app', 'new address') ?></p>
    </div>
  </div>
<?php
$this->endBlock();
$yes = Yii::t('app', 'Yes');
$no = Yii::t('app', 'No');
$this->params['modals'] = [[
    'cssClass' => 'location-confirm-modal',
    'dialogCssClass' => 'modal-dialog-centered',
    'title' => Yii::t('app', 'Express'),
    'content' => $this->blocks['updateAddressConfirm'],
    'footerCssClass' => '',
    'beforeSaveButtonHtml' => '<button class="btn btn-primary js-location-modal-yes" type="button">' . $yes . '</button><button class="btn btn-secondary js-location-modal-no" type="button" data-dismiss="modal">' . $no . '</button>',
    'saveButton' => false,
    'closeButton' => false
]];