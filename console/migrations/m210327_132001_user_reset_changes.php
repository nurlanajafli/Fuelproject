<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m210327_132001_user_reset_changes
 */
class m210327_132001_user_reset_changes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            '{{%user_driver_id_fk}}',
            '{{%user}}'
        );
        $this->dropColumn('{{%user}}', 'driver_id');
        $users = User::find()->andWhere(['auth_key' => null])->all();
        foreach ($users as $user) {
            /** @var User $user */
            $user->auth_key = Yii::$app->getSecurity()->generateRandomString(32);
            $user->save(false);
        }
        $this->alterColumn('{{%user}}', 'auth_key', $this->string(32)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%user}}', 'driver_id', $this->integer());
        $this->addForeignKey(
            '{{%user_driver_id_fk}}',
            '{{%user}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->alterColumn('{{%user}}', 'auth_key', $this->string(40));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210327_132001_user_reset_changes cannot be reverted.\n";

        return false;
    }
    */
}
