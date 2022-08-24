<?php
/**
 * @var \yii\web\View $this
 */

/** @var \common\widgets\ModalForm\ModalForm $widget */
$widget = $this->context;
?>
<div class="<?php echo $widget->cssClass . ($widget->id ? "\" id=\"$widget->id" : '') ?>" tabindex="-1">
    <div class="<?= $widget->dialogCssClass ?>">
        <div class="modal-content">
            <div class="<?= $widget->headerCssClass ?>">
                <h5 class="modal-title"><?= $widget->title ?></h5>
                <?php if ($widget->closeButtonInHeader): ?><button class="close" type="button" data-dismiss="modal" aria-label="Close"><span<?= (strpos($widget->headerCssClass, 'text-white') ? ' class="text-white"' : '') ?>>&times;</span></button><?php endif; ?>
            </div>
            <?php if ($widget->toolbar) : ?>
            <div class="modal-toolbar bg-light"><?= $widget->toolbar ?></div>
            <?php endif; ?>
            <?= $widget->beforeBodyHtml ?>
            <div class="<?= $widget->bodyCssClass ?>">
                <?= $widget->content ?>
            </div>
            <?php if ($widget->closeButton || $widget->beforeSaveButtonHtml || $widget->saveButton): ?>
            <div class="<?= $widget->footerCssClass ?>">
                <?php
                if ($widget->closeButton)
                    echo '<button class="btn btn-secondary" type="button" data-dismiss="modal">' . $widget->closeButton . '</button>';

                echo $widget->beforeSaveButtonHtml;

                if ($widget->saveButton)
                    echo '<button class="btn btn-primary" type="button" data-code="save">' . $widget->saveButton . '</button>';
                ?>
            </div>
            <?php endif; ?>
            <?= $widget->afterFooterHtml ?>
        </div>
    </div>
</div>
