<?php

namespace App\Support\Master\Common\LetterHead\Repository;

use App\Models\LetterHead;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LetterHeadRepository
{
    public function data(Request $request): Builder
    {
        return LetterHead::where('company_id', $request->session()->get('company_session'));
    }
}
