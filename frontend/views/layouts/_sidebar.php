<?php

use common\enums\Permission;
use yii\helpers\Url;

// Theme menu
/*$items = [
    ['divider', 'class' => 'my-0'],
    ['url' => 'index.html', 'icon' => 'fa-fw fa-tachometer-alt', 'label' => 'Dashboard'],
    ['divider', 'class' => ''],
    ['heading', 'Interface'],
    [
        [
            ['heading', 'Custom Components:'],
            ['url' => 'buttons.html', 'label' => 'Buttons'],
            ['url' => 'cards.html', 'label' => 'Cards']
        ],
        'icon' => 'fa-fw fa-cog',
        'label' => 'Components'
    ],
    [
        [
            ['heading', 'Custom Utilities:'],
            ['url' => 'utilities-color.html', 'label' => 'Colors'],
            ['url' => 'utilities-border.html', 'label' => 'Borders'],
            ['url' => 'utilities-animation.html', 'label' => 'Animations'],
            ['url' => 'utilities-other.html', 'label' => 'Other']
        ],
        'icon' => 'fa-fw fa-wrench',
        'label' => 'Utilities'
    ],
    ['divider', 'class' => ''],
    ['heading', 'Addons'],
    [
        [
            ['heading', 'Login Screens:'],
            ['url' => 'login.html', 'label' => 'Login'],
            ['url' => 'register.html', 'label' => 'Register'],
            ['url' => 'forgot-password.html', 'label' => 'Forgot Password'],
            ['heading', 'Other Pages:'],
            ['url' => '404.html', 'label' => '404 Page'],
            ['url' => 'blank.html', 'label' => 'Blank Page']
        ],
        'icon' => 'fa-fw fa-folder',
        'label' => 'Pages'
    ],
    ['url' => 'charts.html', 'icon' => 'fa-fw fa-chart-area', 'label' => 'Charts'],
    ['url' => 'tables.html', 'icon' => 'fa-fw fa-table', 'label' => 'Tables']
];*/

$items = [
    ['divider', 'class' => 'my-0'],
    [
        [
            ['url' => ['/asset'], 'label' => Yii::t('app', 'Assets'), 'permission' => Permission::ASSET_MANAGER],
            ['url' => ['/driver'], 'label' => Yii::t('app', 'Drivers'), 'permission' => Permission::ADD_EDIT_DRIVERS],
        ],
        'icon' => 'fa-fw fa-address-card',
        'label' => 'Overview'
    ],
    [
        [
            ['url' => ['load/index'], 'label' => Yii::t('app', 'Manager'), 'permission' => Permission::ADD_EDIT_LOADS],
//            ['url' => ['load/board'], 'label' => Yii::t('app', 'Board'), 'permission' => Permission::LOAD_BOARD],
            ['url' => ['load/edit'], 'label' => Yii::t('app', 'Load'), 'permission' => Permission::ADD_EDIT_LOADS],
            ['url' => ['/unit'], 'label' => Yii::t('app', 'Units'), 'permission' => Permission::ADD_EDIT_UNITS],
            ['url' => ['load/arrived'], 'label' => Yii::t('app', 'Arrived'), 'permission' => Permission::ADD_EDIT_LOADS]
        ],
        'icon' => 'fa-fw fa-route',
        'label' => 'Dispatch'
    ],
    [
        [
            ['url' => ['/truck'], 'label' => Yii::t('app', 'Trucks'), 'permission' => Permission::TRUCK_LISTING],
            ['url' => ['/trailer'], 'label' => Yii::t('app', 'Trailers'), 'permission' => Permission::TRAILER_LISTING],
            ['url' => ['/driver-compliance'], 'label' => Yii::t('app', 'Driver Compliance'), 'permission' => Permission::ADD_EDIT_DRIVERS],
            ['url' => ['fuel/import'], 'label' => Yii::t('app', 'Fuel Card Data'), 'permission' => Permission::FUEL_MANAGEMENT],
            ['url' => ['/fuel-purchase'], 'label' => Yii::t('app', 'Fuel Purchases'), 'permission' => Permission::ADD_EDIT_PURCHASE_ORDERS],
            ['url' => ['/work-order'], 'label' => Yii::t('app', 'Work Orders'), 'permission' => Permission::ADD_EDIT_PURCHASE_ORDERS],
            ['url' => ['/report'], 'label' => Yii::t('app', 'Reports'), 'permission' => Permission::VIEW_REPORTS],
        ],
        'icon' => 'fa-fw fa-trailer',
        'label' => 'Fleet'
    ],
    [
        [
            ['url' => ['/customer'], 'label' => Yii::t('app', 'Customers'), 'permission' => Permission::CUSTOMER_LISTING],
            ['url' => ['/vendor'], 'label' => Yii::t('app', 'Vendors'), 'permission' => Permission::VENDOR_LISTING],
            ['url' => ['/carrier'], 'label' => Yii::t('app', 'Carriers'), 'permission' => Permission::CARRIER_LISTING],
            ['url' => ['/location'], 'label' => Yii::t('app', 'Locations'), 'permission' => Permission::LOCATION_LISTING],
        ],
        'icon' => 'fa-fw fa-list',
        'label' => 'Lists'
    ],
    [
        [
            ['url' => ['accounting/load-billing'], 'label' => Yii::t('app', 'Load Billing'), 'permission' => Permission::LOAD_BILLING_TL],
            ['url' => ['accounting/load-clearing'], 'label' => Yii::t('app', 'Load Clearing'), 'permission' => Permission::LOAD_CLEARING],
            ['url' => ['/payroll-batch'], 'label' => Yii::t('app', 'Payroll'), 'permission' => Permission::PAYROLL_JOURNAL]
        ],
        'icon' => 'fa-fw fa-book',
        'label' => 'Accounting'
    ],
    [
        [
            ['url' => ['samsara/trucks-on-map'], 'label' => Yii::t('app', 'Samsara - Trucks on Map'), 'permission' => Permission::ADD_EDIT_DRIVERS],
            ['url' => ['samsara/drivers'], 'label' => Yii::t('app', 'Samsara - Drivers'), 'permission' => Permission::ADD_EDIT_DRIVERS],
        ],
        'icon' => 'fa-fw fa-cogs',
        'label' => '3rdParty Integrations'
    ],
];

