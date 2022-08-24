<?php

namespace common\enums;

use TRS\Enum\Enum;

abstract class BaseThumb extends Enum
{
    /**
     * @return array
     */
    abstract public static function getSizeMap();

    public static function getSize(string $size)
    {
        return static::getSizeMap()[$size];
    }
}