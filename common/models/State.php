<?php

namespace common\models;

use \common\models\base\State as BaseState;

/**
 * This is the model class for table "state".
 */
class State extends BaseState
{
    public function get_label()
    {
        return $this->state_code . " - " . $this->state;
    }
}
