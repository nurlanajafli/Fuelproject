<?php

use common\models\Company;
use common\models\Driver;

/**
 * @var Driver $model
 * @var string $password
 */

/** @var Company $company */
$company = Company::findOne(Yii::$app->params['companyId']);
$companyName = $company ? $company->name : '';
?>
<p>Greetings <?= $model->_label ?>,</p>
<p><?= $companyName ?> has established an account for you on Mobile App.
This means that you can now take advantage of all the advanced messaging
capabilities offered by Mobile App.</p>
<br>
<p><i>Your Login Information is as follows - keep this information</i></p>
<p>User Name: <?= $model->email_address ?></p>
<p>Password: <?= $password ?></p>
<br><br>
<p>Thank You,</p>
<p><?= $companyName ?></p>
