<?php

namespace common\enums;

use TRS\Enum\Enum;

class BusinessType extends Enum {
	const CORPORATION = 'Corporation';
    const SOLE_PROPRIETOR = 'Sole Proprietor';
    const PARTNERSHIP = 'Partnership';
}
