<?php

namespace common\enums;

use TRS\Enum\Enum;

class CronTask extends Enum
{
    const DELETE_FILE = 'Delete File';
    const DELETE_EMPTY_LOAD = 'Delete Empty Load';
}