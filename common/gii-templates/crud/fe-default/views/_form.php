<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/*
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

/** @var yii\db\ActiveRecord $model */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $model->setScenario('default');
    $safeAttributes = $model->safeAttributes();
}
if (empty($safeAttributes)) {
    $safeAttributes = $model->getTableSchema()->columnNames;
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Tabs;

/**
* @var yii\web\View $this
* @var <?= ltrim($generator->modelClass, '\\') ?> $model
* @var yii\widgets\ActiveForm $form
*/

$relAttributes = $model->attributes;
?>
<section class="edit-form <?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-form">

    <?= '<?php ' ?>$form = ActiveForm::begin([
            'id' => '<?= $model->formName() ?>',
            'layout' => '<?= $generator->formLayout ?>',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-danger',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    #'offset' => 'col-sm-offset-4',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]
    );
    $this->beginBlock('main');
    ?>
    <div class="row">
        <div class="col-lg">
            <?php
            $n = "\n";
            $t = "\t\t\t";
            foreach ($safeAttributes as $attribute) {
                echo "$n$n$t<!-- attribute $attribute -->";
                $prepend = $generator->prependActiveField($attribute, $model);
                $field = $generator->activeField($attribute, $model);
                $append = $generator->appendActiveField($attribute, $model);

                if ($prepend) {
                    echo "$n$t$prepend";
                }
                if ($field) {
                    echo "$n$t<?= $field ?>";
                }
                if ($append) {
                    echo "$n$t$append";
                }
            }
            echo $n;
            ?>
        </div>
    </div>
    <?php
    $create = $generator->generateString('Create');
    $save = $generator->generateString('Save');
    $out = <<< EOS
    %tagOpen%\$this->endBlock();%tagClose%
    %tagOpen%echo Tabs::widget([
        'encodeLabels' => false,
        'items' => [
            [
                'label' => Yii::t('{$generator->modelMessageCategory}', 'Personal'),
                'content' => \$this->blocks['main'],
                'active' => true,
            ],
            ['label' => Yii::t('{$generator->modelMessageCategory}', 'Pay Defaults')],
            ['label' => Yii::t('{$generator->modelMessageCategory}', 'Dispatch Pay')],
            ['label' => Yii::t('{$generator->modelMessageCategory}', 'Fuel Cards')],
            ['label' => Yii::t('{$generator->modelMessageCategory}', 'Settlement Processing')],
        ]]);%tagClose%
    %tagOpen%echo "\\n<hr/>\\n";%tagClose%
    %tagOpen%echo \$form->errorSummary(\$model);%tagClose%
    %tagOpen%echo Html::submitButton('<i class="fas fa fa-check"></i> '.(\$model->isNewRecord ? $create : $save),['id' => 'save-' . \$model->formName(), 'class' => 'btn btn-success']);%tagClose%
    %tagOpen%ActiveForm::end();%tagClose%
    EOS;
    $out = str_replace("%tagOpen%","<?php ",$out);
    $out = str_replace("%tagClose%"," ?>",$out);
    $out = str_replace("\n","\n\t",$out);
    echo "$out\n";
    ?>
</section>