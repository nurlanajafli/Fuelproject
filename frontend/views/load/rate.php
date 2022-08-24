<?php
/**
 * @var \yii\web\View $this
 * @var \frontend\forms\load\Rate $model
 * @var array $formConfig
*/

use common\enums\RateSource;
use common\enums\RateBy;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

$this->beginBlock('form');
$form = ActiveForm::begin(ArrayHelper::merge($formConfig, [
    'id' => 'rateLoadForm',
    'layout' => 'horizontal',
    'fieldConfig' => Yii::$app->params['activeForm']['horizontalFormConfig'],
]));
$form->fieldConfig = Yii::$app->params['activeForm']['horizontalContactFormConfig'];
?>
  <div class="row">
  <div class="col-6">
    <?= $form->field($model, 'source')->dropdownList(RateSource::getUiEnums()) ?>
  </div>
  <div class="col-6" id="selectMatrix">
    <?= $form->field($model, 'matrixNumber')->widget(\common\widgets\tdd\Dropdown::class, [
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
                    'value' => function ($matrixModel) use ($model) {
                        return $matrixModel->calculate($model->getLoad())['rate'];
                    },
                ])
            ],
            'ordering' => false,
            'attributes' => ['data-callback' => 'rateloadmxnuch'],
        ],
        'lookupColumnIndex' => 0,
        'displayColumnIndex' => 0,
    ]) ?>
  </div>
</div>
<hr>
<div class="d-flex">
   <div class="col-5">
     <div class="form-fieldset">
       <?php
       $items = [];
       $options = ['id' => 'rateLoadRateBy', 'options' => [], 'prompt' => Yii::t('app', 'Select')];
       $rateMethod = $model->getLoad()->ratingMatrix ? $model->getLoad()->ratingMatrix->rate_method : null;
       $rateSource = $model->getLoad()->rate_source;
       foreach ($model->getRateByValues() as $key => $value) {
           $items[$key] = $value['value'];
           $class = implode('+', array_map(function ($value) { return $value ?? 'null'; }, array_unique(array_merge($value['methods'], $value['source'], (in_array($rateMethod, $value['methods']) && in_array($rateSource, $value['source'])) ? [] : ['d-none']))));
           $class = str_replace([' ', '+'], ['', ' '], $class);
           $options['options'][$key] = ['class' => $class];
       }
       ?>
       <?= $form->field($model, 'rateBy')->dropdownList($items, $options) ?>
       <?= $form->field($model, 'rate')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01, 'id' => 'rateLoadRate']) ?>
       <?= $form->field($model, 'units')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01, 'readonly' => 'readonly']) ?>
       <div class="form-group row">
         <label class="col-sm-4"><?= Yii::t('app', 'Gross') ?></label>
         <p class="col-sm-6" id="modelGross"><?= $model->getLoad()->gross ?></p>
       </div>
     </div>
   </div>
   <div class="col-5">
     <div class="form-fieldset">
         <?= $form->field($model, 'discountPct')->textInput(['type' => 'number', 'min' => 0,  'step' => 0.01]) ?>
         <div class="form-group row">
           <label class="col-sm-4"><?= Yii::t('app', 'Discount Amt') ?></label>
           <p class="col-sm-6" id="modelDiscountAmount"><?= $model->getLoad()->discount_amount ?></p>
         </div>
         <div class="form-group row">
           <label class="col-sm-4"><?= Yii::t('app', 'Freight Rev') ?></label>
           <p class="col-sm-6"  id="modelFreight"><?= $model->getLoad()->freight ?></p>
         </div>
         <div class="form-group row">
           <label class="col-sm-4"><?= Yii::t('app', 'Accessorials') ?></label>
           <p class="col-sm-6" id="modelAccessories"><?= $model->getLoad()->accessories ?></p>
         </div>
     </div>
   </div>
   <div class="col-2">
       <?= Yii::t('app', 'Total Revenue') ?>
       <p class="col-sm-6" id="modelTotal"><?= $model->getLoad()->total ?></p>
   </div>
</div>
<?php
ActiveForm::end();
$this->endBlock(); ?>
<?= \common\widgets\ModalForm\ModalForm::widget([
    'dialogCssClass' => 'modal-xl',
    'title' => Yii::t('app', 'Rate Load {n}', ['n' => $model->getLoad()->id]),
    'content' => $this->blocks['form']
]) ?>
<script type="text/javascript">
    $("#rateLoadRate, #rateLoadRateBy, #rate-discountpct, #selectMatrix input").on('change',function () {
        updateRate();
    });
    function updateRate() {
        var rateBy = $("#rateLoadRateBy").val();
        var rate = $("#rateLoadRate").val();
        var discountpct = $("#rate-discountpct").val();
        var gross = freight = discount_amount = total = 0;
        var accessories = <?= $model->getLoad()->accessories ?>;
        if(rateBy == '<?=RateBy::MILES?>') {
            var billMiles = <?= $model->load->bill_miles  ?? 0; ?>;
            gross = billMiles * rate;
            discount_amount = discountpct * gross;
            freight = gross - discount_amount;
            total = freight + accessories;
            $("#modelGross").empty().append(gross.toFixed(2));
            $("#modelDiscountAmount").empty().append(discount_amount.toFixed(2));
            $("#modelFreight").empty().append(freight.toFixed(2));
            $("#modelAccessories").empty().append(accessories.toFixed(2));
            $("#modelTotal").empty().append(total.toFixed(2));
        }
        if(rateBy == '<?=RateBy::FLAT?>') {
            gross = rate;
            discount_amount = discountpct * gross;
            freight = gross - discount_amount;
            total = freight + accessories;
            $("#modelGross").empty().append(gross);
            $("#modelDiscountAmount").empty().append(discount_amount.toFixed(2));
            $("#modelFreight").empty().append(freight.toFixed(2));
            $("#modelAccessories").empty().append(accessories.toFixed(2));
            $("#modelTotal").empty().append(total.toFixed(2));
        }
    }
</script>