<?php

use common\models\FuelcardAccountConfig as FCC;
use common\widgets\ModalForm\ModalForm;
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
<?php /*
    <div class="form-fieldset"><span class="form-legend"><?=Yii::t('app', 'Comdata Web Service')?></span>
        <div class="field row">
            <div class="col-3"><label><?=Yii::t('app', 'NT Signon')?></label></div>
            <div class="col-6"><input class="form-control" type="text" name="<?=FCC::NT_SIGNON?>" value="<?=$model->config[FCC::NT_SIGNON]?>" required></div>
        </div>
        <div class="field row">
            <div class="col-3"><label><?=Yii::t('app', 'NT Password')?></label></div>
            <div class="col-6"><input class="form-control" type="password" name="<?=FCC::NT_PASSWORD?>" value="<?=$model->config[FCC::NT_PASSWORD]?>" required></div>
        </div>
        <h5 class="pt-4 text-black-50"><?=Yii::t('app', 'Real Time Info')?></h5>
        <hr />
        <div class="field row">
            <div class="col-3"><label><?=Yii::t('app', 'Account Code')?></label></div>
            <div class="col-6"><input class="form-control" type="text" name="<?=FCC::RT_ACCOUNT_CODE?>" value="<?=$model->config[FCC::RT_ACCOUNT_CODE]?>" required></div>
        </div>
        <div class="field row">
            <div class="col-3"><label><?=Yii::t('app', 'Customer ID')?></label></div>
            <div class="col-6"><input class="form-control" type="text" name="<?=FCC::RT_CUSTOMER_ID?>" value="<?=$model->config[FCC::RT_CUSTOMER_ID]?>" required></div>
        </div>
        <div class="field row">
            <div class="col-3"><label><?=Yii::t('app', 'Password')?></label></div>
            <div class="col-6"><input class="form-control" type="password" name="<?=FCC::RT_PASSWORD?>" value="<?=$model->config[FCC::RT_PASSWORD]?>" required></div>
        </div>
        <div class="field row">
            <div class="col-3"><label><?=Yii::t('app', 'Security Info')?></label></div>
            <div class="col-6"><input class="form-control" type="text" name="<?=FCC::RT_SECURITY_INFO?>" value="<?=$model->config[FCC::RT_SECURITY_INFO]?>" required></div>
        </div>
        <div class="field row">
            <div class="col-3"><label><?=Yii::t('app', 'Sign-On Name')?></label></div>
            <div class="col-6"><input class="form-control" type="text" name="<?=FCC::RT_SIGNON_NAME?>" value="<?=$model->config[FCC::RT_SIGNON_NAME]?>" required></div>
        </div>
    </div>
 */ ?>

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