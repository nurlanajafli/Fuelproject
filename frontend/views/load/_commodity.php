<?php
/**
 * @var \yii\bootstrap4\ActiveForm $form
 * @var \common\models\Load $model
*/

use common\models\Commodity;
use common\widgets\DataTables\DataColumn;
?>
<div class="form-fieldset">
    <div class="row">
        <div class="col-6">
            <div class="field row">
                <div class="col-4">
                    <label><?= $model->getAttributeLabel('commodity_commodity_id') ?></label>
                </div>
                <?= $form->field($model, 'commodity_commodity_id', ['template' => '{input}{error}', 'options' => ['class' => 'col-8']])->widget(\common\widgets\tdd\Dropdown::class, [
                    'items' => Commodity::find()->orderBy(['id' => SORT_ASC])->all(),
                    'modelClass' => Commodity::class,
                    'lookupColumnIndex' => 0,
                    'displayColumnIndex' => 1,
                    'grid' => [
                        'columns' => [
                            'id|visible=false',
                            'description',
                            new DataColumn([
                                'attribute' => 'hazmat_code',
                                'value' => function ($model) {
                                    /** @var Commodity $model */
                                    if ($rel = $model->hazmatCode) {
                                        return $rel->code;
                                    }
                                    return '';
                                }
                            ]),
                            new DataColumn([
                                'title' => Yii::t('app', 'Equip'),
                                'value' => function ($model) {
                                    /** @var Commodity $model */
                                    // TODO: temp
                                    return '';
                                }
                            ])
                        ],
                        'order' => [[2, 'asc']]
                    ]
                ]) ?>
            </div>
            <div class="field row">
                <div class="col-4">
                    <label><?= $model->getAttributeLabel('commodity_reference') ?></label>
                </div>
                <?= $form->field($model, 'commodity_reference', ['template' => '{input}{error}', 'options' => ['class' => 'col-8']])->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="col-6">
            <div class="field row">
                <div class="col-4">
                    <label><?= $model->getAttributeLabel('commodity_weight') ?></label>
                </div>
                <?= $form->field($model, 'commodity_weight', ['template' => '{input}{error}', 'options' => ['class' => 'col-8']])->textInput(['maxlength' => true, 'type' => 'number']) ?>
            </div>
            <div class="field row">
                <div class="col-4">
                    <label><?= $model->getAttributeLabel('commodity_pieces') ?></label>
                </div>
                <?= $form->field($model, 'commodity_pieces', ['template' => '{input}{error}', 'options' => ['class' => 'col-8']])->textInput(['maxlength' => true]) ?>
            </div>
            <div class="field row">
                <div class="col-4">
                    <label><?= $model->getAttributeLabel('commodity_space') ?></label>
                </div>
                <?= $form->field($model, 'commodity_space', ['template' => '{input}{error}', 'options' => ['class' => 'col-8']])->textInput(['maxlength' => true/*, 'placeholder' => '0.00', 'data-mask' => '#,###.00', 'data-mask-reverse' => true*/]) ?>
            </div>
        </div>
    </div>
</div>