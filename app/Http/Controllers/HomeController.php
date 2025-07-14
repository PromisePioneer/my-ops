<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountingPeriodRequest;
use App\Models\AccountingPeriod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request): View
    {
        return view('home');
    }


    public function getAccountingPeriodSession()
    {
        return session()->get('accounting_period_session') ?? [];
    }

    public function accountingPeriodData()
    {
        return response()->json(AccountingPeriod::first()->year);
    }

    public function accountingPeriodUpdate(AccountingPeriodRequest $request)
    {
        AccountingPeriod::first()->update([
            'year' => $request->input('year'),
        ]);


        session()->forget('company_session');
        session()->put('company_session', $request->input('current_company_session'));
    }


}
