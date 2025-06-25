<?php

namespace App\Support\FpDevice\Repository;

use AllowDynamicProperties;
use App\Models\FpDevice;
use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class FpDeviceRepository
{
    public function __construct()
    {
        $this->fpDevice = new FpDevice();
        $this->branch = new Branch();
    }


    public function data(Request $request): Builder
    {
        return $this->fpDevice->with(['branch', 'attendanceJobProgress' => function ($query) {
            $query->latest('created_at');
        }])->when($request->user()->hasRole('Branch Manager'), function ($query) use ($request) {
            $allowedBranchId = $this->branch->query()
                ->with('children')
                ->find($request->user()->branch_id)
                ->children
                ->pluck('id')
                ->toArray();
            $query->whereIn('branch_id', $allowedBranchId);
        });
    }
}
