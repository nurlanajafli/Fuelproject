<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/*
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

$urlParams = $generator->generateUrlParams();
/** @var yii\db\ActiveRecord $model */
$model = new $generator->modelClass();
$model->setScenario('crud');
$modelName = Inflector::camel2words(StringHelper::basename($model::className()));
$str1 = $generator->generateString('View');
$str2 = $generator->generateString('Full list');

echo "<?php\n";
?>
use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var <?= ltrim($generator->modelClass, '\\') ?> $model
*/

$this->title = Yii::t('<?= $generator->modelMessageCategory ?>', '<?= $modelName ?>');
$this->params['breadcrumbs'][] = ['label' => Yii::t('<?= $generator->modelMessageCategory ?>', '<?= Inflector::pluralize($modelName) ?>'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = Yii::t('<?= $generator->messageCategory ?>', 'Edit');
?>
<div class="giiant-crud <?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-update">

    <div class="crud-navigation float-right">
        <?= "<?= Html::a('<i class=\"fas fa fa-binoculars\"></i> ' . $str1, ['view', $urlParams], ['class' => 'btn btn-sm btn-secondary']) ?>\n" ?>
        <?= "<?= Html::a('<i class=\"fas fa fa-list\"></i> ' . $str2, ['index'], ['class' => 'btn btn-sm btn-secondary']) ?>\n" ?>
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
