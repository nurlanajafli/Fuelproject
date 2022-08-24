<?php

namespace console\controllers;

use common\models\User;
use yii\console\ExitCode;
use yii\helpers\Console;

class UserController extends \yii\console\Controller
{
    public function actionCreate()
    {
        $this->stdout('This action will create a new user' . PHP_EOL);
        if (!$this->confirm('Do you really want to create a user?', false)) {
            return ExitCode::OK;
        }
        $user = new User();
        $this->prompt($user->getAttributeLabel('email') . ':', [
            'required' => true,
            'validator' => function ($input, &$error) use ($user) {
                $user->email = $input;
                if (!$user->validate('email')) {
                    $error = $user->getFirstError('email');
                    return false;
                }
                return true;
            },
        ]);
        $user->password = $this->prompt('Password:', [
            'required' => true,
        ]);
        $user->generateAuthKey();
        if (!$user->save()) {
            $this->stderr('Failed to save user' . PHP_EOL . PHP_EOL, Console::FG_RED, Console::BOLD);
            foreach ($user->getErrors() as $attribute => $errors) {
                foreach ($errors as $error) {
                    $this->stderr($user->getAttributeLabel($attribute) . ': ', Console::FG_RED, Console::BOLD);
                    $this->stderr($error . PHP_EOL, Console::FG_YELLOW);
                }
            }
            return ExitCode::DATAERR;
        }
        $this->stdout('User successfully created' . PHP_EOL, Console::FG_GREEN, Console::BOLD);
        $this->stdout('User ID: ', Console::FG_GREEN);
        $this->stdout($user->getId() . PHP_EOL, Console::FG_YELLOW, Console::BOLD);
        return ExitCode::OK;
    }
}