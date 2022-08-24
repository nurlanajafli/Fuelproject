<?php

/**
 * @var $model \common\models\LoginForm
 */

use frontend\assets\AppAsset;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$appAsset = new AppAsset();
$appAsset->register($this);
$this->beginPage();
$this->context->layout = false;
?>
<?=$this->render('//layouts/_header')?>
<body class="bg-gradient-primary">
<?php $this->beginBody(); ?>
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">
                                            <?= Yii::t('app','Welcome Back!') ?>
                                        </h1>
                                    </div>
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'login-form',
                                        'options' => ['class' => 'user'],
                                        'enableClientValidation' => false
                                    ]); ?>

                                    <?= $form->field($model, 'username')->textInput([
                                        'autofocus' => true,
                                        'class' => 'form-control form-control-user',
                                        'placeholder' => $model->getAttributeLabel('username')
                                    ])->label(false) ?>

                                    <?= $form->field($model, 'password')->passwordInput([
                                        'class' => 'form-control form-control-user',
                                        'placeholder' => $model->getAttributeLabel('password')
                                    ])->label(false) ?>

                                    <?= $form->field($model, 'rememberMe', ['template' => '<div class="custom-control custom-checkbox small">{input}{label}{error}</div>'])
                                        ->checkbox(['class' => 'custom-control-input'], false)
                                        ->label($model->getAttributeLabel('rememberMe'), ['class' => 'custom-control-label']) ?>

                                    <?= Html::submitButton(Yii::t('app','Login'), ['class' => 'btn btn-primary btn-user btn-block']) ?>
                                    <?php ActiveForm::end(); ?>

                                    <hr>

                                    <div class="text-center">
                                        <a class="small" href="<?= Url::to(['site/request-password-reset']) ?>">
                                            <?= Yii::t('app','Forgot Password?') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$js = <<< JS
var preventSubmit = true;

$('#login-form').on('submit', function(e) {
    if (preventSubmit) {
        e.preventDefault();
        let form = $("#login-form");
        $.ajax({
            url:'/site/ajax-last-session?username=' + $("#loginform-username").val(),
            method:'get'
        }).done(function(response){
            if (response.status == 'warning') {
                $("#modalContent").html(response.data);
                $("#loginOverrideModal").modal("show");
            } else {
                preventSubmit = false;
                $("#login-form").submit();
            }
        });
        return false;
    }
});

$("#modalForceLogin").on("click", function(){
    preventSubmit = false;
    $("#login-form").submit();
});
JS;

$this->registerJs($js, View::POS_READY);
?>

<div class="modal fade" id="loginOverrideModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Another user session detected!</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalContent" style="font-weight: bold"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button id="modalForceLogin" type="submit" class="btn btn-primary">
                    <?= Yii::t('app', 'Login anyway') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody(); ?>
<?php $this->endPage();