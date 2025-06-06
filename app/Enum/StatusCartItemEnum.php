<?php

namespace App\Enum;

enum StatusCartItemEnum: string
{
    case BOOKED = 'booked'; // Sudah booked, tapi belum approve oleh admin
    case REJECTED = 'rejected'; // Ditolak admin
    case APPROVED = 'approved'; // Disetujia admin dipinjam atau diperpanjang
}
