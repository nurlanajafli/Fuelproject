<?php
/**
 * @var \common\models\Driver $model
 * @var string $password
 */

use common\models\Setting;

$companyName = Setting::get('company_name');
?>
Greetings <?= $model->_label ?>,

<?= $companyName ?> has established an account for you on Mobile App.
This means that you can now take advantage of all the advanced messaging
capabilities offered by Mobile App.

Your Login Information is as follows - keep this information

User Name: <?= $model->email_address ?>
Password: <?= $password ?>


Thank You,
<?= $companyName ?>
