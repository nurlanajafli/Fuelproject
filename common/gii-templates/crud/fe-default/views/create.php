<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/*
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

/** @var yii\db\ActiveRecord $model */
$model = new $generator->modelClass();
$model->setScenario('crud');
$modelName = Inflector::camel2words(StringHelper::basename($model::className()));
$cancel = $generator->generateString('Cancel');

echo "<?php\n";
?>
use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var <?= ltrim($generator->modelClass, '\\') ?> $model
*/

$this->title = Yii::t('<?= $generator->modelMessageCategory ?>', '<?= $modelName ?>');
$this->params['breadcrumbs'][] = ['label' => Yii::t('<?= $generator->modelMessageCategory ?>', '<?= Inflector::pluralize($modelName) ?>'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud <?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-create">

    <div class="crud-navigation float-right">
        <?= "<?= Html::a('<i class=\"fas fa fa-undo\"></i> ' . $cancel, \yii\helpers\Url::previous(), ['class' => 'btn btn-sm btn-secondary']) ?>\n" ?>
    </div>

    <h1 class="h3 mb-4 text-gray-800">
        <?= "<?= Yii::t('{$generator->modelMessageCategory}', '{$modelName}') ?>\n" ?>
        <small><?= '<?= Html::encode($model->'.$generator->getModelNameAttribute($generator->modelClass).') ?>' ?></small>
    </h1>

    <div class="card shadow mb-4">
        <?= '<?= $this->render("_toolbar", ["modelName" => "'.$modelName.'"]) ?>'."\n" ?>
        <div class="card-body">
            <?= '<?= $this->render("_form", ["model" => $model]) ?>'."\n" ?>
        </div>
    </div>

</div>
