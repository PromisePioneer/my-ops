<?php

namespace App\Support;

use AllowDynamicProperties;
use App\Models\Area;
use App\Models\PSB;
use App\Support\HelperService\FinancialClosePeriodService;
use Illuminate\Support\Collection;

#[AllowDynamicProperties] class VendorPayrollService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }


    public function data(): Collection
    {
        $userData = Area::with('areaHasUser', 'areaHasUser.user', 'areaHasUser.area')->whereHas('department', function ($query) {
            $query->where('name', 'Vendor');
        })->get();
        return self::formattedData($userData);
    }


    public function formattedData(Collection $data): Collection
    {
        $startDate = $this->financialClosePeriodService->vendorPayrollPeriodStartDate();
        $endDate = $this->financialClosePeriodService->vendorPayrollPeriodEndDate();
        return $data->map(function ($item) use($startDate, $endDate) {
            $psb = PSB::where('area_id', $item->id)
                ->whereBetween('active_date', [$startDate, $endDate])
                ->count();
            $areaCount = $item->areaHasUser->count();
            $totalSalary = $areaCount > 0 ? 95000 / $areaCount : 0;

            return [
                'id' => $item->id,
                'name' => $item->name,
                'roles' => $item->roles[0]?->name ?? 'N/A',
                'branch' => $item->branch?->name ?? 'N/A',
                'psb' => $psb,
                'vendors' => $item->areaHasUser
                    ->map(function ($item) use ($totalSalary, $psb) {
                        return [
                            'user' => $item->user->name,
                            'total_salary' => 'Rp.' . number_format($totalSalary * $psb)
                        ];
                    })
            ];
        });
    }


    public function psbData(): Collection
    {
        $psb = PSB::with('broadbandPacket', 'branch', 'user')->get();
        return self::psbFormattedData($psb);
    }


    private static function psbFormattedData($data): Collection
    {
        return $data->map(function ($query) {
            return [
                'id' => $query->id,
                'date' => $query->date,
                'vendor_name' => $query->user->name,
                'regis_date' => $query->regis_date,
                'customer_name' => $query->customer_name,
            ];
        });
    }
}
