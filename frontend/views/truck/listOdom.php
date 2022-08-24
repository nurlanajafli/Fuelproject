<?php
/**
 * @var $data TruckOdometer[]
 * @var $id int truck_id
 */

use common\helpers\DateTime;
use common\models\TruckOdometer;
use yii\widgets\ActiveForm;

$odom = new TruckOdometer();
$odom->date_collected = DateTime::nowDateYMD();
$odom->truck_id = $id;

$this->beginBlock('form');
$form = ActiveForm::begin([
    'action' => ['truck/add-odom'],
    'options' => ['method' => 'post']
]); ?>
    <div class="col">
        <?= $form->field($odom, 'truck_id', ['options' => ['tag' => false]])->hiddenInput()->label(false); ?>
        <div class="row">
            <?= $form->field($odom, 'date_collected', ['template' => '<div class="col-5">{input}{error}{hint}</div>', 'options' => ['tag' => false]])
                ->textInput(['type' => 'date']) ?>

            <?= $form->field($odom, 'odometer', ['template' => '<div class="col-5">{input}{error}{hint}</div>', 'options' => ['tag' => false]])
                ->textInput(['type' => 'number', 'placeholder' => 'Odometer data']) ?>
            <div class="col-2"></div>
        </div>
    </div>
    <br/>
    <div class="col">
        <table class="table table-striped">
            <thead class="thead-light">
            <tr>
                <th><?= Yii::t('app', 'Date') ?></th>
                <th><?= Yii::t('app', 'Odom') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $model) : ?>
                <tr>
                    <td><?= $model->date_collected ?></td>
                    <td><?= $model->odometer ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php ActiveForm::end() ?>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Odometer data'),
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-lg'
]);
