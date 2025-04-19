<?php

namespace App\Support\Master\Common\Contact\Interface;

use App\Models\Master\Common\Contact;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContactServiceInterface
{
    public function data();

    public function search(Request $request);

    public static function formattedData(LengthAwarePaginator $data);

    public function getContacts(Request $request);

    public function selectedContact(Contact $contact);
}
