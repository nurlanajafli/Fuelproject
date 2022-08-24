<?php

namespace common\interfaces;

interface ImageThumb
{
    public static function getSizeMap();

    public static function getSize(string $size);

    public static function getLargestSize();
}
