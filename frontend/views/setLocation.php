<?php
/**
 * @var \yii\web\View $this
 * @var array $formConfig
 * @var \frontend\forms\SetLocation $model
 */

use yii\bootstrap4\ActiveForm;
use common\widgets\ModalForm\ModalForm;
use common\widgets\tdd\Dropdown;
use common\models\Location;
use common\widgets\DataTables\DataColumn;
use common\models\Zone;
use yii\helpers\ArrayHelper;

$this->beginBlock('content');
$formConfig['options']['class'] .= ' form-fieldset mb-3';
if (isset($cb)) {
    $formConfig['options']['data-cb'] = $cb;
}
$formConfig['fieldConfig']['options']['class'] .= ' row field';
$form = ActiveForm::begin($formConfig);
$widgetConfig = [
    'modelClass' => Location::class,
    'items' => Location::find()->all(),
    'lookupColumnIndex' => 0,
    'displayColumnIndex' => 1,
    'grid' => [
        'columns' => [
            'id|visible=false',
            new DataColumn([
                'attribute' => 'company_name',
                'title' => Yii::t('app', 'Name'),
            ]),
            'address',
            'city',
            new DataColumn([
                'attribute' => 'state_id',
                'title' => Yii::t('app', 'St'),
                'value' => function ($model) {
                    /** @var Location $model */
                    if ($rel = $model->state) {
                        return $rel->_label;
                    }
                    return '';
                },
            ]),
            'zip',
            'zone'
        ]
    ],
    'callback' => 'function (r) {jQuery("#ml_city").text(r[3]);jQuery("#ml_st").text(r[4]);jQuery("#ml_zip").text(r[5]);return [];}'
];
$city = $st = $zip = '';
if ($model->location_id && ($location = \common\models\Location::findOne(['id' => $model->location_id]))) {
    $city = $location->city;
    if ($rel = $location->state) {
        $st = $rel->_label;
    }
    $zip = $location->zip;
}
?>
              <?= $form->field($model, 'date', [
                  'template' => '<div class="col-2">{label}</div><div class="col-3">{input}{error}</div>',
              ])->textInput(['type' => 'date']) ?>
              <?= $form->field($model, 'location_id', [
                  'template' => '<div class="col-2">{label}</div><div class="col-7">{input}{error}</div>',
              ])->widget(Dropdown::class, $widgetConfig) ?>
              <div class="form-group row field">
                <div class="col-2">
                  <label><?= Yii::t('app', 'City St Zip') ?></label>
                </div>
                <div class="col-7 row">
                  <div class="col"><span class="form-readonly-text" id="ml_city"><?= $city ?></span></div>
                  <div class="col"><span class="form-readonly-text" id="ml_st"><?= $st ?></span></div>
                  <div class="col"><span class="form-readonly-text" id="ml_zip"><?= $zip ?></span></div>
                </div>
              </div>
              <?= $form->field($model, 'zone', [
                  'template' => '<div class="col-2">{label}</div><div class="col-3">{input}{error}</div>',
              ])->dropdownList(ArrayHelper::map(Zone::find()->all(), 'code', 'code'), ['prompt' => Yii::t('app', 'Select')]) ?>
<?php
ActiveForm::end();
$this->endBlock();
echo ModalForm::widget([
    'dialogCssClass' => 'modal-lg',
    'title' => Yii::t('app', 'Modify Location'),
    'content' => $this->blocks['content'],
    'saveButton' => Yii::t('app', 'Save'),
]);