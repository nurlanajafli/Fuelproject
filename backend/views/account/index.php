<?php
/**
 * /var/www/html/backend/runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22
 *
 * @package default
 */


use backend\widgets\Datatable;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */
$this->title = Yii::t('app', 'Accounts');
$this->params['breadcrumbs'][] = $this->title;

if (isset($actionColumnTemplates)) {
	$actionColumnTemplate = implode(' ', $actionColumnTemplates);
	$actionColumnTemplateString = $actionColumnTemplate;
} else {
	Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New', ['create'], ['class' => 'btn btn-success']);
	$actionColumnTemplateString = "{view} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>
<div class="giiant-crud account-index">

    <div>
        <?php echo Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <br />

    <?= Datatable::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ["class" => "table table-striped table-bordered table-condensed"],
        'clientOptions' => ['pageLength' => 20, 'lengthMenu' => [10, 15, 20, 25, 50, 100]],
        'columns' => [
            'account',
            'description',
            ['attribute' => 'account_type', 'value' => 'accountType.type'],
            'filter',
            'active:boolean',
            'hidden:boolean',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        $url = Url::to(['account/view', 'account' => $model->account]);
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                    },
                    'update' => function ($url, $model) {
                        $url = Url::to(['account/update', 'account' => $model->account]);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                    },
                    'delete' => function ($url, $model) {
                        $url = Url::to(['account/delete', 'account' => $model->account]);
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);
                    },
                ]
            ]
        ]
    ]) ?>

</div>
