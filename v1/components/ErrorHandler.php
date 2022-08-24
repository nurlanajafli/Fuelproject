<?php

namespace v1\components;

use Exception;
use OAuth2\ResponseInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

class ErrorHandler extends \yii\base\ErrorHandler
{
    /**
     * @inheritDoc
     */
    protected function renderException($exception)
    {
        $code = 500;
        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        }

        $name = 'Error';
        $message = 'Something has gone wrong.';

        if ($exception instanceof Exception) {
            $name = $exception->getName();
            $message = $exception->getMessage();
        }

        if (!is_null($decoded = json_decode($message))) {
            $message = $decoded;
        }

        $errorData = [
            'exception' => $name,
            'message' => $message,
            'code' => $code
        ];

        if (YII_DEBUG)
            $errorData = ArrayHelper::merge($errorData, [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);

        /** @var ResponseInterface $response */
        $response = Yii::$app->getModule('oauth2')->getServer()->getResponse();
        $response->setStatusCode($code);
        $response->setParameters([
            'status' => ($code >= 500) ? 'error' : 'fail',
            'message' => $message,
            'data' => $errorData,
            'code' => $code
        ]);
        $response->send();
    }
}
