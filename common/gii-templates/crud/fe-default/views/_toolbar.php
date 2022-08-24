<?php
/*
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */
echo "<?php\n";
?>

/**
* @var yii\web\View $this
* @var string $modelName
*/
?>
<div class="card-header py-3">
    <div class="edit-form-toolbar">
        <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?= "<?= Yii::t('{$generator->messageCategory}', 'Images') ?>" ?>"><i class="fas fa-image"></i></button>
        <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?= "<?= Yii::t('{$generator->messageCategory}', 'Balances') ?>" ?>"><i class="fas fa-check"></i></button>
        <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?= "<?= Yii::t('{$generator->messageCategory}', 'Reccuring Time Off') ?>" ?>"><i class="fas fa-calendar-alt"></i></button>
        <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?= "<?= Yii::t('{$generator->messageCategory}', 'Taxes and Adjustments') ?>" ?>"><i class="fas fa-calculator"></i></button>
        <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?= "<?= Yii::t('{$generator->messageCategory}', 'Register '.\$modelName.' for Mobile App') ?>" ?>"><i class="fas fa-mobile"></i></button>
        <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?= "<?= Yii::t('{$generator->messageCategory}', 'Terminate '.strtolower(\$modelName)) ?>" ?>"><i class="fas fa-eraser"></i></button>
        <!--
        <div class="dropdown">
            <button class="btn btn-link dropdowwn-toggle" data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="Link">
                <i class="fas fa-link"></i>
                <i class="fas fa-caret-down"></i>
            </button>
            <div class="dropdown-menu"><a class="dropdown-item" href="#">First</a><a class="dropdown-item" href="#">Second</a><a class="dropdown-item" href="#">Third</a></div>
        </div>
        -->
    </div>
</div>