<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ServiceCategory\ServicesCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicesCategoryController extends Controller
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->middleware('permission:lihat kategori layanan', ['only' => ['index']]);
        $this->middleware('permission:tambah kategori layanan', ['only' => ['create', 'store']]);
        $this->middleware('permission:update kategori layanan', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hapus kategori layanan', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('pages.master.services-categories.index');
    }

    public function data(): JsonResponse
    {
        $services = ServiceCategory::orderBy('capacity', 'ASC')->paginate(self::$perPage);

        return response()->json($services);
    }

    public function search(Request $request): JsonResponse
    {
        $servicesCategory = ServiceCategory::where('name', 'like', '%'.$request->search.'%')
            ->orWhere('capacity', 'like', '%'.$request->search.'%')
            ->orderBy('capacity', 'ASC')
            ->limit(25)
            ->get();

        return response()->json($servicesCategory);
    }

    public function store(ServicesCategoryRequest $request): JsonResponse
    {
        $services = ServiceCategory::create($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function show(ServiceCategory $serviceCategory): JsonResponse
    {
        return response()->json($serviceCategory);
    }

    public function update(ServicesCategoryRequest $request, ServiceCategory $serviceCategory): JsonResponse
    {
        $serviceCategory->update($request->validated());

        return response()->json([
            'message' => 'data berhasil di update',
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $cabangId = $request->only('data');

        $convertToString = implode(',', $cabangId['data']);
        $integerIDs = array_map('intval', explode(',', $convertToString));

        foreach ($integerIDs as $id) {
            $users = DB::table('users')->whereIn('id', $integerIDs)->get();
            foreach ($users as $user) {
                if ($user->id === $id) {
                    throw new \RuntimeException('Tidak dapat menghapus branch yang memiliki user');
                }
            }
        }
        $services = ServiceCategory::whereIn('id', $integerIDs)->delete();

        return response()->json([
            'message' => 'data berhasil di hapus',
            'data' => $services,
        ]);
    }
}
