<?php
/**
 * @var string $directoryAsset
 */

use dmstr\widgets\Menu;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=\Yii::$app->user->identity->username?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'TMS System', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'], 'options' => YII_DEBUG ? [] : ['class' => 'hide']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'], 'options' => YII_DEBUG ? [] : ['class' => 'hide']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Company',
                        'icon' => 'home',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Company Info',
                                'url' => ['/company/update', 'id' => Yii::$app->params['companyId']],
                                'active' => Yii::$app->controller->getUniqueId() == 'company'
                            ],
                            ['label' => 'Offices', 'url' => ['/office']],
                            ['label' => 'Company Contact Depts', 'url' => ['/department']],
                            ['label' => 'Company Note Codes', 'url' => ['/company-note-code']],
                            ['label' => 'States', 'url' => ['/states']],
                            ['label' => 'Users', 'url' => ['/user']],
                            ['label' => 'Permissions', 'url' => ['/permission']],
                        ]
                    ],
                    [
                        'label' => 'Accounting',
                        'icon' => 'usd',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Account Types', 'url' => ['/account-type']],
                            ['label' => 'Accounts', 'url' => ['/account']],
                            ['label' => 'Payroll Adjustment Codes', 'url' => ['/payroll-adjustment-code']],
                            ['label' => 'Payment Terms Codes', 'url' => ['/payment-term-code']],
                            ['label' => 'Purchase Order Codes', 'url' => ['/purchase-order-code']],
                            ['label' => 'Invoice Item Codes', 'url' => ['/invoice-item-code']],
                            ['label' => 'Collection Codes', 'url' => ['/collection-code']],
                            ['label' => 'Accounting Defaults', 'url' => ['/accounting-default']],
                        ]
                    ],
                    [
                        'label' => 'Dispatch',
                        'icon' => 'road',
                        'url' => '#',
                        'items' => [
                            ['label' => "Truck Types", 'url' => ['/truck-type']],
                            ['label' => "Trailer Types", 'url' => ['/trailer-type']],
                            ['label' => "Load Types", 'url' => ['/load-type']],
                            ['label' => "Load Note Types", 'url' => ['/load-note-type']],
                            ['label' => "Zones", 'url' => ['/zone']],
                            ['label' => "Commodities", 'url' => ['/commodity']],
                        ],
                    ],
                    [
                        'label' => 'Fleet',
                        'icon' => 'truck',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Claim Codes', 'url' => ['/claim-code']],
                            ['label' => 'Accident Codes', 'url' => ['/accident-code']],
                            ['label' => 'Log Activity Codes', 'url' => ['/log-activity-code']],
                            ['label' => 'Log Violation Codes', 'url' => ['/log-violation-code']],
                            ['label' => 'Violation Messages', 'url' => ['/violation-message']],
                            ['label' => 'Vehicle Service Codes', 'url' => ['/vehicle-service-code']],
                            ['label' => 'Parts Categories', 'url' => ['/parts-category']],
                        ]
                    ],
                    [
                        'label' => 'Matrices',
                        'icon' => 'th',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Load Rating', 'url' => ['/load-rating']],
                            ['label' => 'Commodity Rating', 'url' => '#'],
                            ['label' => 'Accessorial Matrix', 'url' => ['/accessorial-matrix']],
                            ['label' => 'Accessorial Rating', 'url' => ['/accessorial-rating']],
                            ['label' => 'Fuel Surcharges', 'url' => '#'],
                            ['label' => 'Accessorial Pay', 'url' => ['/accessorial-pay']],
                            ['label' => 'Dispatch Pay', 'url' => '#'],
                        ]
                    ],
                    ['label' => 'International', 'icon' => 'flag', 'url' => '#'],
                    /*
                    [
                        'label' => 'Level One',
                        'icon' => 'circle-o',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                            [
                                'label' => 'Level Two',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                    ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                ],
                            ],
                        ],
                    ],
                    */
                    ['label' => 'Settings', 'icon' => 'gear', 'url' => ['/settings']],
                ],
                'route' => Yii::$app->controller->getUniqueId(),
            ]
        ) ?>

    </section>

</aside>
