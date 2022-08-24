<?php
/**
 * @var integer $id
 */
use common\enums\I18nCategory;
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Load') . ' ' . $id . ' - ' . Yii::t('app', 'Route'),
    'content' => '<p class="alert-warning alert">'.Yii::t('app', 'Error loading map. Perhaps the key token is invalid or expired!').'</p>',
    'options' => [
        'closeButton' => false,
        'saveButton' => false
    ]
]);
