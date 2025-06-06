<?php

namespace App\Enum;

enum ConfirmationStatusLoanEnum: string
{
    case PENDING = 'pending'; // Status default ketika loan dibuat
    case APPROVED = 'approved'; //  Disetujui admin
    case REJECTED = 'rejected'; // Ditolak admin
}
