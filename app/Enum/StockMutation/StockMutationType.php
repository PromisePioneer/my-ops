<?php

namespace App\Enum\StockMutation;

enum StockMutationType: string
{
    case  SENT = 'Dikirim';
    case ACCEPTED = 'Diterima';
    case CANCELED = 'Dibatalkan';
}
