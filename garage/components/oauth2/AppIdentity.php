<?php

namespace garage\components\oauth2;

use common\enums\Scope;
use common\models\User;
use OAuth2\Encryption\Jwt;

class AppIdentity extends User
{
    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $jwt = new Jwt();
        $data = $jwt->decode($token, null, false);
        $user = null;
        if ($data && $data['sub']) {
            /** @var User $user */
            $user = static::find()->andWhere(['id' => $data['sub'], 'status' => static::STATUS_ACTIVE])->one();
            if ($user->driver) {
                $user = null;
            }
        }
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
