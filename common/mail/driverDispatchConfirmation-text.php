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
Greetings <?= $model->_label ?>,
Load assign from <?= $companyName ?>.

Thank You,
<?= $companyName ?>
