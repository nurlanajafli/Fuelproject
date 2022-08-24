<?php

namespace v1\components;

use Exception;
use yii\web\HttpException as BaseHttpException;

class HttpException extends BaseHttpException
{
    public function __construct($status, $message = null, $code = 0, Exception $previous = null)
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }
        parent::__construct($status, $message, $code, $previous);
    }
}
