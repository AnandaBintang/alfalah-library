<?php

namespace App\Enum;

enum StatusLoanBook: string
{
    case BORROWED = 'borrowed';
    case RETURNED = 'returned';
}
