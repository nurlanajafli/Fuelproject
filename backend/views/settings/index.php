<?php
/**
 * @var \common\components\View $this
 * @var array $map
 */

use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
          <div class="row">
            <div class="col-lg-12">
              <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row clearfix">
                      <div class="col-sm-12">
                        <div class="form-horizontal">
                            <?php
                            foreach ($map as $value) :
                                $model = new \common\models\Setting();
                                $model->key = $value[2];
                                $model->value = $value[1];
                                $form = ActiveForm::begin(['options' => ['class' => 'prevent-submit']]);
                                echo $form->field($model, 'key', [
                                    'options' => ['tag' => false],
                                    'template' => '{input}',
                                    'inputOptions' => ['id' => "setting-key{$model->key}"]
                                ])->hiddenInput();
                                echo $form->field($model, 'value', [
                                    'labelOptions' => ['class' => 'col-sm-2 control-label', 'for' => "setting-value{$model->key}"],
                                    'inputOptions' => ['id' => "setting-value{$model->key}"],
                                    'template' => '{label}<div class="col-sm-8">{input}{hint}{error}</div>'
                                ])->textInput()->label($value[0]);
                                ActiveForm::end();
                            endforeach;
                            ?>
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="form-actions pull-right">
                          <button class="btn btn-primary" type="button" id="setting-save" data-action="<?= \yii\helpers\Url::toRoute('update') ?>">Save</button>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