$href = function ($item) {
    return Url::to($item['url']);
};

$isItemActive = function ($item) {
    $content = dmstr\widgets\Menu::widget([
        'items' => [
            ['url' => $item['url']]
        ],
        'route' => (is_array($item['url']) && strpos($item['url'][0], '/') === 0)
            ? Yii::$app->controller->id
            : null
    ]);
    return strpos($content, 'active');
};

$toggled = (isset($_COOKIE['accordion-toggled']) && $_COOKIE['accordion-toggled'] === 'true');
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion<?= $toggled ? ' toggled' : '' ?>"
    id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-truck"></i>
    </div>
    <div class="sidebar-brand-text mx-3">TMS</div>
  </a>
    <?php foreach ($items as $k => $item) : ?>

        <?php if ($item[0] === 'divider') : ?>

        <!-- Divider -->
        <hr class="sidebar-divider <?= $item['class'] ?>">

        <?php elseif ($item[0] === 'heading') : ?>

        <!-- Heading -->
        <div class="sidebar-heading">
            <?= $item[1] ?>
        </div>

        <?php elseif (is_array($item[0])) : $hasActive = count(array_filter($item[0], $isItemActive)) > 0; ?>
        <!-- Nav Item - <?= $item['label'] ?> Collapse Menu -->

            <?php $currentItemBlock = false;
            foreach ($item[0] as $k2 => $item2) :
                if (Yii::$app->user->can($item2['permission'])) {
                    $currentItemBlock = true;
                }
            endforeach; ?>
            <?php if ($currentItemBlock == true) { ?>
          <li class="nav-item<?= $hasActive ? ' active' : '' ?>">
            <a class="nav-link<?= !$hasActive || $toggled ? ' collapsed' : '' ?>" href="#" data-toggle="collapse"
               data-target="#collapse<?= $k ?>"
               aria-expanded="<?= $hasActive && !$toggled ? 'true' : 'false' ?>" aria-controls="collapse<?= $k ?>">
              <i class="fas <?= $item['icon'] ?>"></i>
              <span><?= $item['label'] ?></span>
            </a>
            <div id="collapse<?= $k ?>" class="collapse<?= $hasActive && !$toggled ? ' show' : '' ?>"
                 aria-labelledby="heading<?= $k ?>" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                  <?php foreach ($item[0] as $k2 => $item2) : ?>
                      <?php if (isset($item2[0]) && $item2[0] === 'heading') : if ($k2) echo '<div class="collapse-divider"></div>'; ?>
                      <h6 class="collapse-header"><?= $item2[1] ?></h6>
                      <?php else : ?>
                          <?php if (Yii::$app->user->can($item2['permission'])) { ?>
                        <a class="collapse-item<?= $isItemActive($item2) ? ' active' : '' ?>"
                           href="<?= $href($item2) ?>"><?= $item2['label'] ?></a>
                          <?php } ?>
                      <?php endif; endforeach; ?>
              </div>
            </div>
          </li>
            <?php } ?>
        <?php else : ?>

        <!-- Nav Item - <?= $item['label'] ?> -->
        <li class="nav-item<?= $isItemActive($item) ? ' active' : '' ?>">
          <a class="nav-link" href="<?= $href($item) ?>">
            <i class="fas <?= $item['icon'] ?>"></i>
            <span><?= $item['label'] ?></span></a>
        </li>

        <?php endif; endforeach; ?>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
