<?php

namespace App\Support\Master\Common\Contact\Repository;

use App\Models\Master\Common\Contact;
use App\Support\Master\Common\Contact\Interface\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class ContactRepository implements ContactRepositoryInterface
{
    public function handle(): Builder
    {
        return Contact::orderBy('company_name');
    }

}
