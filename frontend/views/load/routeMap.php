<?php
/**
 * @var integer $id
 * @var string $imgData
 */
use common\enums\I18nCategory;
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Load') . ' ' . $id . ' - ' . Yii::t('app', 'Route'),
    'content' => '<img src="data:image/png;base64,' . $imgData . '">',
    'options' => [
        'dialog' => ['class' => 'modal-map'],
        'closeButton' => false,
        'saveButton' => false
    ]
]);
