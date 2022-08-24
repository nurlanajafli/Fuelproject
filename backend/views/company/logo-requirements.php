<?php
/**
 * @var \yii\web\View $this
*/

$this->beginBlock('content');
?>
    <div class="row">
        <div class="col-sm-2"><span class="glyphicon glyphicon-info-sign text-info"></span></div>
        <div class="col-sm-10">
            <p><?= Yii::t('app', 'The company logo image prints on invoices and other documents') ?></p>
            <p><?= Yii::t('app', 'The image file must meet the following requirements:') ?></p>
            <ul>
                <!--<li>- 150 pixels wide</li>
                <li>- 75 pixels tall</li>-->
                <li>- <?= Yii::t('app', '100 Kb or less') ?></li>
                <li>- <?= Yii::t('app', '.jpg, .jpeg or .png format') ?></li>
            </ul>
        </div>
    </div>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Express'),
    'content' => $this->blocks['content'],
    'closeButtonInHeader' => false,
    'saveButton' => false,
    'closeButton' => Yii::t('app', 'OK')
]);
