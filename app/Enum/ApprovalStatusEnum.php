<?php

namespace App\Enum;

enum ApprovalStatusEnum: string
{
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case PENDING = 'pending';
}
