<?php

use common\models\LoadRatingDistance;
use common\models\LoadRatingGeograph;
use common\models\LoadRatingZipZip;
use common\models\LoadRatingZoneZone;
use common\models\State;
use common\models\TrailerType;
use common\models\Zone;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\LoadRatingMatrix $parentModel
 * @var LoadRatingZipZip|LoadRatingZoneZone|LoadRatingGeograph|LoadRatingDistance $rowModel
 */

$this->title = Yii::t('app', 'Matrix #{number} ({method} - {type}) add rule', [
    'number' => $parentModel->number,
    'method' => $parentModel->rate_method,
    'type' => $parentModel->rate_type,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Load Rating Matrix'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' =>  "#$parentModel->number", 'url' => ['update', 'number' => $parentModel->number]];
$this->params['breadcrumbs'][] = Yii::t("app", "Add");

?>

<div class="clearfix crud-navigation">
    <div class="pull-left">
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t("app", "List Rules"), ['load-rating/update', 'number' => $parentModel->number], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<div class="load-rating-row-form">

    <?php $form = ActiveForm::begin([
            'id' => 'LoadRatingMatrixRow',
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
    );?>

    <div>
        <?= $form->field($rowModel, 'matrix')->hiddenInput()->label(false); ?>

        <?php foreach ($rowModel->getColumns($parentModel->rate_type) as $col) : ?>
            <?php if ($col == 'trl_type') : ?>
                <?= $form
                    ->field($rowModel, $col)
                    ->dropDownList(ArrayHelper::map(TrailerType::find()->all(), 'type', '_label'), ['prompt' => 'Select']);
                ?>
            <?php elseif ($col == 'zone_1' or $col == 'zone_2') : ?>
                <?= $form
                    ->field($rowModel, $col)
                    ->dropDownList(ArrayHelper::map(Zone::find()->all(), 'code', '_label'), ['prompt' => 'Select']);
                ?>
            <?php elseif ($col == 'origin_state' or $col == 'dest_state') : ?>
                <?= $form
                    ->field($rowModel, $col)
                    ->dropDownList(ArrayHelper::map(State::find()->all(), 'id', '_label'), ['prompt' => 'Select']);
                ?>
            <?php else : ?>
                <?= $form
                    ->field($rowModel, $col)
                    ->textInput($rowModel->getFieldParams($rowModel, $col))
                ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <?=Html::tag(
        "div",
        Html::submitButton(
            '<span class="glyphicon glyphicon-check"></span> '
            . Yii::t("app", "Save"), ['class' => 'btn btn-success']
        ),
        ["class" => "text-left"]
    ); ?>

    <hr/>
    <?php echo $form->errorSummary($rowModel); ?>
    <?php ActiveForm::end(); ?>
</div>

