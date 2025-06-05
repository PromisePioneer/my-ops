<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WorkTimeSettingController extends Controller
{

    public function index(): View
    {
        return view('pages.adms.work-time-settings.index');
    }
}
