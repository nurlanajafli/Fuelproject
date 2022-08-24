<?php

namespace v1\components\oauth2;

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
        if (!$data)
            return null;

        /** @var AppIdentity $user */
        if ($data['sub'] && ($user = AppIdentity::find()->
            alias('t')->
            joinWith('driver')->
            where(['t.id' => $data['sub'], 't.status' => static::STATUS_ACTIVE])->
            one())) {
            return $user;
        }

        return null;
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
