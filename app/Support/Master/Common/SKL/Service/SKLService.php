<?php

namespace App\Support\Master\Common\SKL\Service;

use AllowDynamicProperties;
use App\Http\Requests\SKLRequest;
use App\Models\Master\Common\SKL;
use App\Support\Master\Common\SKL\Repository\SKLRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class SKLService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->sklRepository = new SKLRepository();
        $this->skl = new SKL();
    }

    public function data(): LengthAwarePaginator
    {
        $data = $this->sklRepository->getSKL()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = SKL::search($search)->query(function ($query) {
            $query->orderBy('name');
        })->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $skl): LengthAwarePaginator
    {
        $data = $skl->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });

        $skl->setCollection($data);
        return $skl;
    }


    public function store(SKLRequest $request): SKL
    {
        return $this->skl->create($request->validated());
    }


    public function update(SKLRequest $request, SKL $skl): bool
    {
        return $skl->update($request->validated());
    }


    public function destroy(Request $request, SKL $skl): bool
    {

        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        return $skl->whereIn('id', $explodeID)->delete();
    }


    public function archivedData(): LengthAwarePaginator
    {
        $data = $this->sklRepository->getTrashedSKL()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function archivedSearch(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = $this->skl::search($search)->onlyTrashed()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function restore(Request $request, SKL $skl): bool
    {

        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        return $skl->whereIn('id', $explodeID)->restore();
    }


    public function forceDelete(Request $request, SKL $skl): bool
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        return $skl->whereIn('id', $explodeID)->forceDelete();
    }
}
