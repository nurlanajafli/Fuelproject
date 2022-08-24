<div id="modals">
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <form action="<?= \yii\helpers\Url::toRoute('site/logout') ?>" method="post">
                        <?= \yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><?= Yii::t('app', 'Logout') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    $array = \yii\helpers\ArrayHelper::getValue($this->params, 'modals', []);
    array_walk($array, function ($config) {
        echo \common\widgets\ModalForm\ModalForm::widget($config);
    });
    ?>
</div>
