<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\ListOfItem;
use App\Models\Supplier;
use App\Service\ListOfItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class ListOfItemController extends Controller
{
    public function __construct()
    {
        $this->supplier = new Supplier();
        $this->listOfItemService = new ListOfItemService();
    }

    public function index(): View
    {
        return view('pages.inventory.list-of-items.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->listOfItemService->data());
    }


    public function search(): JsonResponse
    {
        return response()->json();
    }

    public function create()
    {

    }


    public function store()
    {

    }


    public function update()
    {

    }


    public function destroy()
    {

    }
}
