<?php

use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app', 'Load Rating Matrices');
$this->params['breadcrumbs'][] = $this->title;


if (isset($actionColumnTemplates)) {
    $actionColumnTemplate = implode(' ', $actionColumnTemplates);
    $actionColumnTemplateString = $actionColumnTemplate;
} else {
    Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New', ['create'], ['class' => 'btn btn-success']);
    $actionColumnTemplateString = "{update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>
<div class="giiant-crud trailer-type-index">
    <?php Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>
    <h1>
        <?= Yii::t('app', 'Load Rating Matrices') ?>
        <small>List</small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <hr />

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => LinkPager::class,
                'firstPageLabel' => 'First',
                'lastPageLabel' => 'Last',
            ],
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'headerRowOptions' => ['class'=>'x'],
            'columns' => [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => $actionColumnTemplateString,
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            $options = [
                                'title' => Yii::t('app', 'View'),
                                'aria-label' => Yii::t('app', 'View'),
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                        }
                    ],
                    'urlCreator' => function($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap'=>'nowrap']
                ],
                'number',
                'name',
                'rate_method',
                'rate_type',
                'inactive:boolean'
            ]
        ]); ?>
    </div>

</div>
<?php Pjax::end() ?>

<pre class="collapse">
    <h3>Matrix Info</h3>
    <li>Matrix No (Int, Pk)
    <li>Matrix Name (Str)
    <li>Rate Method (Enum)
    <li>Rate Type (Enum)
    <li>Inactive (bool)

    <hr>
        <b>Rate Methods</b> - Supported Rate Types
    <i>
    <b>ZipZip</b>      - Flat, Miles, Piece, Space, Pound, Cwt, Ton, Lot, Multi
    <b>ZoneZone</b>    - Flat, Miles, Piece, Space, Pound, Cwt, Ton, Lot,        Step
    <b>Geograph</b>    - Flat, Miles, Piece, Space, Pound, Cwt, Ton, Lot, Multi
    <b>Distance</b>    - Flat, Miles,                           Ton
    </i>

    <b>[Zi]</b>  ZipZip   - Zip 1 Start | Zip 1 End | Zip 2 Start | Zip 2 End | ... | Description
    <b>[Zo]</b>  ZoneZone - Zone 1 | Zone 2 | ... | Description
    <b>[Ge]</b>  Geograph - Origin City | O St | Dest City | D St | ... | Description
    <b>[Di]</b>  Distance -  ... | Description

    Cwt      [Zo,Zi] - Low Wgt (int) | High Wgt (int) | Rate (.4)
    Cwt         [Ge] - Rate (.4), Min (.2)

    Flat  [Zo,Zi,Ge] - Rate (.2) | Bill Miles (int)
    Flat        [Di] - Low Miles (int) | High Miles (int) | Rate (.4)

    Miles       [Di] - Low Miles (int) | High Miles (int) | Rate (.4) | Loaded & Empty checkbox
    Miles [Zo,Zi,Ge] - Rate (.4) | Base Rate (.4) | Base Miles (int) | Min (.2) | Bill Miles (int) | Trl Type (Fk - Trailer Type)

    Lot   [Zo,Zi,Ge] - Rate (.4) | Pieces (.4)
    Step        [Zo] - Max Pcs (.4) | Max Space (int) | Max Wgt (int) |  Rate (.4)

    Pound    [Zo,Zi] - Low Wgt (int) | High Wgt (int) | Rate (.4)
    Pound       [Ge] - Rate (.4) | Min (.2)

    Piece [Zo,Zi,Ge] - Rate (.4)
    Space [Zo,Zi,Ge] - Rate (.4)

    Ton   [Zo,Zi,Ge] - Rate (.4)
    Ton         [Di] - Low Miles (int) | High Miles (int) | Rate (.4)

    Multi    [Zi,Ge] - Rate (.4)
    <hr>
    ===== ZipZip (Cwt, Flat, Miles, Piece, Space, Ton, Lot) =====
    [Flat] Zip 1 Start | Zip 1 End | Zip 2 Start | Zip 2 End | Rate | Bill Miles | Description
               str     |    str    |    str      |    str    | 0.00 |          0 |  str

    ===== ZoneZone (Cwt, Flat, Miles, Piece, Space, Ton, Lot, Step) =====
    [Cwt]  Zone 1 | Zone 2 | Low Wgt | High Wgt |   Rate   | Description
             NE   |  NC    |       0 |        0 | 125.0000 | Min Charge
                  |        |       1 |     1000 |  15.0000 |

    [Step] Zone 1 | Zone 2 | Max Pcs | Max Space | Max Wgt |  Rate  | Description
             NE   |  NC    | 0.0000  |         0 |       0 | 0.0000 |  str

    ===== Geograph (Cwt, Flat, Miles, Piece, Space, Ton, Lot) =====
    [Cwt]   Origin City | O St | Dest City | D St |  Rate  |  Min  | Description
                 str    |  CO  |   str     |  AL  | 2.5500 | 75.00 |  str

    ===== Distance (Flat, Miles, Ton) =====
    [Miles] Low Miles | High Miles |  Rate  | Description
                    0 |          0 | 0.0000 |  str
    <hr>
    <u>Cwt</u> - hundredweight, 100 торговых фунтов = 45,359237 килограмма
     - to set Minimum Rate => set `Low Wgt` = `High Wgt` = 0
     - to set Maximum Rate => set `High Wgt` = 99999

    <u>Flat</u> - `Bill Miles` override PCMiler's calculations

    <u>Miles</u> - If `Base Rate` is entered, Base Rate + (per-mile rate * load miles)
          - If `Base Miles` are entered, (per-mile rate * load miles in excess of Base Miles)
          - If `Base Miles` and `Base Rate`, Base Rate + (per-mile rate * load miles in excess of Base Miles)
          - If a `Minimum Rate` is entered, if needed, the load will be rated as e flat rate minimum amount
          - If `Bill Miles` are entered, override PCMiler's calculations
          - If `Trailer Type` is selected, the rate will only apply to loads with the selected type
</pre>