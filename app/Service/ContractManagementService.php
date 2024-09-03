<?php

namespace App\Service;

use App\Models\ContractManagement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

use function App\Helper\convertToRoman;

class ContractManagementService
{


    private static int $perPage = 10;
    private ContractManagement $contractManagement;

    public function __construct()
    {
        $this->contractManagement = new ContractManagement();
    }

    public function paginatedData(): LengthAwarePaginator
    {
        $contractManagement = $this->contractManagement->getContractManagement()->paginate(self::$perPage);
        self::formattedData($contractManagement);
        return $contractManagement;
    }


    private static function formattedData($contractManagementData): void
    {
        $data = $contractManagementData->getCollection()->map(function ($contractManagement) {
            $startDate = Carbon::parse($contractManagement?->contract?->start_date)->format('Y-m-d');
            $endDate = Carbon::parse($contractManagement?->contract?->end_date)->format('Y-m-d');
            $expired = false;
            if ($contractManagement->contract === null) {
                $startDate = Carbon::parse($contractManagement->join_date);
                $endDate = Carbon::parse($contractManagement->join_date)->addYears(1);
            }


            if (Carbon::parse($endDate) < Carbon::now()) {
                $expired = true;
            }

            return [
                'id' => $contractManagement->id,
                'name' => "$contractManagement->name",
                'contract_date' => Carbon::parse($startDate)->format('d/m/Y')." - ".Carbon::parse($endDate)->format('d/m/Y'),
                'expired' => $expired,

            ];
        })->values();

        $contractManagementData->setCollection($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->contractManagement->getContractManagement();


        if (!empty($search)) {
            $query->where('name', 'like', "%$search%");
            $query->orWhere('nip', 'like', "%$search%");
        }

        $contract = $query->paginate(self::$perPage);
        self::formattedData($contract);
        return $contract;
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $contract = $this->contractManagement->getContractManagement();
        $contract->where('branch_id', $request->branch_id);

        if ($request->year) {
            $contract->whereHas('contract', function ($query) use ($request) {
                $query->whereYear('end_date', $request->year);
                $query->orWhereYear('start_date', $request->year);
            });
        }

        if ($request->month) {
            $contract->whereHas('contract', function ($query) use ($request) {
                $query->whereMonth('end_date', $request->month);
                $query->orWhereMonth('start_date', $request->year);
            });
        }

        if ($request->month && $request->year) {
            $contract->orWhereHas('contract', function ($query) use ($request) {
                $query->whereDate('start_date', Carbon::parse('01-'.$request->month.'-'.$request->year));
                $query->orWhereDate('end_date', Carbon::parse('01-'.$request->month.'-'.$request->year));
            });
        }

        $paginator = $contract->paginate(self::$perPage)->onEachSide(1);

        self::formattedData($paginator);

        return $paginator;
    }

    public function generateContractNumber($date, $user): string
    {
        $userData = User::with('branch')->where('id', $user->id)->first();
        $isContractUserExist = ContractManagement::where('user_id', $user->id)->first();
        $branchCode = $userData->branch?->code ?? 100;


        $contractRenewalMonth = convertToRoman(Carbon::parse($date)->format('m'));
        $contractRenewalYear = Carbon::parse($date)->format('Y');

        if ($userData && $isContractUserExist) {
            $convertInvNumberToArray = explode('/', $isContractUserExist->contract_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int) $startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $branchCode.'-'.$startValue.'/MY-PKWT/'.$contractRenewalMonth.'/'.$contractRenewalYear;
        }

        $startingNumber = '000';
        $startValue = str_pad((int) $startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $branchCode.'-'.$startValue.'/MY-PKWT/'.$contractRenewalMonth.'/'.$contractRenewalYear;
    }


}