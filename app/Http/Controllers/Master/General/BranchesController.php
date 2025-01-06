<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchChildrenRequest;
use App\Http\Requests\Master\Branch\BranchRequest;
use App\Models\Branch;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchesController extends Controller
{
    private static int $perPage = 10;

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Branch::class);
        return view('pages.general-master-data.branch.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Branch::class);
        $branches = Branch::with('children')->whereNull('parent_id')->paginate(10);

        return response()->json($branches);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Branch::class);

        $search = $request->input('search');
        $branchSearch = Branch::with('children')->when(!empty($search), function ($query) use ($search) {
            return $query->where('code', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhere('address', 'like', '%'.$search.'%');
        })->paginate(self::$perPage);

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

    /**
     * @throws AuthorizationException
     */
    public function show(Branch $branch): JsonResponse
    {
        $this->authorize('update', $branch);

        return response()->json($branch);
    }


    public function subBranchDetail(Branch $branch): JsonResponse
    {
        $subBranch = Branch::with('parent')->where('id', $branch->id)->first();
        return response()->json($subBranch);
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


    public function storeChildren(BranchChildrenRequest $request, Branch $branch): JsonResponse
    {
        Branch::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'address' => $request->address
        ]);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function updateChildren(BranchChildrenRequest $request, Branch $branch): JsonResponse
    {
        $branch->update([
            'name' => $request->name,
            'address' => $request->address
        ]);
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
}
