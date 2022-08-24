<?php

use common\widgets\ModalForm\ModalForm;
use common\models\FuelcardAccountConfig as FCC;
use yii\helpers\Html;

/**
 * @var string $provider
 * @var FCC $model
 */

$this->beginBlock('content'); ?>

    <div class="form-fieldset">
        <div class="field row">
            <div class="col-3"><label><?=Yii::t('app', 'Account ID')?></label></div>
            <div class="col-6"><input class="form-control" type="text" name="<?=FCC::ACCOUNT?>" value="<?=$model->config[FCC::ACCOUNT]?>" required></div>
        </div>
        <div class="row">
            <div class="col-6 offset-3">
                <div class="field"><p class="form-readonly-text"><?=$provider?></p></div>
                <input type="hidden" name="provider" value="<?=$provider?>">
            </div>
        </div>
        <div class="field row">
            <div class="col-3"><label><?=Yii::t('app', 'Username')?></label></div>
            <div class="col-6"><input class="form-control" type="text" name="<?=FCC::USERNAME?>" value="<?=$model->config[FCC::USERNAME]?>" required></div>
        </div>
        <div class="field row">
            <div class="col-3"><label><?=Yii::t('app', 'Password')?></label></div>
            <div class="col-6"><input class="form-control" type="password" name="<?=FCC::PASSWORD?>" value="<?=$model->config[FCC::PASSWORD]?>" required></div>
        </div>
    </div>

<?php $this->endBlock();

echo ModalForm::widget([
    'content' => $this->blocks['content'],
    'dialogCssClass' => 'modal-lg',
    'saveButton' => false,
    'title' => Yii::t('app', 'FTP Account Setup'),
    'beforeSaveButtonHtml' => '<button class="btn btn-primary" type="submit" >Save</button>',
    'beforeBodyHtml' => Html::beginForm(['fuel/save-account-config']),
    'afterFooterHtml' => Html::endForm(),
]);