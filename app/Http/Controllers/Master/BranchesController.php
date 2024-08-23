<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Branch\BranchImportRequest;
use App\Http\Requests\Master\Branch\BranchRequest;
use App\Imports\BranchesImport;
use App\Models\Branch;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class BranchesController extends Controller
{

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Branch::class);
        return view('pages.master.branch.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Branch::class);
        $branches = Branch::select('id', 'code', 'name')->paginate(10);
        return response()->json($branches);
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Branch::class);
        $branchSearch = Branch::where('name', 'like', '%'.$request->search.'%')
            ->Orwhere('code', 'like', '%'.$request->search.'%')
            ->select('id', 'code', 'name')
            ->limit(25)
            ->get();

        return response()->json($branchSearch);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(BranchRequest $request): JsonResponse
    {
        $this->authorize('create', Branch::class);
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

    /**
     * @throws AuthorizationException
     */
    public function show(Branch $branch): JsonResponse
    {
        $this->authorize('update', $branch);
        return response()->json($branch);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(BranchRequest $request, Branch $branch): JsonResponse
    {
        $this->authorize('update', $branch);
        $branch->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    /**
     * @throws Exception
     */
    public function destroy(Request $request, Branch $branch): JsonResponse
    {
        $this->authorize('delete', $branch);
        Branch::whereIn('id', [$request->get('id')])->delete();
        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function import(BranchImportRequest $request): JsonResponse
    {
        $this->authorize('import', Branch::class);
        $file = $request->file('file_import');

        Excel::import(new BranchesImport(), $file);

        return response()->json([
            'message' => 'Data berhasil diimport',
        ]);
    }
}
