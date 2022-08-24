<?php

use frontend\assets\AppAsset;
use yii\helpers\Html;

$toggled = (isset($_COOKIE['accordion-toggled']) && $_COOKIE['accordion-toggled'] === 'true');

$appAsset = new AppAsset();
$appAsset->register($this);
$this->beginPage();
?>
    <?= $this->render('_header') ?>
    <body id="page-top" class="jquery-contextmenu<?= $toggled ? ' sidebar-toggled' : '' ?>">
    <?php $this->beginBody(); ?>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?= $this->render('_sidebar') ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?= $this->render('_topbar') ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid" id="container-fluid">

                    <?php if (\yii\helpers\ArrayHelper::isIn(Yii::$app->controller->getRoute(), ['site/index', 'load/index', 'driver-compliance/index'])) : ?>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?= Html::encode($this->title) ?></h1>
<!--                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i-->
<!--                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                    </div>
                    <?php endif; ?>

                    <?= $this->render('_alerts') ?>

                    <?= $content ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Jafton 2020-<?=date("Y")?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?= $this->render('_modals') ?>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php
$this->endPage();
