<?php

namespace v1\components\oauth2;

use OAuth2\Storage\UserCredentialsInterface;
use common\models\User;
use yii\db\Expression;

class UserCredentials implements UserCredentialsInterface
{
    /**
     * @inheritDoc
     */
    public function checkUserCredentials($username, $password)
    {
        // $user = User::findOne(['email' => $username]);
        $user = User::find()->where(new Expression("LOWER(email) = LOWER('$username')"))->one();
        return $user && $user->validatePassword($password);
    }

    /**
     * @inheritDoc
     */
    public function getUserDetails($username)
    {
        // $user = User::findOne(['email' => $username]);
        $user = User::find()->where(new Expression("LOWER(email) = LOWER('$username')"))->one();
        return $user ? ['user_id' => $user->id, 'scope' => $user->scope] : false;
    }
}
