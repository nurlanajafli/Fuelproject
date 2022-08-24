<?php

namespace common\enums;

use TRS\Enum\Enum;

class LoadStatus extends Enum
{
    const AVAILABLE = 'Available'; // Ready
    const RESERVED = 'Reserved';
    const PENDING = 'Pending';
    const POSSIBLE = 'Possible';
    const ENROUTED = 'Enrouted'; // Dispatched
    const COMPLETED = 'Completed'; // Arrived
    const CANCELLED = 'Cancelled';
    const DROPPED = 'Dropped';
}