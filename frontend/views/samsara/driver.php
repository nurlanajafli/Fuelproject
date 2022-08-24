<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->registerCssFile('https://maps-sdk.trimblemaps.com/v3/trimblemaps-3.3.0.css');
$this->registerJsFile('https://maps-sdk.trimblemaps.com/v3/trimblemaps-3.3.0.js', ['position' => View::POS_HEAD]);
?>
<?php $interval = function ($startTime,$endTime) {
    $sFormat = date('Y-m-d H:i:s', strtotime($startTime));
    $eFormat = date('Y-m-d H:i:s', strtotime($endTime));
    $start = new \DateTime($sFormat);
    $end = new \DateTime($eFormat);
    $intervalTime = $start->diff($end);
    $intervalTime = $intervalTime->format("%H:%i:%s");
    return $intervalTime;
};
$toSeconds = function($time) {
    $time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $time);
    sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);
    $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
    return $time_seconds;
};
$getSecondPrecent = function($currentHour,$chBegin,$chEnd,$hosBegin,$hosEnd,$fullSec) {
    $partPrecent = number_format($fullSec/864,2,'.',' ');
    return $partPrecent;
};
$statusText = function($status,$full = false) {
    switch ($status) {
        case 'sleeperBed':
            return $full ? "Sleeper Bed" : "SB";
            break;
        case 'driving':
            return $full ? "Driving" : "D";
            break;
        case 'personalConveyance':
            return $full ? "Personal Conveyance" : "PC";
            break;
        case 'offDuty':
            return $full ? "Off Duty" : "OFF";
            break;
        case 'onDuty':
            return $full ? "On Duty" : "ON";
            break;
        default:
            return $status;
    }
};
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Samsara Driver Log</h1>
</div>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <p><?= Html::a('< Back', ['samsara/drivers'], ['class' => 'primary-link']) ?></p>
                <h2 class="h2 mb-0 text-gray-800 mb-2"><?=$driverInfoLog['driver']['name']?></h2>
                <p><i class="fa fa-list"></i> <?= Html::a('View driver record', ['samsara/driver-log','id'=>$driverInfoLog['driver']['id']], ['class' => 'primary-link']) ?></p>
                <table class="table">
                    <tbody>
                        <tr>
                            <td colspan="2">Hours of services</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><strong>Log</strong>
                                    <span class="text-right" style="float: right;">
                                        <i class="fa fa-list"></i> <?= Html::a('View log details', ['samsara/driver-log','id'=>$driverInfoLog['driver']['id']], ['class' => 'primary-link']) ?>
                                    </span>
                                </p>
                                <?php
                                $hosStatus = [];
                                $hosStatuses = [];
                                $allTimeStatus = [];
                                $seconds = 0;
                                $lastLog = end($driverInfoLog['hosLogs']);
                                foreach($driverInfoLog['hosLogs'] as $k=>$v): ?>
                                <?php
                                $hosSt = $v['hosStatusType'];
                                if(!in_array($v['hosStatusType'],$hosStatus)) {
                                    $hosStatuses[$k]['status'] = $hosSt;
                                    $hosStatuses[$k]['status_time'] = 0;
                                    array_push($hosStatus,$hosSt);
                                }
                                 ?>
                                        <?php $intervalTime = $interval($v['logStartTime'],$v['logEndTime']); ?>
                                        <?php $allTimeStatus[$k]['status'] = $hosSt;
                                        $allTimeStatus[$k]['beginSecond'] = $seconds;?>
                                            <?php $seconds = $seconds + $toSeconds($intervalTime); ?>
                                       <?php $allTimeStatus[$k]['endSecond'] = $seconds;
                                       $allTimeStatus[$k]['fullSecond'] = $allTimeStatus[$k]['endSecond'] - $allTimeStatus[$k]['beginSecond'];
                                       $allTimeStatus[$k]['hoursBegin'] = gmdate("H",$allTimeStatus[$k]['beginSecond']);
                                       $allTimeStatus[$k]['hoursEnd'] = gmdate("H",$allTimeStatus[$k]['endSecond']);
                                       ?>

                                <?php endforeach; ?>
                                <?php
                                $timeSeconds = [];
                                for($i=3600; $i<=86400; $i= $i+3600) {
                                    array_push($timeSeconds,$i);
                                } ?>
                                <?php
                                foreach($allTimeStatus as $ak=>$av) {
                                    $fullPrecent = $av['fullSecond']/36;
                                    $hoursAll = $av['hoursEnd'] - $av['hoursBegin'];
                                    foreach($hosStatuses as $sk=>$sv) {
                                        if($sv['status'] == $av['status']) {
                                            $hosStatuses[$sk]['status_time'] = $hosStatuses[$sk]['status_time'] + $allTimeStatus[$ak]['fullSecond'];
                                        }
                                    }
                                }
                                ?>
                                <div class="row">
                                    <div class="col-2">
                                        <ul class="hoursNum hNum">
                                            <li>&nbsp;</li>
                                        </ul>
                                        <ul class="statusList">
                                            <?php $statusTime = 0;
                                            foreach ($hosStatuses as $status): ?>
                                                <li>
                                                    <strong title="<?=$status['status']?>" class="<?=$status['status']?>">
                                                    <?=$statusText($status['status'])?>
                                                    </strong> <small>(<?=gmdate('H:i:s',$status['status_time'])?>)</small>
                                                    <?php $statusTime = $statusTime + $status['status_time']; ?>
                                                </li>
                                            <?php endforeach ?>
                                        </ul>
                                        <p class="mt-2 text-center"><small><?=gmdate('H:i:s',$statusTime)?></small></p>
                                    </div>
                                    <div class="col-10">
                                        <ul class="hoursNum">
                                            <?php foreach ($timeSeconds as $kt=>$vt) { ?>
                                                <li class="p-0 text-center">
                                                    <small class="<?=$vt?>">
                                                        <?php if($kt == 0) {
                                                            echo "M";
                                                        } elseif($kt == 12) {
                                                            echo "N";
                                                        } else {
                                                            echo $kt;
                                                        } ?>
                                                    </small></li>
                                            <?php } ?>
                                        </ul>
                                        <?php  foreach($hosStatus as $status): ?>
                                            <div class="sts <?=$status?>">
                                                <?php
                                                $x=1;
                                                foreach($allTimeStatus as $ak=>$av) {
                                                    $precentCurrent = number_format($av['fullSecond']/864,2,'.','');
                                                    if($av['status'] == $status) {
                                                        ?>
                                                        <span data-step="<?=$x?>" title="<?=gmdate("H:i:s",$av['fullSecond'])?>" data-status="<?=$status?>" class="active" style="width:<?=$precentCurrent?>%"></span>
                                                    <?php } else { ?>
                                                        <span data-step="<?=$x?>" class="deactive" style="width:<?=$precentCurrent?>%"></span>
                                                    <?php } $x++;

                                                } ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Duty status</td>
                            <td class="text-right"><strong class="<?=$lastLog['hosStatusType']?>"><?=$statusText($lastLog['hosStatusType'],true)?></strong></td>
                        </tr>
                        <tr>
                            <td>Time in current status</td>
                            <td class="text-right">
                                <?=gmdate("H:i:s",end($allTimeStatus)['fullSecond']);?>
                            </td>
                        </tr>
                        <tr>
                            <td>Drive remaining</td>
                            <td class="text-right"><?=gmdate("H:i:s",$driverInfoClock[0]['clocks']['drive']['driveRemainingDurationMs']/1000) ?></td>
                        </tr>
                        <tr>
                            <td>Shift remaining</td>
                            <td class="text-right"><?=gmdate("H:i:s",$driverInfoClock[0]['clocks']['shift']['driveRemainingDurationMs']/1000) ?></td>
                        </tr>
                        <tr>
                            <td>Cycle remaining</td>
                            <td class="text-right"><?=gmdate("H:i:s",round($driverInfoClock[0]['clocks']['cycle']['cycleRemainingDurationMs']/1000)) ?></td>
                        </tr>
                        <tr>
                            <td>Cycle tomorrow</td>
                            <td class="text-right"><?=gmdate("H:i:s",$driverInfoClock[0]['clocks']['cycle']['cycleTomorrowDurationMs']/1000) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div id="trucksMap" style="height: 500px;"></div>
                <script>
                    TrimbleMaps.APIKey = "<?= Yii::$app->pcmiler->apiKey ?>";
                    var trucksMap = new TrimbleMaps.Map({
                        container: "trucksMap",
                        center: new TrimbleMaps.LngLat(<?=$lastLog['logRecordedLocation']['longitude']?>, <?=$lastLog['logRecordedLocation']['latitude']?>),
                        zoom: 10
                    });

                    // Create a marker with a label on top of an icon.
                    var el = document.createElement('div');
                    el.id = 'container';
                    el.title = "Truck";
                    var divData = document.createElement('div');
                    divData.className = 'marker-data';
                    divData.innerHTML = "<div class='marker-info'></div>";
                    el.appendChild(divData);
                    var divLabel = document.createElement('div');
                    divLabel.innerHTML = "";
                    divLabel.className = 'marker-label';
                    el.appendChild(divLabel);
                    var divIcon = document.createElement('div');
                    divIcon.className = 'marker-image';
                    el.appendChild(divIcon);

                    var marker = new TrimbleMaps.Marker({element: el})
                        .setLngLat([<?=$lastLog['logRecordedLocation']['longitude']?>, <?=$lastLog['logRecordedLocation']['latitude']?>]).addTo(trucksMap);

                </script>
            </div>
        </div>
    </div>
