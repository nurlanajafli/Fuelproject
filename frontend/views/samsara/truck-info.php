<?php
use yii\bootstrap4\Tabs;
use dosamigos\chartjs\ChartJs;

/**
 * @var array $data
 */
if($data) { ?>
    <?php
    $hourMinute = function($fullTime) {
        $timeTemp = substr($fullTime,11,5);
        return $timeTemp;
    };
    ?>
    <style>
        #detailInfo canvas {
            max-height: 520px;
        }
    </style>

    <?php $this->beginBlock('fuel_temp'); ?>
    <div class="row">
        <div class="col-lg">
            <div class="col-lg">
                <?php if($data['fuelPercents'] && isset($data['fuelPercents'])) { ?>
                    <?php
                    $fuelLabels = [];
                    $fuelPercents = [];
                    ?>
                    <?php foreach($data['fuelPercents'] as $k=>$v) {
                        array_push($fuelLabels,$hourMinute($v['time']));
                        array_push($fuelPercents,$v['value']);
                    }
                    ?>
                    <h2 class="text-center h3 mb-0 text-gray-800">Truck No:<?=$data['name']?> Fuel Percents</h2>
                    <?= ChartJs::widget([
                        'type' => 'bar',
                        'options' => [
                            'responsive' => true,
                        ],
                        'data' => [
                            'labels' => $fuelLabels,
                            'datasets' => [
                                [   'label' => 'Fuel Percents',
                                    'backgroundColor' => "#9FE2BF",
                                    'borderColor' => "#9FE2BF",
                                    'pointBackgroundColor' => "rgba(179,181,198,1)",
                                    'pointBorderColor' => "#fff",
                                    'pointHoverBackgroundColor' => "#fff",
                                    'pointHoverBorderColor' => "rgba(179,181,198,1)",
                                    'data' => $fuelPercents
                                ]
                            ]
                        ]
                    ]);
                    ?>
                <?php } else { ?>
                    <p class="alert alert-info"><?=Yii::t('app', 'No data')?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('fuel'); ?>
    <div class="row">
        <div class="col-lg">
            <div class="col-lg">
                <?php if($data['fuelPercents'] && isset($data['fuelPercents'])) { ?>
                <?php
                $fuelLabels = [];
                $fuelPercents = [];
                ?>
                <?php foreach($data['fuelPercents'] as $k=>$v) {
                    array_push($fuelLabels,$hourMinute($v['time']));
                    array_push($fuelPercents,$v['value']);
                }
                ?>
                <h2 class="text-center h3 mb-0 text-gray-800">Truck No:<?=$data['name']?> Fuel Percents</h2>
                <?= ChartJs::widget([
                    'type' => 'bar',
                    'options' => [
                        'responsive' => true,
                    ],
                    'data' => [
                        'labels' => $fuelLabels,
                        'datasets' => [
                            [   'label' => 'Fuel Percents',
                                'backgroundColor' => "#9FE2BF",
                                'borderColor' => "#9FE2BF",
                                'pointBackgroundColor' => "rgba(179,181,198,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                                'data' => $fuelPercents
                            ]
                        ]
                    ]
                ]);
                ?>
                <?php } else { ?>
                    <p class="alert alert-info"><?=Yii::t('app', 'No data')?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('speed'); ?>
    <div class="row">
        <div class="col-lg">
            <?php if($data['ecuSpeedMph'] && isset($data['ecuSpeedMph'])) { ?>
            <?php
            $labels = [];
            $mphValues = [];
            $metrValues = [];
            ?>
            <?php foreach($data['ecuSpeedMph'] as $k=>$v) {
                array_push($labels,$hourMinute($v['time']));
                array_push($mphValues,number_format($v['value'],2,'.',''));
                array_push($metrValues,number_format(($v['value']*1.609344),2,'.',''));
            }
            ?>
            <h2 class="text-center h3 mb-0 text-gray-800">Truck No:<?=$data['name']?> Speed Graph</h2>
            <?= ChartJs::widget([
                'type' => 'bar',
                'options' => [
                    'responsive' => true,
                    'maintainAspectRatio'=> false,
                ],
                'data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [   'label' => 'Speed Mph',
                            'backgroundColor' => "#4BC0C0",
                            'borderColor' => "#4BC0C0",
                            'pointBackgroundColor' => "rgba(179,181,198,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(179,181,198,1)",
                            'data' => $mphValues
                        ],
                        [
                            'label' => "Speed Kmh",
                            'backgroundColor' => "#FFCE5D",
                            'borderColor' => "#FFCE5D",
                            'pointBackgroundColor' => "rgba(255,99,132,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(255,99,132,1)",
                            'data' => $metrValues
                        ]
                    ]
                ]
            ]);
            ?>
            <?php } else { ?>
                <p class="alert alert-info"><?=Yii::t('app', 'No data')?></p>
            <?php } ?>
        </div>
    </div>
    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('volts'); ?>
    <div class="row">
        <div class="col-lg">
            <div class="col-lg">
                <?php if($data['batteryMilliVolts'] && isset($data['batteryMilliVolts'])) { ?>
                    <?php
                    $labels = [];
                    $voltValues = [];
                    ?>
                    <?php foreach($data['batteryMilliVolts'] as $k=>$v) {
                        array_push($labels,$hourMinute($v['time']));
                        array_push($voltValues,number_format(($v['value']*0.001),2,'.',''));
                    }
                    ?>
                    <h2 class="text-center h3 mb-0 text-gray-800">Truck No:<?=$data['name']?> Battery Volts</h2>
                    <?= ChartJs::widget([
                        'type' => 'bar',
                        'options' => [
                            'responsive' => true,
                            'maintainAspectRatio'=> false,
                            'plugins' => [
                                'title' => [
                                    'display' => true,
                                    'text' => 'Test text'
                                ]
                            ]
                        ],
                        'data' => [
                            'labels' => $labels,
                            'datasets' => [
                                [   'label' => 'Battery Volts',
                                    'backgroundColor' => "#FF7F50",
                                    'borderColor' => "#FF7F50",
                                    'pointBackgroundColor' => "rgba(179,181,198,1)",
                                    'pointBorderColor' => "#fff",
                                    'pointHoverBackgroundColor' => "#fff",
                                    'pointHoverBorderColor' => "rgba(179,181,198,1)",
                                    'data' => $voltValues
                                ]
                            ]
                        ]
                    ]);
                    ?>
                <?php } else { ?>
                    <p class="alert alert-info"><?=Yii::t('app', 'No data')?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('engineload'); ?>
    <div class="row">
        <div class="col-lg">
            <div class="col-lg">
                <?php if($data['engineLoadPercent'] && isset($data['engineLoadPercent'])) { ?>
                <?php
                $labels = [];
                $precent = [];
                ?>
                <?php foreach($data['engineLoadPercent'] as $k=>$v) {
                    array_push($labels,$hourMinute($v['time']));
                    array_push($precent,number_format($v['value'],2,'.',''));
                }
                ?>
                <h2 class="text-center h3 mb-0 text-gray-800">Truck No:<?=$data['name']?> Engine Load Precent</h2>
                <?= ChartJs::widget([
                    'type' => 'bar',
                    'options' => [
                        'responsive' => true,
                        'maintainAspectRatio'=> false,
                        'plugins' => [
                            'title' => [
                                'display' => true,
                                'text' => 'Test text'
                            ]
                        ]
                    ],
                    'data' => [
                        'labels' => $labels,
                        'datasets' => [
                            [   'label' => 'Engine Load Precent',
                                'backgroundColor' => "#CCCCFF",
                                'borderColor' => "#CCCCFF",
                                'pointBackgroundColor' => "rgba(179,181,198,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                                'data' => $precent
                            ]
                        ]
                    ]
                ]);
                ?>
                <?php } else { ?>
                    <p class="alert alert-info"><?=Yii::t('app', 'No data')?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('tableinfo'); ?>
    <?php $endData = isset($data['gps']) ? end($data['gps']) : 0; ?>
    <?php $endDistanceMetr =  isset($data['gpsDistanceMeters'][0]) ? end($data['gpsDistanceMeters'])['value'] : 0; ?>
    <?php $endOdometrsMetr =  isset($data['gpsOdometerMeters'][0]) ? end($data['gpsOdometerMeters'])['value'] : 0; ?>


    <div class="row">
        <div class="col-lg">
            <div class="truck-info">
                <h2 class="h3 mb-0 text-gray-800">Details:</h2>
                <div class="row">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                        <tr class="table-primary">
                            <th scope="col">Truck name</th>
                            <th scope="col">Vehicle ID</th>
                            <th scope="col">Timestamp</th>
                            <th scope="col">GPS Ping</th>
                            <th scope="col">GPS Distance/<br>Odometr (kM)</th>
                            <th scope="col">Heading Degrees</th>
                            <th scope="col">Fuel Level</th>
                            <th scope="col">Location</th>
                            <th scope="col">Speed (mpH)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row"><span class="name"><?=$data['name']?></span></th>
                            <td><span class="id"><?=$data['id']?></span></td>
                            <td><span class="time"><?=$endData['time']?></span></td>
                            <td>[<span class="latitude"><?=$endData['latitude']?></span>, <span class="longitude"><?=$endData['longitude']?></span>]</td>
                            <td><span class="engineseconds"><?= number_format($endDistanceMetr/1000,2,'.',''); ?> / <br>
                                <?= number_format($endOdometrsMetr/1000,2,'.',''); ?>
                                </span></td>
                            <td><span class="engineseconds"><?=$endData['headingDegrees'] ?? 0?></span></td>
                            <td><span class="fuel"><?=isset($fuelPercents) ? end($fuelPercents) : 0;?></span>%</td>
                            <td><span class="location"><?=$endData['reverseGeo']['formattedLocation']?></span></td>
                            <td><span class="speed"><?=isset($mphValues) ? end($mphValues) : 0; ?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('route'); ?>
    <div class="row">
        <div class="col-lg">
            <?php $endGps = end($data['gps']); $showGps = false; ?>
            <?php if(isset($data['gps']) && is_array($data['gps']) && sizeof($data['gps']) > 10) { $showGps = true; } ?>
            <?php if($endGps['longitude'] != $data['gps'][0]['longitude'] && $showGps) { ?>
                <h2 class="text-center h3 mb-0 text-gray-800">Truck No:<?=$data['name']?> Route</h2><br>
            <?php $better_token = md5(uniqid(rand(), true)); ?>
            <div id="routeMapline<?=$better_token?>" style="height: 500px;"></div>
            <script>
                TrimbleMaps.APIKey = "<?= Yii::$app->pcmiler->apiKey ?>";
                const routeMapline<?=$better_token?> = new TrimbleMaps.Map({
                    container: "routeMapline<?=$better_token?>",
                    center: new TrimbleMaps.LngLat(<?=$data['gps'][0]['longitude']?>, <?=$data['gps'][0]['latitude']?>),
                    zoom: 6
                });
                const myRouteLine<?=$better_token?> = new TrimbleMaps.Route({
                    routeId: "myRouteLine<?=$better_token?>",
                    stops: [
                    <?php $kx = 0; foreach($data['gps'] as $k=>$v) {
                        if($k == $kx) { ?>
                        new TrimbleMaps.LngLat(<?=$v['longitude']?>, <?=$v['latitude']?>),
                    <?php $kx=$kx+4; } } ?>
                        new TrimbleMaps.LngLat(<?=$endGps['longitude']?>, <?=$endGps['latitude']?>),
                ],
                 showStops: false,
                 showArrows: false,
            });
            routeMapline<?=$better_token?>.on('load', function() {
                    myRouteLine<?=$better_token?>.addTo(routeMapline<?=$better_token?>);
                });
            </script>
            <?php } else { $showGps = false; ?>
                <p class="alert alert-info"><?=Yii::t('app', 'This truck has not been driving during this time.')?></p>
            <?php } ?>
        </div>
    </div>
    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('deflevel'); ?>
    <div class="row">
        <div class="col-lg">
            <div class="col-lg">
                <?php if($data['defLevelMilliPercent'] && isset($data['defLevelMilliPercent'])) { ?>
                    <?php
                    $labels = [];
                    $precentDef = [];
                    ?>
                    <?php foreach($data['defLevelMilliPercent'] as $k=>$v) {
                        array_push($labels,$hourMinute($v['time']));
                        array_push($precentDef,number_format($v['value']/1000,2,'.',''));
                    }
                    ?>
                    <h2 class="text-center h3 mb-0 text-gray-800">Truck No:<?=$data['name']?> DEF Precent Level</h2>
                    <?= ChartJs::widget([
                        'type' => 'bar',
                        'options' => [
                            'responsive' => true,
                            'maintainAspectRatio'=> false,
                            'plugins' => [
                                'title' => [
                                    'display' => true,
                                    'text' => 'Test text'
                                ]
                            ]
                        ],
                        'data' => [
                            'labels' => $labels,
                            'datasets' => [
                                [   'label' => 'DEF Precent Level',
                                    'backgroundColor' => "#87CEEB",
                                    'borderColor' => "#87CEEB",
                                    'pointBackgroundColor' => "rgba(179,181,198,1)",
                                    'pointBorderColor' => "#fff",
                                    'pointHoverBackgroundColor' => "#fff",
                                    'pointHoverBorderColor' => "rgba(179,181,198,1)",
                                    'data' => $precentDef
                                ]
                            ]
                        ]
                    ]);
                    ?>
                <?php } else { ?>
                    <p class="alert alert-info"><?=Yii::t('app', 'No data')?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('airtemp'); ?>
    <div class="row">
        <div class="col-lg">
            <div class="col-lg">
                <?php if($data['ambientAirTemperatureMilliC'] && isset($data['ambientAirTemperatureMilliC'])) { ?>
                    <?php
                    $labelsAir = [];
                    $airTemp = [];
                    ?>
                    <?php foreach($data['ambientAirTemperatureMilliC'] as $k=>$v) {
                        array_push($labelsAir,$hourMinute($v['time']));
                        array_push($airTemp,number_format($v['value']/1000,2,'.',''));
                    }
                    ?>
                    <h2 class="text-center h3 mb-0 text-gray-800">Truck No:<?=$data['name']?> Air Temperature (C)</h2>
                    <?= ChartJs::widget([
                        'type' => 'bar',
                        'options' => [
                            'responsive' => true,
                            'maintainAspectRatio'=> false,
                            'plugins' => [
                                'title' => [
                                    'display' => true,
                                    'text' => 'Test text'
                                ]
                            ]
                        ],
                        'data' => [
                            'labels' => $labelsAir,
                            'datasets' => [
                                [   'label' => 'Air temperature (c)',
                                    'backgroundColor' => "#008080",
                                    'borderColor' => "#008080",
                                    'pointBackgroundColor' => "rgba(179,181,198,1)",
                                    'pointBorderColor' => "#fff",
                                    'pointHoverBackgroundColor' => "#fff",
                                    'pointHoverBorderColor' => "rgba(179,181,198,1)",
                                    'data' => $airTemp
                                ]
                            ]
                        ]
                    ]);
                    ?>
                <?php } else { ?>
                    <p class="alert alert-info"><?=Yii::t('app', 'No data')?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>

    <div class="row">
        <div class="col-lg edit-form">
            <?php
            $defaultActive = $showGps ? 'route' : 'speed';
            $activeTab = Yii::$app->request->get('tab', $defaultActive); ?>
            <?php echo Tabs::widget([
                'encodeLabels' => false,
                'items' => [
                    [
                        'label' => Yii::t('app', 'Fuel'),
                        'content' => $this->blocks['fuel'],
                        'active' => ($activeTab == 'fuel'),
                    ],
                    [
                        'label' => Yii::t('app', 'Speed Mph'),
                        'content' => $this->blocks['speed'],
                        'active' => ($activeTab == 'speed'),
                    ],
                    [
                        'label' => Yii::t('app', 'Battery Volts'),
                        'content' => $this->blocks['volts'],
                        'active' => ($activeTab == 'volts'),
                    ],
                    [
                        'label' => Yii::t('app', 'Engine Load Precent'),
                        'content' => $this->blocks['engineload'],
                        'active' => ($activeTab == 'engineload'),
                    ],
                    [
                        'label' => Yii::t('app', 'Details'),
                        'content' => $this->blocks['tableinfo'],
                        'active' => ($activeTab == 'tableinfo'),
                    ],
                    [
                        'label' => Yii::t('app', 'Routing'),
                        'content' => $this->blocks['route'],
                        'active' => ($activeTab == 'route'),
                    ],
                    [
                        'label' => Yii::t('app', 'DEF percent level'),
                        'content' => $this->blocks['deflevel'],
                        'active' => ($activeTab == 'deflevel'),
                    ],
                    [
                        'label' => Yii::t('app', 'Air Temperature (C)'),
                        'content' => $this->blocks['airtemp'],
                        'active' => ($activeTab == 'airtemp'),
                    ],
                ]]); ?>


        </div>
    </div>
    <p><br><br><?php echo $cacheStatus; ?></p>
<?php } else { ?>
0
<?php  }?>
