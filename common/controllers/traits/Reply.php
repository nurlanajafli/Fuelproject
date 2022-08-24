<?php

namespace common\controllers\traits;

use Yii;
use yii\web\Response;

trait Reply
{
    public function replyJson($data)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }
}
