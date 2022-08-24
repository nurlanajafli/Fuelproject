<?php

use frontend\assets\AppAsset;

/**
 * @var int $trucksCount
 * @var int $trailersCount
 * @var yii\data\ActiveDataProvider $truckDataProvider
 * @var yii\data\ActiveDataProvider $trailerDataProvider
 */

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?=Yii::t('app', 'Assets')?></h1>
</div>
<!-- Content Row -->
<div class="row">
    <!-- Trucks -->
    <div class="col-xl-6 col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <a href="<?=yii\helpers\Url::to(['truck/index'])?>"><?=Yii::t('app', 'Trucks')?></a>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $trucksCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Trailers -->
    <div class="col-xl-6 col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            <a href="<?=yii\helpers\Url::to(['trailer/index'])?>"><?=Yii::t('app', 'Trailers')?></a>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $trailersCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-trailer fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content Row -->
<div class="row">
    <!-- Trucks -->
    <div class="col-xl-6 col-md-4 mb-4">
        <?= $this->render('_truck', ['dataProvider' => $truckDataProvider]); ?>
    </div>
    <!-- Trailers -->
    <div class="col-xl-6 col-md-4 mb-4">
        <?= $this->render('_trailer', ['dataProvider' => $trailerDataProvider]); ?>
    </div>
</div>
