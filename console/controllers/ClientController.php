<?php

namespace console\controllers;

use common\enums\GrantType;
use common\enums\Scope;
use filsh\yii2\oauth2server\models\OauthClients;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class ClientController extends Controller
{
    public function actionCreate()
    {
        if (!$this->confirm('Do you really want to create a new client?')) {
            return ExitCode::OK;
        }

        $model = new OauthClients();
        $model->redirect_uri = 'oob';
        $grantTypes = [GrantType::CLIENT_CREDENTIALS, GrantType::PASSWORD, GrantType::REFRESH_TOKEN];
        $model->grant_types = implode(' ', $grantTypes);
        $model->scope = $this->select('Select a scope:', Scope::getUiEnums());
        foreach ($model->getAttributes(['redirect_uri', 'grant_types', 'scope']) as $attrName => $attrValue) {
            $this->stdout($model->getAttributeLabel($attrName) . ': ', Console::BOLD);
            $this->stdout($attrValue . PHP_EOL, Console::FG_RED);
        }
        if (!$this->confirm('All data is correct?')) {
            return ExitCode::OK;
        }

        $security = Yii::$app->getSecurity();
        $model->client_id = $security->generateRandomString(32);
        $model->client_secret = $security->generateRandomString(32);
        if (!$model->save()) {
            $this->stdout('Failed to create a new client' . PHP_EOL);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        foreach ($model->getAttributes(['client_id', 'client_secret']) as $attrName => $attrValue) {
            $this->stdout($model->getAttributeLabel($attrName) . ': ', Console::FG_GREEN);
            $this->stdout($attrValue . PHP_EOL, Console::FG_YELLOW, Console::BOLD);
        }

        return ExitCode::OK;
    }


}