<?php

namespace App\Enum;

enum StatusCartEnum: string
{
    case PENDING = 'pending'; // Status pending artinya hanya berada di keranjang user
    case CHECK_OUT = 'checkout'; // Sudah disetujui admin untuk dipinjamkan
    case EXPIRED = 'expired'; // Kadaluarsa
    case CANCELLED = 'cancelled'; // Ditolak admin
}
