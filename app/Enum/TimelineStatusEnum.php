<?php

namespace App\Enum;

enum TimelineStatusEnum: string
{
    case ONTIME = 'ontime';
    case OVERDUE = 'overdue';
    case PENDING = 'pending';
}
