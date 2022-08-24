<?php

namespace common\models\traits;

trait Person
{
    public function getFullName()
    {
        return join(', ', array_filter([$this->last_name, $this->first_name]));
    }
}
