<?php
/**
 * @var \common\models\LoadDrop $model
 * @var array $formConfig
 */

use yii\bootstrap4\ActiveForm;

$this->beginBlock('form');
$form = ActiveForm::begin($formConfig);
?>
<div class="row my-3">
    <div class="col-12">
        <div class="row">
            <div class="form-fieldset">
                <span class="form-legend"><?= Yii::t('app', 'Drop Info') ?></span>
                <div class="row">
                    <div class="col">
                      <?= $form->field($model, 'drop_date')->textInput(['type' => 'date']) ?>
                    </div>
                    <div class="col">
                        <?= $form->field($model, 'drop_time_from')->textInput(['type' => 'time']) ?>
                    </div>
                    <div class="col">
                        <?= $form->field($model, 'drop_time_to')->textInput(['type' => 'time']) ?>
                    </div>
                </div>
              <div class="row">
                <div class="col">
                    <?= $form->field($model, 'location_id')->widget(\common\widgets\tdd\Dropdown::class, [
                        'grid' => [
                            'dataProvider' => new \yii\data\ActiveDataProvider([
                                'query' => \common\models\Location::find()->joinWith('state'),
                                'pagination' => false
                            ]),
                            'columns' => [
                                'id|visible=false',
                                'company_name|title=Name',
                                'address',
                                'city',
                                'null|rel=state.state_code|title=St',
                                'zip',
                                'zone',
                                new \common\widgets\DataTables\CheckboxColumn([
                                    'title' => Yii::t('app', 'PRP')
                                ]),
                                new \common\widgets\DataTables\CheckboxColumn([
                                    'title' => Yii::t('app', 'Inactive')
                                ]),
                            ]
                        ]
                    ])->label(false) ?>
                </div>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="form-fieldset">
            <span class="form-legend"><?= Yii::t('app', 'Retrieval Info') ?></span>
            <div class="row">
              <div class="col"><?= $form->field($model, 'retrieve_date')->textInput(['type' => 'date']) ?></div>
              <div class="col"><?= $form->field($model, 'retrieve_time')->textInput(['type' => 'time']) ?></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-fieldset">
            <span class="form-legend"><?= $model->getAttributeLabel('trailer_disposition') ?></span>
            <div class="row">
                <?php
                $enums = \common\enums\TrailerDisposition::getEnums();
                echo $form->field($model, 'trailer_disposition', ['errorOptions' => ['class' => 'invalid-feedback d-none'], 'radioOptions' => ['class' => 'custom-control-input', 'labelOptions' => ['class' => 'custom-control-label'], 'wrapperOptions' => ['class' => 'custom-control custom-radio custom-control-inline']]])->
                radioList(array_combine($enums, $enums))->label(false);
                ?>
            </div>
          </div>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-lg',
    'title' => Yii::t('app', 'Drop Load'),
    'saveButton' => Yii::t('app', 'Drop'),
]);
