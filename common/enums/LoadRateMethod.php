<?php

namespace common\enums;

use common\enums\LoadRateType as T;

class LoadRateMethod extends BaseEnum
{
    const ZIP_ZIP = "ZipZip";
    const ZONE_ZONE = "ZoneZone";
    const GEOGRAPH = "Geograph";
    const DISTANCE = "Distance";

    public static function getTypes($method) {
        $methods = self::getTypesMap();
        if (in_array($method, array_keys(static::getEnums())))
            return $methods[$method];
    }

    public static function getTypesMap(): array
    {
        return [
            self::ZIP_ZIP =>   [T::FLAT, T::MILES, T::PIECE, T::SPACE, T::POUND, T::CWT, T::TON, T::LOT, T::MULTI],
            self::ZONE_ZONE => [T::FLAT, T::MILES, T::PIECE, T::SPACE, T::POUND, T::CWT, T::TON, T::LOT,           T::STEP],
            self::GEOGRAPH =>  [T::FLAT, T::MILES, T::PIECE, T::SPACE, T::POUND, T::CWT, T::TON, T::LOT, T::MULTI],
            self::DISTANCE =>  [T::FLAT, T::MILES,                                       T::TON],
        ];
    }

    public static function getMethodsMap(): array
    {
        return [
            T::FLAT =>  [self::ZIP_ZIP, self::ZONE_ZONE, self::GEOGRAPH, self::DISTANCE],
            T::MILES => [self::ZIP_ZIP, self::ZONE_ZONE, self::GEOGRAPH, self::DISTANCE],
            T::PIECE => [self::ZIP_ZIP, self::ZONE_ZONE, self::GEOGRAPH                ],
            T::SPACE => [self::ZIP_ZIP, self::ZONE_ZONE, self::GEOGRAPH                ],
            T::POUND => [self::ZIP_ZIP, self::ZONE_ZONE, self::GEOGRAPH                ],
            T::CWT =>   [self::ZIP_ZIP, self::ZONE_ZONE, self::GEOGRAPH                ],
            T::TON =>   [self::ZIP_ZIP, self::ZONE_ZONE, self::GEOGRAPH, self::DISTANCE],
            T::LOT =>   [self::ZIP_ZIP, self::ZONE_ZONE, self::GEOGRAPH                ],
            T::MULTI => [self::ZIP_ZIP,                  self::GEOGRAPH                ],
            T::STEP =>  [               self::ZONE_ZONE                                ],
        ];
    }

    public static function getMethodsByType($type): array
    {
        return (self::getMethodsMap())[$type];
    }
}
