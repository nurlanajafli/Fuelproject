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
<p>Load assign from <?= $companyName ?>.
</p>
<br><br>
<p>Thank You,</p>
<p><?= $companyName ?></p>
