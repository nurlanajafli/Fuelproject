<?php

namespace common\enums;

use TRS\Enum\Enum;

class CronStatus extends Enum
{
    const _NEW = 'New';
    const IN_PROGRESS = 'In Progress';
    const RETRY = 'Retry';
    const DONE = 'Done';
}