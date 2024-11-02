<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{

    private static int $perPage = 10;

    public function index(): View
    {
        $this->authorize('view', Company::class);
        return view('pages.general-master-data.company.index');
    }


    public function data(): JsonResponse
    {
        $this->authorize('view', Company::class);
        $company = Company::paginate(self::$perPage);
        return response()->json($company);
    }


    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Company::class);
        $search = $request->input('search');
        $company = Company::paginate(self::$perPage);


        if (!empty($search)) {
            $company->where('name', 'like', '%' . $search . '%');
        }

        $data = $company->paginate(self::$perPage);
        return response()->json($data);
    }


    public function store(CompanyRequest $request): JsonResponse
    {
        $this->authorize('create', Company::class);
        return response()->json(Company::create($request->validated()));
    }


    public function edit(Company $company): JsonResponse
    {
        $this->authorize('edit', $company);
        return response()->json($company);
    }


    public function update(CompanyRequest $request, Company $company): JsonResponse
    {
        $this->authorize('edit', $company);
        return response()->json($company->update($request->validated()));
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Company $company): JsonResponse
    {
        $this->authorize('delete', $company);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $company->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
