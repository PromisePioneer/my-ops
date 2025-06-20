<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class WorkTimeSettingController extends Controller
{

    public function index(): View
    {
        return view('pages.adms.work-time-settings.index');
    }
}