</div>

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
    ul.hoursNum {
        width: 100%;display: table;table-layout: fixed;border-collapse: collapse;margin: 0;
    }
    ul.hoursNum li {
        display: table-cell;text-align: left;
        vertical-align: middle;word-wrap: break-word;
        border-right:1px solid #B3B3B3;
    }
    ul.hoursNum li:first-child {
        border-left: 1px solid #B3B3B3;
    }
    ul.hoursNum.hNum li:first-child {
        border:0 none;
    }
    ul.statusList {
        list-style: none; margin: 0;padding: 0
    }
    ul.statusList li {
        list-style: none;margin:0; padding:4px 0;height: 30px;
    }
    ul.statusList li strong.offDuty, strong.offDuty {color:#E74C3C;}
    ul.statusList li strong.sleeperBed, strong.sleeperBed {color:#000;}
    ul.statusList li strong.driving, strong.driving {color:#1E8449;}
    ul.statusList li strong.onDuty, strong.onDuty {color:#E67E22;}
    ul.statusList li strong.personalConveyance, strong.personalConveyance {color:#3498DB;}
    .sts {
        height:30px;
    }
    .sts .active {
        background: #3498DB; margin:12px 0 0; height:6px; float:left;
    }
    .sts .deactive {
        background: transparent;margin:12px 0 0; height:6px; float:left;
    }
    .sts.offDuty {background: rgba(231,76,60,0.15);}
    .sts.sleeperBed {background: rgba(0,0,0,0.15);}
    .sts.driving {background: rgba(30,132,73,0.15);}
    .sts.onDuty {background: rgba(230,126,34,0.15);}
    .sts.personalConveyance {background: rgba(52,152,219,0.15);}
</style>