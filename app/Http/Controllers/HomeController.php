<?php

namespace App\Http\Controllers;

use App\Models\Attendances;
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

    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        dd(Attendances::all());
        return view('home');
    }
}
