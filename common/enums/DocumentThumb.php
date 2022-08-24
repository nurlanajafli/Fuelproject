<?php

namespace common\enums;

use TRS\Enum\Enum;
use common\interfaces\ImageThumb;

class DocumentThumb extends Enum implements ImageThumb
{
    const SMALL = 'small';
    const PREVIEW = 'preview';

    public static function getSizeMap()
    {
        return [
            static::SMALL => ['height' => 70, 'quality' => 100],
            static::PREVIEW => ['height' => 200, 'quality' => 100],
        ];
    }

    public static function getSize(string $size)
    {
        return static::getSizeMap()[$size];
    }

    public static function getLargestSize()
    {
        return static::getSize(static::PREVIEW);
    }
}
