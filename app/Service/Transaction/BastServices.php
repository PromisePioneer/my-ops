<?php

namespace App\Service\Transaction;

use App\Models\Bast;
use App\Models\BastProduct;
use App\Service\HelperService\HandleFileUploadService;
use Illuminate\Support\Facades\DB;
use Throwable;

class BastServices
{
    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService();
    }

    /**
     * @throws Throwable
     */
    public function store($request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['branch_id'] = $request->user()->branch_id;
            $data['file'] = $this->handleFileUploadService->upload($request, 'documents/bast', 'file');
            $data['created_by'] = $request->user()->id;
            $bast = Bast::create($data);
            BastProduct::whereIn('bast_id', [$bast->id])->delete();
            $this->bastProductCreateOrUpdate($request, $bast);
        });
    }

    public function bastProductCreateOrUpdate($request, $bast): void
    {
        foreach ($request['data'] as $key => $value) {
            $value['bast_id'] = $bast->id;
            BastProduct::create($value);
        }
    }

    /**
     * @throws Throwable
     */
    public function update($request, $bast): void
    {
        DB::transaction(function () use ($request, $bast) {
            $data = $request->validated();
            $data['branch_id'] = $request->user()->branch_id;
            $data['file'] = $this->handleFileUploadService->upload($request, 'documents/bast', 'file', $bast->file);
            $data['created_by'] = $request->user()->id;
            $bast->update($data);
            BastProduct::whereIn('bast_id', [$bast->id])->delete();
            $this->bastProductCreateOrUpdate($request, $bast);
        });
    }
}
