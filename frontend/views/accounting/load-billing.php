<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use common\models\Load;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Load Billing');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $this->title ?></h1>
    </div>
<?php
$gridId = 'billing-loads';
echo Grid::widget([
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'columns' => [
        'id', // 0
        'booked_by|rel=bookedBy.username', // 1
        'bill_to|rel=billTo.name', // 2
        'customer_reference', // 3
        'customer_reference|title=Shipper|rel=loadStops[0].getCompanyName()', // 4
        'bill_miles|int|title=Miles', // 5
        'office_id|rel=office._label|filterable', // 6
        'type_id|rel=type._label', // 7
        'notes', // 8
        'hold_billing|yn|title=Hold|filterable', // 9
        'status|filterable', // 10
        'id|title=Delivered by|rel=dispatchAssignment.driver.get_label()', // 11
        // 12
        new DataColumn([
            'title' => Yii::t('app', 'Del/Arrived'),
            'value' => function ($model) {
                /** @var Load $model */
                $stops = $model->getLoadStopsOrdered();
                $lastStop = $stops[count($stops) - 1];
                return Yii::$app->formatter->asDate($model->arrived_date ? $model->arrived_date : $lastStop->available_from, "php:Y/m/d");
            }
        ]),
        'id|visible=false|rel=loadStops[0].company.time_zone',
    ],
    'rowAttributes' => 'function(data) {if (data[9] == "Y") return "load-gray"}',
    'buttons' => [
        [
            'text' => "<span><i class='fa fa-save'></i></span>",
            'action' => "js:function(){                
                /* https://datatables.net/reference/type/selector-modifier */
                var visibleRows = $('#$gridId').DataTable().rows({search:'applied'}).data(); 
                var visibleIds = [];
                for (var i=0, l=visibleRows.length; i<l; i++) { 
                    visibleIds.push(visibleRows[i][0]); 
                }                               
                var selectedData = $('#$gridId').DataTable().rows('.selected').data(); 
                var selectedIds = [];    
                for (var i=0, l=selectedData.length; i<l; i++) {
                    /* check if row is not filtered out */
                    var visible = false;
                    for (var j=0, v=visibleRows.length; j<v; j++) {                        
                        if (selectedData[i][0] == visibleRows[j][0]) { 
                            visible = true; 
                        }
                    }                    
                    if (visible) { 
                        selectedIds.push(selectedData[i][0]); 
                    }                  
                }                    
                if (selectedIds.length) {
                    window.location.href = '?export=' + selectedIds.join(',');
                }
            }"
        ],
        [
            'text' => "<span><i class='fas fa-star'></i></span>",
            'action' => 'js:function(){this.rows().select();}'
        ],
        [
            'text' => "<span><i class='far fa-star'></i></span>",
            'action' => 'js:function(){this.rows().deselect();}'
        ],
    ],
    'rightToolbarHtml' => '
        <div class="dt-toolbar-actions js-daterange d-flex" data-column-index="12">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-filter"></i></button>
                <div class="dropdown-menu">
                    <a class="dropdown-item js-daterange-this-month" href="#">This Month</a>
                    <a class="dropdown-item js-daterange-last-month" href="#">Last Month</a>
                    <a class="dropdown-item js-daterange-this-quarter" href="#">This Quarter</a>
                    <a class="dropdown-item js-daterange-last-quarter" href="#">Last Quarter</a>
                    <a class="dropdown-item js-daterange-this-year" href="#">This Year</a>
                    <a class="dropdown-item js-daterange-last-year" href="#">Last Year</a>
                    <a class="dropdown-item js-daterange-today" href="#">Today</a>
                </div>
            </div>
            <input class="form-control js-daterange-min" type="text" name="min" placeholder="from date">
            <input class="form-control js-daterange-max" type="text" name="max" placeholder="to date">
        </div>
    ',
    'attributes' => [
        'data-booking' => Url::toRoute(['load/edit', 'id' => '{id}']),
        'data-clearing' => Url::toRoute(['clear-load', 'id' => '{id}']),
        'data-summary' => Url::toRoute(['dispatch-summary', 'load' => '{id}']),
        'data-hold' => Url::toRoute(['accounting/hold-billing', 'id' => '{id}']),
    ],
]);

$js = <<<JSCODE
$('#$gridId tbody').on( 'click', 'tr', function () {
    $(this).toggleClass('selected');      
    var selectedData = $('#$gridId').DataTable().rows('.selected').data();
    var selectedIds = [];    
    for (var i=0, l=selectedData.length; i<l; i++) {
        selectedIds.push(selectedData[i][0]);
    }   
});
JSCODE;
$this->registerJs($js);