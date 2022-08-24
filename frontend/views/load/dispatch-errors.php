<?php
$this->beginBlock('content');
foreach ($errors as $error) {
    echo '<div class="alert alert-danger fade show" role="alert"><strong>' . $error . '</strong></div>';
}
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', $title),
    'content' => $this->blocks['content'],
    'options' => [
        'dialog' => ['class' => 'modal-lg'],
        'saveButton' => false
    ]
]);