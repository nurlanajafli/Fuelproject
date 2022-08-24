<?php
/**
 * @var \common\components\View $this
 * @var \common\models\FuelPurchase $model
 * @var array $formConfig
 */

use common\widgets\DataTables\DataColumn;
use common\widgets\tdd\Dropdown;
use yii\bootstrap4\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

$form = ActiveForm::begin(ArrayHelper::merge($formConfig, [
    'fieldConfig' => [
        'options' => ['class' => 'form-group row'],
        'template' => '<div class="col-3">{label}</div><div class="col-6">{input}{error}</div>',
    ],
    'options' => [
        'data-cb' => 'fuelPurchaseReloadOnClick',
    ],
]));
$prompt = Yii::t('app', 'Select');
?>
  <div class="row mt-2">
    <div class="col">
        <?= $form->field($model, 'trip_no')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'unit_id')->widget(Dropdown::class, [
            'grid' => [
                'dataProvider' => new ActiveDataProvider([
                    'query' => \common\models\Unit::find(),
                    'pagination' => false,
                ]),
                'columns' => [
                    'id|title=Unit No',
                    'driver_id|rel=driver.getFullName()',
                    'co_driver_id|rel=coDriver.getFullName()|title=CoDriver',
                    'truck_id|rel=truck.truck_no|title=Truck No',
                    'trailer_id|rel=trailer.trailer_no|default=No Trailer',
                    'trailer_2_id|rel=trailer2.trailer_no|default=No Trailer',
                    'truck_id|visible=false',
                    'trailer_id|visible=false',
                    'trailer_2_id|visible=false',
                ],
                'order' => [[0, 'asc']]
            ],
            'lookupColumnIndex' => 0,
            'displayColumnIndex' => 0,
            'callback' => 'fuelPurchaseOnUnitChange',
        ]) ?>
        <?= $form->field($model, 'driver_id', ['template' => '<div class="col-3">{label}</div><div class="col-8">{input}{error}</div>'])->dropdownList($items = ArrayHelper::map(\common\models\Driver::find()->all(), 'id', 'fullName'), ['prompt' => $prompt]) ?>
        <?= $form->field($model, 'codriver_id', ['template' => '<div class="col-3">{label}</div><div class="col-8">{input}{error}</div>'])->dropdownList($items, ['prompt' => $prompt]) ?>
        <?= $form->field($model, 'truck_id')->widget(Dropdown::class, [
            'grid' => [
                'dataProvider' => new ActiveDataProvider([
                    'query' => \common\models\Truck::find(),
                    'pagination' => false,
                ]),
                'columns' => [
                    'id|visible=false',
                    'truck_no',
                    'type',
                ],
                'order' => [[1, 'asc']],
                'id' => 'fuelpurchase-truck_id-dt',
            ],
            'lookupColumnIndex' => 0,
            'displayColumnIndex' => 1,
        ]) ?>
        <?= $form->field($model, 'trailer_id')->widget(Dropdown::class, $config = [
            'grid' => [
                'dataProvider' => new ActiveDataProvider([
                    'query' => \common\models\Trailer::find(),
                    'pagination' => false,
                ]),
                'columns' => [
                    'id|visible=false',
                    'trailer_no',
                    'type',
                    new DataColumn([
                        'attribute' => 'length',
                        'value' => function ($model) {
                            return $model->length ?: ($model->type0 ? $model->type0->length : '');
                        }
                    ]),
                    new DataColumn([
                        'attribute' => 'height',
                        'value' => function ($model) {
                            return $model->height ?: ($model->type0 ? $model->type0->height : '');
                        }
                    ]),
                ],
                'order' => [[1, 'asc']],
                'id' => 'fuelpurchase-trailer_id-dt'
            ],
            'lookupColumnIndex' => 0,
            'displayColumnIndex' => 1,
        ]) ?>
        <?= $form->field($model, 'trailer2_id')->widget(Dropdown::class, ArrayHelper::merge($config, [
            'grid' => [
                'id' => 'fuelpurchase-trailer2_id-dt'
            ]
        ])) ?>
    </div>
    <div class="col">
      <div class="form-group row">
        <div class="col-3">
          <label><?= Yii::t('app', 'Date') ?></label>
        </div>
        <div class="col-8">
          <div class="row">
            <?= $form->field($model, 'purchase_date', ['options' => ['class' => 'col-7'], 'template' => '{input}{error}'])->textInput(['type' => 'date']) ?>
            <?= $form->field($model, 'purchase_time', ['options' => ['class' => 'col-5'], 'template' => '{input}{error}'])->textInput(['type' => 'time']) ?>
          </div>
        </div>
      </div>
      <?= $form->field($model, 'vendor', ['template' => '<div class="col-3">{label}</div><div class="col-8">{input}{error}</div>'])->textInput(['maxlength' => true]) ?>
      <div class="form-group row">
        <div class="col-3">
          <label><?= Yii::t('app', 'City, State') ?></label>
        </div>
        <div class="col-8">
          <div class="row">
            <?= $form->field($model, 'city', ['options' => ['class' => 'col-8'], 'template' => '{input}{error}'])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'state_id', ['options' => ['class' => 'col-4'], 'template' => '{input}{error}'])->widget(Dropdown::class, [
                'grid' => [
                    'dataProvider' => new ActiveDataProvider([
                        'query' => \common\models\State::find(),
                        'pagination' => false,
                    ]),
                    'columns' => [
                        'id|visible=false',
                        'state_code|title=Abb',
                        'state|title=Name',
                    ],
                    'order' => [[2, 'asc']]
                ],
                'lookupColumnIndex' => 0,
                'displayColumnIndex' => 1,
            ]) ?>
          </div>
        </div>
      </div>
      <?php $form->fieldConfig['template'] = '<div class="col-3">{label}</div><div class="col-3">{input}{error}</div>'; ?>
      <?= $form->field($model, 'quantity')->textInput(['type' => 'number', 'step' => '0.01', 'min' => '0']) ?>
      <?= $form->field($model, 'cost')->textInput(['type' => 'number', 'step' => '0.01', 'min' => '0']) ?>
      <?= $form->field($model, 'ppg')->textInput(['type' => 'number', 'step' => '0.01', 'min' => '0']) ?>
    </div>
  </div>
<?php ActiveForm::end();