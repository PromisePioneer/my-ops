<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Branch\BranchImportRequest;
use App\Http\Requests\Master\Branch\BranchRequest;
use App\Imports\BranchesImport;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class BranchesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat cabang', ['only' => ['index']]);
        $this->middleware('permission:tambah cabang', ['only' => ['create', 'store']]);
        $this->middleware('permission:update cabang', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hapus cabang', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        return view('pages.master.branch.index');
    }

    public function data(): JsonResponse
    {
        $branches = Branch::select('id', 'code', 'name')->paginate(10);

        return response()->json($branches);
    }

    public function search(Request $request): JsonResponse
    {
        $branchSearch = Branch::where('name', 'like', '%'.$request->search.'%')
            ->Orwhere('code', 'like', '%'.$request->search.'%')
            ->select('id', 'code', 'name')
            ->limit(25)
            ->get();

        return response()->json($branchSearch);
    }

    public function store(BranchRequest $request): JsonResponse
    {
        Branch::create($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    public function structureOrgranization(Branch $branch): View
    {
        return view('pages.master.branch.structure-organization', compact('branch'));
    }

    public function structureOrgranizationData(Branch $branch): JsonResponse
    {
        $kacab = User::with('roles')->whereHas('roles', static function ($query) {
            $query->where('name', 'Kepala Cabang');
        })->where('branch_id', $branch->id)->first();

        $accountant = User::with('roles')->whereHas('roles', static function ($query) {
            $query->where('name', 'Accountant');
        })->where('branch_id', $branch->id)->first();

        $kca = User::with('roles')->whereHas('roles', static function ($query) {
            $query->where('name', 'KCA');
        })->where('branch_id', $branch->id)->get();

        $wkca = User::with('roles')->whereHas('roles', static function ($query) {
            $query->where('name', 'wkca');
        })->where('branch_id', $branch->id)->get();

        $frontOfficeUser = User::with('roles', 'department')->whereHas('department', function ($query) {
            $query->where('name', 'Front Office');
        })->where('branch_id', $branch->id)
            ->get();

        $backOfficeUser = User::with('roles', 'department')->whereHas('department', function ($query) {
            $query->where('name', 'Back Office');
        })->where('branch_id', $branch->id)
            ->get();

        $fieldWorkerUser = User::with('roles', 'department')->whereHas('department', function ($query) {
            $query->where('name', 'Pekerja Lapangan');
        })->where('branch_id', $branch->id)
            ->get();

        return response()->json([
            'kacab' => $kacab,
            'kca' => $kca,
            'wkca' => $wkca,
            'frontOfficeUser' => $frontOfficeUser,
            'backOfficeUser' => $backOfficeUser,
            'fieldWorkerUser' => $fieldWorkerUser,
        ]);
    }

    public function show(Branch $branch): JsonResponse
    {
        return response()->json($branch);
    }

    public function update(BranchRequest $request, Branch $branch): JsonResponse
    {
        $branch->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function destroy(Request $request, Branch $branch): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $branch->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }

    public function import(BranchImportRequest $request): JsonResponse
    {
        $file = $request->file('file_import');

        Excel::import(new BranchesImport(), $file);

        return response()->json([
            'message' => 'Data berhasil diimport',
        ]);
    }
}
