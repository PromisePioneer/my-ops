<?php

namespace App\Support\FpDevice\Repository;

use AllowDynamicProperties;
use App\Models\FpDevice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class FpDeviceRepository
{
    public function __construct()
    {
        $this->fpDevice = new FpDevice();
    }


    public function data(Request $request): Builder
    {
        return $this->fpDevice->with(['branch', 'attendanceJobProgress' => function ($query) {
            $query->latest('created_at');
        }])->when($request->user()->hasRole('Branch Manager'), function ($query) use ($request) {
            $query->where('branch_id', $request->user()->branch_id);
        });
    }
}
