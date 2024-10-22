<?php

namespace App\Http\Controllers\Operational\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\UnitType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{

    private static int $perPage = 10;
    private UnitType $unitType;


    public function __construct()
    {
        $this->unitType = new UnitType();
    }

    public function index(): View
    {
        return view('pages.inventory.index');
    }

    public function data(): JsonResponse
    {
        return response()->json(Inventory::with('unitType')->paginate(self::$perPage));
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = Inventory::orderBy('name');

        if (!empty($search)) {
            $query->where('sn', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%');
        }

        $inventory = $query->paginate(self::$perPage);
        return response()->json($inventory);
    }
}
