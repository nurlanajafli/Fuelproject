<?php

namespace common\components;

use Yii;
use yii\base\Component;

class Transaction extends Component
{
    public $isolationLevel = null;

    public function exec(callable $callback)
    {
        $t = Yii::$app->db->beginTransaction($this->isolationLevel);
        try {
            $b = call_user_func($callback);
        } catch (\Exception $exception) {
            $t->rollBack();
            throw $exception;
        }
        $b ? $t->commit() : $t->rollBack();
        return $b;
    }
}
