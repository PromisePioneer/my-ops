<?php

namespace App\Http\Controllers\Master\General;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Service\Master\General\Company\CompanyRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class CompanyController extends Controller
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->companyRepository = new CompanyRepository();

    }
    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Company::class);
        return view('pages.general-master-data.company.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Company::class);
        $company = Company::paginate(self::$perPage);
        return response()->json($company);
    }


    /**
     * @throws AuthorizationException
     */
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


    /**
     * @throws AuthorizationException
     */
    public function store(CompanyRequest $request): JsonResponse
    {
        $this->authorize('create', Company::class);
        return response()->json(Company::create($request->validated()));
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(Company $company): JsonResponse
    {
        $this->authorize('edit', $company);
        return response()->json($company);
    }


    /**
     * @throws AuthorizationException
     */
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


    public function getCompanies()
    {
        $companies = $this->companyRepository->getCompanies()->get();
        return $companies->map(function ($company) {
            return [
                'id' => $company->id,
                'text' => $company->name
            ];
        });
    }

    public function selectedCompany(Company $company): array
    {
        $company = $this->companyRepository->selectedCompany($company->id);
        return [
            'id' => $company->id,
            'name' => $company->name
        ];
    }
}
