<?php

namespace common\enums;


use TRS\Enum\Enum;

class Authority extends Enum
{
    const COMMON = 'Common';
    const CONTRACT = 'Contract';
    const BROKER = 'Broker';
    const COMMON_CONTRACT = 'Common/Contract';
    const COMMON_CONTRACT_BROKER = 'Common/Contract/Broker';
}