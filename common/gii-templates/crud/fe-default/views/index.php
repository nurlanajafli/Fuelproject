<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use schmunk42\giiant\generators\model\Generator as ModelGenerator;

/*
 * @var yii\web\View $this
 * @var schmunk42\giiant\generators\crud\Generator $generator
 */

/** @var \yii\db\ActiveRecord $model */
$model = new $generator->modelClass();
$model->setScenario('crud');

$modelName = Inflector::camel2words(Inflector::pluralize(StringHelper::basename($model::className())));

$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    /** @var \yii\db\ActiveRecord $model */
    $model = new $generator->modelClass();
    $safeAttributes = $model->safeAttributes();
    if (empty($safeAttributes)) {
        $safeAttributes = $model->getTableSchema()->columnNames;
    }
}
$primaryKey = $model->primaryKey();

echo "<?php\n";
?>

use yii\data\ActiveDataProvider;
use common\widgets\DataTables\Grid;
use common\widgets\DataTables\ActionColumn;
use common\widgets\DataTables\DataColumn;
use common\enums\I18nCategory;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var ActiveDataProvider $dataProvider
*/
$this->title = <?php echo "Yii::t('{$generator->modelMessageCategory}', '$modelName');"; ?>

$this->params['breadcrumbs'][] = $this->title;
<?php if ($generator->accessFilter): ?>
/**
* create action column template depending access rights
*/
$actionColumnTemplates = [];

if (\Yii::$app->user->can('<?=$permisions['view']['name']?>', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
}

if (\Yii::$app->user->can('<?=$permisions['update']['name']?>', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
}

if (\Yii::$app->user->can('<?=$permisions['delete']['name']?>', ['route' => true])) {
    $actionColumnTemplates[] = '{delete}';
}
<?php else: ?>
$actionColumnTemplates = ['{view}', '{update}', '{delete}'];
<?php endif; ?>
$actionColumn = new ActionColumn(['buttons' => array_map(function ($action) {
    $str = str_replace(['{', '}'], '', $action);
    $res = [
        'a' => ['text' => Yii::t('<?= $generator->messageCategory ?>', ucfirst(($str=='update')?'edit':$str)), 'options' => ['class' => 'btn btn-default btn-sm shadow-sm']],
        'icon' => ['class' => 'fas fa-sm text-red-50 fa-'],
<?php if ($primaryKey): ?>
        'callback' => function ($model, &$button) use ($str) {
            $button['a']['url'] = [$str, '<?= $primaryKey[0] ?>' => $model-><?= $primaryKey[0] ?>];
        }
<?php endif; ?>
    ];
    switch ($action) {
        case '{view}':
            $res['icon']['name'] = 'binoculars';
            break;
        case '{update}':
            $res['icon']['name'] = 'edit';
            break;
        case '{delete}':
            $res['a']['options']['class'] = 'btn btn-outline-danger btn-sm shadow-sm js-row-remove';
            $res['a']['options']['data-confirm'] = Yii::t('<?= $generator->messageCategory ?>', 'Are you sure to delete this item?');
            $res['a']['options']['data-method'] = 'post';
            $res['icon']['class'] = 'fas fa-sm text-red-50 fa-';
            $res['icon']['name'] = 'trash';
            break;
    }
    return $res;
}, $actionColumnTemplates)]);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo "<?= Yii::t('{$generator->modelMessageCategory}', '$modelName') ?>"; ?></h1>
</div>
<?= "<?php\n" ?>echo Grid::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
<?php
$count = 0;
$t = "\t\t";
$n = "\n";
if ($generator->actionButtonColumnPosition != 'right') {
    echo "$t\$actionColumn,$n";
}
foreach ($safeAttributes as $attribute) {
    $format = "'" . trim($attribute) . "'";
    $column = $generator->getColumnByAttribute($attribute, $model);
    if ($column && !is_string($column)) {
        $relation = $generator->getRelationByColumn($model, $column, ['belongs_to']);
        if ($relation && !$relation->multiple) {
            $title = $generator->getModelNameAttribute($relation->modelClass);
            $modelClass = $generator->modelClass;
            $relationProperty = lcfirst((new ModelGenerator())->generateRelationName(
                [$relation],
                $modelClass::getTableSchema(),
                $column->name,
                $relation->multiple
            ));
            $format = <<< EOS
new DataColumn([
    'attribute' => '{$column->name}',
    'value' => function (\$model) {
        if (\$rel = \$model->{$relationProperty}) {
            return \$rel->{$title};
        }
        return '';
    }
])
EOS;
        }
    }

    if (++$count < $generator->gridMaxColumns) {
        echo $t . str_replace($n, "$n$t", $format) . ",$n";
    } else {
        echo $t . '/*' . str_replace($n, "$n$t", $format) . ",*/$n";
    }
}
if ($generator->actionButtonColumnPosition == 'right') {
    echo "$t\$actionColumn,$n";
}
?>
    ],
    'toolbarHtml' => '<a class="btn btn-sm btn-secondary" href="' . Url::toRoute('edit') . '"><i class="fas fa fa-plus"></i> ' . Yii::t('app', 'New') . '</a>',
]);
