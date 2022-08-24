<?php

use frontend\assets\AppAsset;
use yii\helpers\Url;

?>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                   aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                 aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                               placeholder="Search for..." aria-label="Search"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1" id="notificationsDropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <?php if ($this->params['notifications']['badge']) : ?>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter"><?= $this->params['notifications']['badge'] ?></span>
                <?php endif; ?>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    <?= Yii::t('app', 'Notifications') ?>
                    <button class="ntf-remove" type="button" id="closeNotifications">
                      <i class="fa fa-times"></i>
                    </button>
                </h6>
                <?php if(isset($this->params['notifications'])):?>
                    <?php foreach ($this->params['notifications']['items'] as $notification) : ?>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="mr-3">
                            <div class="icon-circle bg-warning">
                                <i class="fas <?= $notification[0] ?> text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500"><?= $notification[1] ?></div>
                            <?= $notification[2] ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php endif?>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div>
        </li>

        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <?php if ($this->params['chat_messages']['total_count']) : ?>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter"><?= $this->params['chat_messages']['total_count'] ?></span>
                <?php endif; ?>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                   <?= Yii::t('app', 'Message Center') ?>
                </h6>
                <?php if(isset($this->params['chat_messages'])): ?>
                    <?php foreach ($this->params['chat_messages']['messages'] as $chatMessage) :
                        $options = $chatMessage->options;
                        $duration = $chatMessage->interval1;
                        $duration = str_replace(['@', 'years', 'year', 'mons', 'days', 'day', 'hours', 'hour', 'mins', ' '], ['', 'y', 'y', 'mon', 'd', 'd', 'h', 'h', 'min', ''], $duration);
                        $duration = preg_replace('/(\w+[^0-9])(\d+\.\d+secs?)/', '$1', $duration);
                    ?>
                    <a class="dropdown-item d-flex align-items-center" href="<?= Url::toRoute(['chat/view', 'id' => $options['room_id']]) ?>">
                        <div class="font-weight-bold">
                            <div class="text-truncate"><?= $chatMessage->message ?></div>
                            <div class="small text-gray-500"><?= $options['room_title'] ?> · <?= $duration ?></div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php endif?>
                <a class="dropdown-item text-center small text-gray-500" href="<?= Url::toRoute('chat/index') ?>"><?= Yii::t('app', 'Read More Messages') ?></a>
            </div>
        </li>
        <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= Yii::$app->user->identity->email ?></span>
                <img class="img-profile rounded-circle"
                     src="<?= AppAsset::getAssetUrl('images/svg/undraw_profile.svg') ?>">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
        <?php endif; ?>
    </ul>

</nav>
