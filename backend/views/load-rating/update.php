<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var yii\web\View $this
 * @var common\models\LoadRatingMatrix $model
 * @var $dataProvider
 */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Load Rating Matrix'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t("app", "Edit");
?>
<div class="giiant-crud">

    <h1>
        <?= Yii::t('app', 'Load Rating Matrix') ?>
        <small><?= Html::encode($model->number) ?></small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t("app", "List"), ['load-rating/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr/>

    <?= $this->render('_form', ['model' => $model]); ?>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t("app", "New"), ['create-row', 'number' => $model->number], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php
        Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t("app", "New"), ['create'], ['class' => 'btn btn-success']);
        $actionColumnTemplateString = "{update-row} {delete-row}";
    ?>
    <?php
        $columns = array_merge([
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $actionColumnTemplateString,
                'buttons' => [
                    'update-row' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('app', 'Edit'),
                            'aria-label' => Yii::t('app', 'Edit'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, $options);
                    },
                    'delete-row' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('app', 'Delete'),
                            'aria-label' => Yii::t('app', 'Delete'),
                            'data-pjax' => '0',
                            'data-confirm' => Yii::t('app','Are you sure to delete this item?')
                        ];
                        return Html::a('<span class="glyphicon glyphicon-remove-circle"></span>', $url, $options);
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params = is_array($key) ? $key : ["number" => $model->matrix, "id" => (string)$key];
                    $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                    return Url::toRoute($params);
                },
                'contentOptions' => ['nowrap' => 'nowrap']
            ],
        ], $model->getColumns());

        if ($model->rate_method == \common\enums\LoadRateMethod::GEOGRAPH) {
            foreach ($columns as &$column) {
                if ($column == 'origin_state' || $column == 'dest_state') {
                    $column = [
                        'class' => 'yii\grid\DataColumn',
                        'attribute' => $column,
                        'value' => function ($data) use ($column) {
                            $str = str_replace('_s', 'S', $column);
                            if ($rel = $data->$str) {
                                /** @var \common\models\State $rel */
                                return $rel->state_code;
                            }
                            return '';
                        },
                    ];
                }
            }
        }
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => LinkPager::class,
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
        ],
        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
        'headerRowOptions' => ['class' => 'x'],
        'columns' => $columns
    ]); ?>

</div>