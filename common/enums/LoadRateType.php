<?php

namespace common\enums;

use TRS\Enum\Enum;

class LoadRateType extends Enum
{
    const FLAT = "Flat";
    const MILES = "Miles";
    const PIECE = "Piece";
    const SPACE = "Space";
    const POUND = "Pound";
    const CWT = "Cwt";
    const TON = "Ton";
    const LOT = "Lot";
    const MULTI = "Multi";
    const STEP = "Step";
}
