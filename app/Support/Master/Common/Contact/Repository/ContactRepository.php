<?php

namespace App\Support\Master\Common\Contact\Repository;

use AllowDynamicProperties;
use App\Models\Master\Common\Contact;
use App\Support\Master\Common\Contact\Interface\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class ContactRepository implements ContactRepositoryInterface
{

    public function __construct()
    {
        $this->contact = new Contact();
    }

    public function data(): Builder
    {
        return $this->contact
            ->query()
            ->orderBy('name');
    }


    public function getSuppliers(): Builder
    {
        return $this->contact
            ->query()
            ->where('type', 'Supplier')
            ->orderBy('name');
    }


    public function getClients(): Builder
    {
        return $this->contact->query()
            ->where('type', 'Client')
            ->orderBy('name');
    }

}
