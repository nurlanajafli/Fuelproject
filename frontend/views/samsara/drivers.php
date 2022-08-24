<?php
use yii\helpers\Html;
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Samsara Drivers</h1>
</div>
<table class="table table-bordered">
    <thead class="thead-light">
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Driving status</th>
        <th scope="col">Current Vehicle</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($allDriversList as $v) { ?>
    <tr>
        <th scope="row">
            <?= Html::a($v['name'], ['samsara/driver', 'id' => $v['id']], ['class' => 'primary-link','style'=>'text-decoration:underline']) ?><br>
            <small><?=$v['username']?></small><br>
            <small><?=$v['id']?></small>
        </th>
        <td>
            <?=$v['currentDutyStatus']['hosStatusType']?><br>
            <small><?=$v['driverActivationStatus']?></small>
        </td>
        <td><?=$v['currentVehicle']['name']?></td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<pre>
    <?php // print_r($allDriversList); ?>
</pre>
