<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Tabs;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;
use yii\helpers\Html;
use dosamigos\chartjs\ChartJs;

/**
 * @var array $data
 * @var array $listData
 */

$this->registerCssFile('https://maps-sdk.trimblemaps.com/v3/trimblemaps-3.3.0.css');
$this->registerJsFile('https://maps-sdk.trimblemaps.com/v3/trimblemaps-3.3.0.js', ['position' => View::POS_HEAD]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js');

$trucks = [];

foreach ($data as $item) {
    $trucks[] = [
        'id' => $item['id'],
        'name' => $item['name'],
        'latitude' => isset($item['gps'][0]) ? end($item['gps'])['latitude'] : '',
        'longitude' => isset($item['gps'][0]) ? end($item['gps'])['longitude'] : '',
        'locationName' => isset($item['gps'][0]) ? end($item['gps'])['reverseGeo']['formattedLocation'] : '',
        'speed' => isset($item['gps'][0]['speedMilesPerHour']) ? end($item['gps'])['speedMilesPerHour'] : '',
    ];
}
?>

<style>
    .marker-label {
        cursor: pointer;
    }
    .marker-image {
        background-image: url('https://maps.alk.com/api/1.2/img/truck_green.png');
        background-repeat: no-repeat;
        width: 26px;
        height: 26px;
        cursor: pointer;
    }
    .nav-tabs .nav-link {
        margin: 0 0.2em;
    }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Samsara Assets Locations</h1>
</div>

<div class="row_ mt-4 mb-4">

<div class="row">
    <div class="col-lg">
        <div class="location-map">
            <div id="trucksMap" style="height: 500px;"></div>
            <script>
                TrimbleMaps.APIKey = "<?= Yii::$app->pcmiler->apiKey ?>";
                var trucksMap = new TrimbleMaps.Map({
                    container: "trucksMap",
                    center: new TrimbleMaps.LngLat(-96, 38),
                    zoom: 3.5
                });
                <?php foreach ($trucks as $key=>$truck) : ?>
                    <?php $dataName = $truck['name']; ?>
                    var el = document.createElement('div');
                    el.id = 'container<?=$key?>';
                    el.title = "<?=$truck['name']?> - <?=$truck['locationName']?>";
                    var divData = document.createElement('div');
                    divData.className = 'marker-data';
                    divData.innerHTML = "<div data-url='<?= Url::toRoute(['truck-info', 'id' => $model->id,'name'=>$dataName,'startTime'=>$currentValues[0],'endTime'=>$currentValues[1]]);?>' class='marker-info''" +
                        "data-id='<?=$truck['id']?>'></div>";
                    el.appendChild(divData);
                    var divLabel = document.createElement('div');
                    divLabel.innerHTML = "<b style='background-color:white'><?=$truck['name']?></b><?php if ($truck['speed']):?> <span style='background-color:blue; color:white'><?=intval($truck['speed'])?> mph</span><?php endif;?>";
                    divLabel.className = 'marker-label';
                    el.appendChild(divLabel);
                    var divIcon = document.createElement('div');
                    divIcon.className = 'marker-image';
                    el.appendChild(divIcon);
                    <?php if($truck['longitude'] == '' || $truck['latitude'] == ''): ?>
                    <?php else: ?>
                    var marker = new TrimbleMaps.Marker({element: el})
                        .setLngLat([<?=$truck['longitude']?>, <?=$truck['latitude']?>]).addTo(trucksMap);
                    <?php endif; ?>
                    el.addEventListener('click',getMarkerData);
                <?php endforeach; ?>
                var nav = new TrimbleMaps.NavigationControl({showCompass: false});
                trucksMap.addControl(nav, 'top-left');

                function getMarkerData(e) {
                    var dataTagId = $(this).attr('id');
                    var dataUrl = $("#"+dataTagId).children().find('div.marker-info').data('url');
                    if($(".spinnerload").hasClass('d-none')) {
                        $(".spinnerload").removeClass('d-none');
                    } else {
                        $(".spinnerload").fadeIn('300');
                    }
                    $("#detailInfo").css('opacity','0.7');
                    $.ajax({
                        type     :'POST',
                        cache    : false,
                        url  : dataUrl,
                        success  : function(response) {
                            if(response == 0) {
                                alert('Error occurred during loading data');
                            } else {
                                $("#detailInfo").empty().append(response);
                            }
                            $(".spinnerload").fadeOut(300);
                            $("#detailInfo").css('opacity','1');
                        }
                    });
                    return false;
                }
                function resetCache(num) {
                    var _this = $("#clearCacheBtn"+num);
                    _this.css('opacity','0.7');
                    if (confirm('Are you sure?')) {
                        $.ajax({
                            type: 'POST',
                            cache: false,
                            url: _this.data('url'),
                            success: function (response) {
                                if (response) {
                                    alert('Cache cleared');
                                } else {
                                    alert('Error');
                                }
                                _this.css('opacity', '1');
                            }
                        });
                    }
                    return false;
                }
            </script>
        </div>
        <small>* Click on an object to see detailed information</small>
    </div>
</div>

    <?php
    $activeForm = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => Yii::$app->params['activeForm']['fieldConfig'],
    ]);
    ?>
    <div class="card mb-2 mt-2">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <?= $activeForm->field($form,'startTime')->dropdownList($startTimeData) ?>
                    <?= $activeForm->field($form,'endTime')->hiddenInput(['value'=>$endTimeData])->label(false); ?>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label style="width: 100%; display: block">&nbsp;</label>
                        <?= Html::submitButton('Get data', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

<div class="w-100 text-center spinnerload d-none mt-4 mb-0">
    <div class="spinner-border text-primary text-center" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div id="detailInfo" class="mt-4 mb-4">
    <p><?php echo $cacheStatus; ?></p>
</div>

<button type="button" class="btn btn-primary mt-2 mb-2" data-url="<?= Url::toRoute(['clear-cache', 'id' => $model->id,'startCache'=>$currentValues[0],'endCache'=>$currentValues[1]]);?>" id="clearCacheBtn1" onclick="resetCache(1); return false;">Reset current cache</button>
<button type="button" class="btn btn-primary mt-2 mb-2" data-url="<?= Url::toRoute(['clear-cache', 'id' => $model->id]);?>" id="clearCacheBtn2" onclick="resetCache(2); return false;">Reset all cache</button>
