<?php

namespace v1\components\oauth2\filters;

use filsh\yii2\oauth2server\Module;

class CompositeAuth extends \yii\filters\auth\CompositeAuth
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $server = Module::getInstance()->getServer();
        $value = $server->verifyResourceRequest();
        if (!$value)
            $this->handleFailure(null);

        return parent::beforeAction($action);
    }
}