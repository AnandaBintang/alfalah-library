<?php

namespace App\Enum;

enum StatusLoanBookEnum: string
{
    case BORROWED = 'borrowed';     // Sudah checkout tapi belum diapproved
    case APPROVED = 'approved';     // Disetujui oleh admin
    case REJECTED = 'rejected';     // Ditolak
    case RETURNED = 'returned';     // Dikembalikan
    case OVERDUE = 'overdue';       // TELAT
}
