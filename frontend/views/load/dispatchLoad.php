<?php
/**
 * @var \common\models\DispatchAssignment $model
 * @var array $formConfig
 * @var \common\models\Load $load
 * @var string $title
 */

use common\enums\LoadStatus;
use common\enums\PayType;
use common\models\Driver;
use common\models\Trailer;
use common\widgets\DataTables\DataColumn;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

if ($errors) {
    $this->beginBlock('content');
    foreach ($errors as $error) {
        echo '<div class="alert alert-danger" role="alert"><strong>' . $error . '</strong></div>';
    }
    $this->endBlock();
    echo common\widgets\ModalForm\ModalForm::widget([
        'title' => $title,
        'content' => $this->blocks['content'],
        'dialogCssClass' => 'modal-lg',
        'saveButton' => false
    ]);
} else {
    $prompt = Yii::t('app', 'Select');
    $stopsCount = count($load->loadStops);

    $payTypeArray = PayType::getUiEnums();
    $arrFilterFunc = function ($baseArray, $allowedArray) {
        $result = [];
        foreach ($baseArray as $key => $value) {
            if (in_array($key, $allowedArray)) {
                $result[$key] = $value;
            }
        }
        return $result;
    };

    $this->beginBlock('tb');
    ?>
  <div class="edit-form-toolbar">
      <?php if ($load->status == LoadStatus::ENROUTED) : ?>
        <button class="btn btn-link js-ajax-modal" title="<?= Yii::t('app', 'Drop') ?>" data-tooltip="tooltip"
                data-placement="top" data-url="<?= Url::toRoute(['drop', 'id' => $load->id]) ?>">
          <i class="fas fa-sign-in-alt fa-rotate-90"></i>
        </button>
      <?php endif; ?>
    <button class="btn btn-link" disabled title="<?= Yii::t('app', 'Print') ?>" data-tooltip="tooltip"
            data-placement="top">
      <i class="fas fa-print"></i>
    </button>
    <div class="dropdown">
      <button class="btn btn-link dropdown-toggle" data-toggle="dropdown" disabled
              title="<?= Yii::t('app', 'Send Message') ?>" data-tooltip="tooltip" data-placement="top"
              data-url="<?= Url::toRoute(['drop', 'id' => $load->id]) ?>">
        <i class="fas fa-envelope"></i>
      </button>
      <div class="dropdown-menu">
        <a href="#" class="dropdown-item"><?= Yii::t('app', 'Item 1') ?></a>
        <a href="#" class="dropdown-item"><?= Yii::t('app', 'Item 2') ?></a>
        <a href="#" class="dropdown-item"><?= Yii::t('app', 'Item 3') ?></a>
      </div>
      <button class="btn btn-link" disabled title="<?= Yii::t('app', 'MacroPoint Pings') ?>" data-tooltip="tooltip"
              data-placement="top">
        <i class="fas fa-crosshairs"></i>
      </button>
    </div>
  </div>
  <div class="d-none justify-content-center preloader">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
    <?php
    $this->endBlock();
    $this->beginBlock('form');
    Pjax::begin(['enablePushState' => false]);
    $form = ActiveForm::begin(ArrayHelper::merge($formConfig, [
        'layout' => 'horizontal',
        'fieldConfig' => Yii::$app->params['activeForm']['horizontalLoadFormConfig'],
        'options' => ['data-pjax' => true, 'class' => ''],
        'enableAjaxValidation' => false,
        'id' => 'dispatchload-form'
    ]));
    ?>
  <div class="row my-3">
    <div class="col-4">
      <div class="card">
        <div class="card-body bp-0 py-1">
            <?= $form->field($model, 'pay_code')->dropdownList(\common\enums\PayCode::getUiEnums(), ['prompt' => $prompt]) ?>
            <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>
            <?= $form->field($model, 'unit_id')->widget(\common\widgets\tdd\Dropdown::class, [
                'grid' => [
                    'dataProvider' => new \yii\data\ActiveDataProvider([
                        'query' => \common\models\Unit::find()->joinWith(['driver', 'coDriver', 'truck', 'trailer', 'trailer2']),
                        'pagination' => false
                    ]),
                    'columns' => [
                        'id',
                        'driver_id|rel=driver._label|default=No driver',
                        'driver_id|visible=false',
                        'co_driver_id|rel=coDriver._label|default=No co driver',
                        'co_driver_id|visible=false',
                        new DataColumn([
                            'attribute' => 'truck_id',
                            'value' => function ($model) {
                                return $model->truck ? $model->truck->id . ' (' . $model->truck->_label . ')' : Yii::t('app', 'No truck');
                            },
                        ]),
                        'truck_id|visible=false',
                        new DataColumn([
                            'attribute' => 'trailer_id',
                            'value' => function ($model) {
                                return $model->trailer ? $model->trailer->id . ' (' . $model->trailer->_label . ')' : Yii::t('app', 'No trailer');
                            },
                        ]),
                        'trailer_id|visible=false',
                        new DataColumn([
                            'attribute' => 'trailer_2_id',
                            'value' => function ($model) {
                                return $model->trailer2 ? $model->trailer2->id . ' (' . $model->trailer2->_label . ')' : Yii::t('app', 'No trailer 2');
                            },
                        ]),
                        'trailer_2_id|visible=false',
                        'null|rel=driver.loaded_pay_type|visible=false',
                        'null|rel=driver.loaded_per_mile|visible=false',
                        'null|rel=driver.empty_per_mile|visible=false',
                        'null|rel=driver.percentage|visible=false',
                        'status'
                    ]
                ],
                'lookupColumnIndex' => 0,
                'displayColumnIndex' => 0,
                'callback' => 'displfunitch'
            ]) ?>
            <?= $form->field($model, 'driver_id')->dropdownList(
                ArrayHelper::map(Driver::find()->all(), 'id', '_label'),
                ['readonly' => 'readonly', 'prompt' => Yii::t('app', 'No driver')]
            ) ?>
            <?= $form->field($model, 'codriver_id')->dropdownList(
                ArrayHelper::map(Driver::find()->all(), 'id', '_label'),
                ['readonly' => 'readonly', 'prompt' => Yii::t('app', 'No co driver')]
            ) ?>
            <?= $form->field($model, 'truck_id')->dropdownList(
                ArrayHelper::map(\common\models\Truck::find()->all(), 'id', '_label'),
                ['readonly' => 'readonly', 'prompt' => Yii::t('app', 'No truck')]
            ) ?>
            <?= $form->field($model, 'trailer_id')->dropdownList(
                ArrayHelper::map(Trailer::find()->all(), 'id', '_label'),
                ['prompt' => Yii::t('app', 'No trailer')]
            ) ?>
            <?= $form->field($model, 'trailer2_id')->dropdownList(
                ArrayHelper::map(Trailer::find()->all(), 'id', '_label'),
                ['prompt' => Yii::t('app', 'No trailer 2')]
            ) ?>
            <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>
        </div>
      </div>
    </div>
      <?php $form->fieldConfig = Yii::$app->params['activeForm']['dispatchFormConfig']; ?>
    <div class="col-8">
      <div class="row">
        <div class="col-6">
          <div class="form-fieldset">
            <span class="form-legend"><?= Yii::t('app', 'Load Calculate') ?></span>
              <?= $form->field($model, 'driver_pay_source')->dropdownList(
                  \common\enums\PaySource::getUiEnums(),
                  (!$model->driver_id) ? ['readonly' => 'readonly', 'prompt' => $prompt] : ['prompt' => $prompt]
              ) ?>

              <?php if ($model->driver_pay_source == \common\enums\PaySource::MATRIX) { ?>
                  <?= $form->field($model, 'driver_pay_matrix')->widget(\common\widgets\tdd\Dropdown::class, [
                      'grid' => [
                          'dataProvider' => new \yii\data\ActiveDataProvider([
                              'query' => \common\models\LoadRatingMatrix::find()->andWhere(['inactive' => false]),
                              'pagination' => false,
                          ]),
                          'columns' => [
                              'number|title=Matrix No',
                              'name|title=Matrix Name',
                              'rate_type|title=Type',
                              'rate_method|title=Method',
                              new \common\widgets\DataTables\DataColumn([
                                  'attribute' => 'number',
                                  'visible' => false,
                                  'value' => function () use ($model) {
                                      $loadMatrixList = new \frontend\forms\load\Rate();
                                      return $loadMatrixList->getLoad();
                                      //return $matrixModel->calculate($loadMatrixList->getLoad())['rate'];
                                  },
                              ])
                          ],
                          'ordering' => false,
                          //  'attributes' => ['data-callback' => 'rateloadmxnuch'],
                      ],
                      'lookupColumnIndex' => 0,
                      'displayColumnIndex' => 0,
                  ]) ?>
              <?php } ?>

              <?= $form->field($model, 'driver_pay_type')->dropdownList(
                  $arrFilterFunc($payTypeArray, [PayType::FLAT, PayType::MILES]),
                  ['prompt' => $prompt]
              ) ?>
              <?= $form->field($model, 'driver_loaded_miles')->textInput(['type' => 'number', 'readonly' => 'readonly']) ?>
              <?= $form->field($model, 'driver_empty_miles')->textInput(['type' => 'number', 'readonly' => 'readonly']) ?>
              <?= $form->field($model, 'driver_loaded_rate')->textInput(['type' => 'number', 'step' => 0.01]) ?>
              <?= $form->field($model, 'driver_empty_rate')->textInput(['type' => 'number', 'step' => 0.01]) ?>
          </div>
        </div>
        <div class="col-6<?= $model->codriver_id ? '' : ' invisible_' ?>" id="dispatchload-codriver_">
          <div class="form-fieldset">
            <span class="form-legend"><?= Yii::t('app', 'Pay') ?></span>

              <?php if ($model->driver && $model->driver->co_driver_id && $model->driver->co_driver_id > 0
                  && $model->driver->co_driver_earning_percent && $model->driver->co_driver_earning_percent > 0) { ?>
                  <?php $driverPercent = 1 - $model->driver->co_driver_earning_percent; ?>
                <div class="form-group row">
                  <label class="col-5"><?= Yii::t('app', 'Driver Percent') ?></label>
                  <div class="col-7"><?= $driverPercent ?></div>
                </div>
                <div class="form-group row">
                  <label class="col-5"><?= Yii::t('app', 'Driver Pay') ?></label>
                  <div class="col-7"><?= Yii::$app->formatter->asDecimal($model->driver_total_pay * $driverPercent) ?></div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-5"><?= Yii::t('app', 'Co Driver Percent') ?></label>
                  <div class="col-7"><?= $model->driver->co_driver_earning_percent ?></div>
                </div>
                <div class="form-group row">
                  <label class="col-5"><?= Yii::t('app', 'Co Driver Pay') ?></label>
                  <div class="col-7"><?= Yii::$app->formatter->asDecimal($model->driver_total_pay * $model->driver->co_driver_earning_percent) ?></div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-5"><?= Yii::t('app', 'Total') ?></label>
                  <div class="col-7"><?= Yii::$app->formatter->asDecimal($model->driver_total_pay) ?></div>
                </div>
              <?php } else { ?>
                <div class="form-group row">
                  <label class="col-5"><?= Yii::t('app', 'Total') ?></label>
                  <div class="col-7"><?= Yii::$app->formatter->asDecimal($model->driver_total_pay) ?></div>
                </div>
              <?php } ?>
              <?php /*

                        <?= $form->field($model, 'codriver_pay_source')->dropdownList(
                            \common\enums\PaySource::getUiEnums(),
                            ['readonly' => 'readonly', 'prompt' => $prompt]
                        ) ?>
                        <?= $form->field($model, 'codriver_pay_matrix')->dropdownList([], ['prompt' => $prompt]) ?>
                        <?= $form->field($model, 'codriver_pay_type')->dropdownList(
                            \common\enums\PayType::getUiEnums(),
                            ['prompt' => $prompt]
                        ) ?>
                        <?= $form->field($model, 'codriver_loaded_miles')->textInput(['type' => 'number', 'readonly' => 'readonly']) ?>
                        <?= $form->field($model, 'codriver_empty_miles')->textInput(['type' => 'number', 'readonly' => 'readonly']) ?>
                        <?= $form->field($model, 'codriver_loaded_rate')->textInput(['type' => 'number', 'step' => 0.01]) ?>
                        <?= $form->field($model, 'codriver_empty_rate')->textInput(['type' => 'number', 'step' => 0.01]) ?>
                        <div class="form-group row">
                            <label class="col-5"><?= Yii::t('app', 'Total Pay') ?></label>
                            <div class="col-7"><?= Yii::$app->formatter->asDecimal($model->codriver_total_pay) ?></div>
                        </div> */ ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label><?= Yii::t('app', 'Dispatch Starts') ?></label>
            <div class="form-row">
                <?= $form->field($model, 'dispatch_start_date', $opt = ['template' => '<div class="col-3">{input}{error}{hint}</div>', 'options' => ['tag' => false]])->textInput(['type' => 'date', 'maxlength' => true]) ?>
                <?= $form->field($model, 'dispatch_start_time_in', $opt)->textInput(['type' => 'time', 'maxlength' => true]) ?>
                <?= $form->field($model, 'dispatch_start_time_out', $opt)->textInput(['type' => 'time', 'maxlength' => true]) ?>
            </div>
          </div>
          <p class="form-readonly-text">
              <?= $load->loadStops[0]->address ?>,
              <?= $load->loadStops[0]->city ?>,
              <?= $load->loadStops[0]->state ? $load->loadStops[0]->state->state_code : '' ?>,
              <?= $load->loadStops[0]->zip ?>
          </p>
          <br>
          <div class="form-group">
            <label><?= Yii::t('app', 'Dispatch Ends') ?></label>
            <div class="form-row">
                <?= $form->field($model, 'dispatch_deliver_date', $opt)->textInput(['type' => 'date', 'maxlength' => true]) ?>
                <?= $form->field($model, 'dispatch_deliver_time_in', $opt)->textInput(['type' => 'time', 'maxlength' => true]) ?>
                <?= $form->field($model, 'dispatch_deliver_time_out', $opt)->textInput(['type' => 'time', 'maxlength' => true]) ?>
            </div>
          </div>
          <p class="form-readonly-text">
              <?= $load->loadStops[$stopsCount - 1]->address ?>,
              <?= $load->loadStops[$stopsCount - 1]->city ?>,
              <?= $load->loadStops[$stopsCount - 1]->state ? $load->loadStops[$stopsCount - 1]->state->state_code : '' ?>
            ,
              <?= $load->loadStops[$stopsCount - 1]->zip ?>
          </p>
        </div>
      </div>
    </div>
  </div>
    <?php
    ActiveForm::end();
    Pjax::end();
    $this->endBlock();
    echo common\widgets\ModalForm\ModalForm::widget([
        'title' => $title,
        'toolbar' => $this->blocks['tb'],
        'content' => $this->blocks['form'],
        'dialogCssClass' => 'modal-xl'
    ]);
}