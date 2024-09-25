<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class FinancialReportController extends Controller
{
    public function index(): View
    {
        return view('pages.journals.financial-report.index');
    }

    public function data()
    {
    }

}
