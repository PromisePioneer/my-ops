<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use App\Models\Master\Common\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Invoice extends Model
{
    use Notifiable;

    protected $table = 'invoices';

    protected $fillable = [
        'fab_id',
        'contact_id',
        'branch_id',
        'invoice_number',
        'account_id',
        'due_date',
        'description',
        'baa_file',
        'cooperative_contract_file',
        'grand_total',
        'payment_status',
        'created_by',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
