<?php
$lastDailyLog = end($allHosLogsDaily);
$toSeconds = function($time) {
    $time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $time);
    sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);
    $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
    return $time_seconds;
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
$interval = function ($startTime,$endTime) {
    $sFormat = date('Y-m-d H:i:s', strtotime($startTime));
    $eFormat = date('Y-m-d H:i:s', strtotime($endTime));
    $start = new \DateTime($sFormat);
    $end = new \DateTime($eFormat);
    $intervalTime = $start->diff($end);
    $intervalTime = $intervalTime->format("%H:%i:%s");
    return $intervalTime;
};

use yii\helpers\Html; ?>
<p><?= Html::a('< Back', ['samsara/driver','id'=>$driverInfoLog['driver']['id']], ['class' => 'primary-link']) ?></p>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Hours of service Report - <?=$driverInfoLog['driver']['name']?></h1>
</div>
<?php $lastLog = end($driverInfoLog['hosLogs']); ?>
<div class="card mb-2">
    <div class="card-body">
        <table class="table no-border">
            <tbody class="no-border">
                <tr class="no-border">
                    <td class="no-border">
                        <p>Duty status</p>
                        <p>
                            <strong class="<?=$driverInfoClockDetail['currentDutyStatus']['hosStatusType']?>">
                                <?=$statusText($driverInfoClockDetail['currentDutyStatus']['hosStatusType'],true)?>
                            </strong>
                        </p>
                    </td>
                    <td>
                        <p>Time in current status</p>
                        <p><strong>
                                <?php
                                $intervalSec = $interval($lastLog['logStartTime'],$lastLog['logEndTime']);
                                echo gmDate("H:i:s",$toSeconds($intervalSec))?>
                            </strong>
                        </p>
                    </td>
                    <td>
                        <p>Viecel name</p>
                        <p><strong><?=$driverInfoClockDetail['currentVehicle']['name']?></strong></p>
                    </td>
                    <td>
                        <p>Time until break</p>
                        <p><strong><?=gmdate("H:i",$driverInfoClockDetail['clocks']['break']['timeUntilBreakDurationMs']/1000)?></strong></p>
                    </td>
                    <td>
                        <p>Drive remainning</p>
                        <p><strong><?=gmdate("H:i",$driverInfoClockDetail['clocks']['drive']['driveRemainingDurationMs']/1000)?></strong></p>
                    </td>
                    <td>
                        <p>Shift remainning</p>
                        <p><strong><?=gmdate("H:i",$driverInfoClockDetail['clocks']['shift']['shiftRemainingDurationMs']/1000)?></strong></p>
                    </td>
                    <td>
                        <p>Cycle remainning</p>
                        <p><strong><?=gmdate("H:i",$driverInfoClockDetail['clocks']['cycle']['cycleRemainingDurationMs']/1000)?></strong></p>
                    </td>
                    <td>
                        <p>Cycle tomorrow</p>
                        <p><strong><?=gmdate("H:i",$driverInfoClockDetail['clocks']['cycle']['cycleTomorrowDurationMs']/1000)?></strong></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
    <div class="card mb-2">
        <div class="card-body">
            <p><strong>Carrier name:</strong> <span><?=$lastDailyLog['logMetaData']['carrierName']?></span></p>
            <p><strong>Carrier Address:</strong> <span><?=$lastDailyLog['logMetaData']['carrierFormattedAddress']?></span></p>
            <p><strong>Carrier US DOT Numbers:</strong> <span><?=$lastDailyLog['logMetaData']['carrierUsDotNumber']?></span></p>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <p><strong>Driver:</strong> <span><?=$driverInfoLog['driver']['name']?></span></p>
            <p>
                <strong>Co-drivers:</strong>
                <?php foreach($lastLog['codrivers'] as $lk=>$lv) { ?>
                    <p><?=$lv['name']?></p>
                <?php } ?>
            </p>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <p><strong>Viecel:</strong> <span><?=$driverInfoClockDetail['currentVehicle']['name']?></span></p>
            <p><strong>Home Terminal Name:</strong> <span><?=$lastDailyLog['logMetaData']['homeTerminalName']?></span></p>
            <p><strong>Home Terminal Formatted Address:</strong> <span><?=$lastDailyLog['logMetaData']['homeTerminalFormattedAddress']?></span></p>
        </div>
    </div>
<pre>
<?php //print_r($lastDailyLog); ?>
    <?php //print_r($driverInfoLog); ?>
</pre>
<style>
    strong.offDuty {color:#E74C3C;}
    strong.sleeperBed {color:#000;}
    strong.driving {color:#1E8449;}
    strong.onDuty {color:#E67E22;}
    strong.personalConveyance {color:#3498DB;}
</style>
