<?php

namespace common\enums;

use TRS\Enum\Enum;

class CustomerCalcBy extends Enum
{
    const ADDRESS_TO_ADDRESS = 'Address to Address';
    const ZIP_TO_ZIP = 'Zip to Zip';
}