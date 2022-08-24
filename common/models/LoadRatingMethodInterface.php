<?php

namespace common\models;

interface LoadRatingMethodInterface
{
    public static function getColumns($rateType);

    public function getFieldParams($model, $field);
}