<?php

/* @var $model \frontend\models\PasswordResetRequestForm */

use frontend\assets\AppAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use common\enums\I18nCategory;

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
                            <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2"><?= Yii::t('app', 'Forgot Your Password?')?></h1>
                                        <p class="mb-4"><?= Yii::t('app', 'We get it, stuff happens. Just enter your email address below and we\'ll send you a link to reset your password!')?></p>
                                    </div>
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'request-password-reset-form',
                                        'options' => ['class' => 'user'],
                                        'enableClientValidation' => false
                                    ]); ?>

                                    <?= $form->field($model, 'email')->textInput([
                                        'type' => 'email',
                                        'autofocus' => true,
                                        'class' => 'form-control form-control-user',
                                        'placeholder' => Yii::t('app', 'Enter Email Address...')
                                    ])->label(false) ?>

                                    <?= Html::submitButton(Yii::t('app','Reset Password'), ['class' => 'btn btn-primary btn-user btn-block']) ?>

                                    <?php ActiveForm::end(); ?>
                                    <hr>
<!--                                    <div class="text-center">-->
<!--                                        <a class="small" href="register.html">Create an Account!</a>-->
<!--                                    </div>-->
                                    <div class="text-center">
                                        <a class="small" href="<?= Url::to(['site/login']) ?>"><?= Yii::t('app', 'Already have an account? Login!')?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <!-- Bootstrap core JavaScript-->
<!--    <script src="vendor/jquery/jquery.min.js"></script>-->
<!--    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>-->
   <!-- Core plugin JavaScript-->
<!--    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>-->
   <!-- Custom scripts for all pages-->
<!--    <script src="js/sb-admin-2.min.js"></script>-->
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage();
