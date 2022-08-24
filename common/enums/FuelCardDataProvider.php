<?php

namespace common\enums;

use TRS\Enum\Enum;

class FuelCardDataProvider extends Enum
{
    const EFS = 'EFS';
    const TCH = 'TCH';
    const KNOX = 'Knox';
    const T_CHEK = 'T-Chek';
    const COMDATA = 'Comdata';
    const COMDATA_MC = 'Comdata MC';
    const MULTI_SERVICE = 'Multi Service';
    const FLEET_ONE = 'Fleet One';
    const TCH_CHECK = 'TCH Check';
    const COM_CHEK = 'ComChek';
    const TOLLS = 'TOLLS';
    const TOLLS2 = 'TOLLS2';

    public static function getKey($val)
    {
        $data = self::getEnums();
        $flipped = array_flip($data);
        return $flipped[$val] ?? null;
    }
}