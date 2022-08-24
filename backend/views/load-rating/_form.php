<?php

use common\enums\LoadRateMethod as M;
use common\enums\LoadRateType as T;
use dmstr\bootstrap\Tabs;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\LoadRatingMatrix $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="load-rating-form">

    <?php $form = ActiveForm::begin([
            'id' => 'LoadRatingMatrix',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-danger',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '', // 'collapse',
                ],
            ],
        ]
    );
    ?>

    <div class="">
        <?php $this->beginBlock('main'); ?>
        <p>
            <?= $form->field($model, 'number')->textInput(['maxlength' => true, 'readonly' => !$model->isNewRecord]) ?>
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'rate_method')
                        ->dropDownList(M::getUiEnums(), [
                            'prompt' => 'Select',
                            'readonly' => !$model->isNewRecord,
                            'onchange' => '$("#loadratingmatrix-rate_type option").attr("disabled","disabled"); $("#loadratingmatrix-rate_type option." + this.value).removeAttr("disabled");'
                        ])
            ?>
            <?= $form->field($model, 'rate_type')
                    ->dropDownList(T::getUiEnums(), [
                        'prompt' => 'Select',
                        'readonly' => !$model->isNewRecord,
                        'options' => array_combine(
                            T::getUiEnums(),
                            array_map(
                                function($val){ return ['class' => implode(" ", M::getMethodsByType($val))]; },
                                T::getUiEnums()
                            )
                        )
                    ])
            ?>
            <?php echo $form->field($model, 'inactive')->checkbox([], false) ?>
            <?php echo $form->field($model, 'loaded_and_empty')->checkbox([], false) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => [
                    [
                        'label'   => Yii::t('app', 'Load Rating Matrix'),
                        'content' => $this->blocks['main'],
                        'active'  => true,
                    ],
                ]
            ]
        );
        ?>

        <?php
            $content = Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . Yii::t("app", "Save"), ['class' => 'btn btn-success']);
            if ($this->context->action->id == 'create') {
                echo Html::tag("div", $content, ["class" => "text-left"]);
            } else {
                $content = Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . Yii::t("app", "Save"), ['class' => 'btn btn-success']);
                $content .= "&nbsp;";
                $content .= Html::a(
                        '<span class="glyphicon glyphicon-remove"></span> ' . Yii::t("app", "Delete"),
                        ["delete", "number" => $model->number],
                        ['class' => 'btn btn-danger', 'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this matrix?'),
                            'method' => 'post',
                        ]]
                );
                echo Html::tag("div", $content, ["class" => "text-right"]);
            }
        ?>

        <hr/>

        <?php echo $form->errorSummary($model); ?>
        <?php ActiveForm::end(); ?>

    </div>

</div>

