<?php

namespace App\Enum\Transaction;

enum TransactionType: string
{
    case DEFAULT = 'Default';
    case ITEM = 'Barang';
    case EXPENSE = 'Beban';
    case LEVERANGE = 'Utang';
    case RECEIVABLES = 'Piutang';
    case INITIAL_INVENTORY_BALANCE = 'Saldo Awal Persediaan';
}
