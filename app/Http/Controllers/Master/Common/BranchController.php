<?php

namespace App\Http\Controllers\Master\Common;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchChildrenRequest;
use App\Http\Requests\Master\Common\Branch\BranchRequest;
use App\Models\Master\Common\Branch;
use App\Support\Master\Common\Branch\Service\BranchService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

#[AllowDynamicProperties] class BranchController extends Controller
{

    public function __construct()
    {
        $this->branchService = new BranchService();
        $this->branch = new Branch();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Branch::class);
        return view('pages.master.common.branches.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', Branch::class);
        return response()->json($this->branchService->data($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Branch::class);
        return response()->json($this->branchService->search($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function store(BranchRequest $request): JsonResponse
    {
        $this->authorize('create', Branch::class);
        DB::transaction(function () use ($request) {
            $this->branch->query()->create([
                'company_id' => $request->session()->get('company_session'),
                'name' => $request->name,
                'code' => $request->code,
                'address' => $request->address,
            ]);
            activity()->event('created')->withProperties([
                'attributes' => [
                    'Nama' => $request->input('name'),
                    'Alamat' => $request->input('address'),
                ]
            ])->log('Buat Cabang Baru');
        });
        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Branch $branch): JsonResponse
    {
        $branch->load('parent');
        $this->authorize('update', $branch);

        return response()->json($branch);
    }


    public function subBranchDetail(Branch $branch): JsonResponse
    {
        $branch->load('parent');
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


    public function storeChildren(BranchChildrenRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['parent_id'] = $request->parent_id;
        $this->branch->query()->create($data);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function updateChildren(BranchChildrenRequest $request, Branch $branch): JsonResponse
    {
        $branch->update($request->validated());
        return response()->json(['message' => 'data berhasil disimpan']);
    }

    /**
     * @throws Exception
     */
    public function destroy(Request $request, Branch $branch): JsonResponse
    {
        $this->authorize('delete', $branch);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $branch->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function destroyChildren(Branch $branch): JsonResponse
    {
        $branch->delete();
        return response()->json(['message' => 'data berhasil dihapus']);
    }


    public function getMainBranches(Request $request, BranchService $branchService): JsonResponse
    {
        return response()->json($branchService->getMainBranches($request));
    }


    public function getSubBranches(Request $request, Branch $branch): JsonResponse
    {
        return response()->json($this->branchService->getSubBranches($request, $branch->id));
    }

    public function selectedBranch(Branch $branch): JsonResponse
    {
        return response()->json($this->branchService->selectedBranch($branch?->id));
    }


    public function getAllBranch(Request $request): JsonResponse
    {
        return response()->json($this->branchService->getAllBranch($request));
    }
}
