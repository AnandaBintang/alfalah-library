<?php

namespace App\Enum;

enum StatusLoanBookEnum: string
{
    case BORROWED = 'borrowed';     // Sudah checkout tapi belum diapproved
    //    case REJECTED = 'rejected';     // Ditolak
    case RETURNED = 'returned';     // Dikembalikan
    //    case OVERDUE = 'overdue';       // TELAT
    case PENDING = 'pending';
}
