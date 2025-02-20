<?php

namespace App\Service\User\ContractManagement;

use App\Models\ContractManagement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use function App\Helper\convertToRoman;
use function App\Helper\formatDate;

class ContractManagementService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->contractManagement = new ContractManagement();
    }

    public function data(): LengthAwarePaginator
    {
        $contractManagement = ContractManagement::with('user')->paginate(self::$perPage);
        return self::formattedData($contractManagement);
    }


    private static function formattedData(LengthAwarePaginator $contractManagementData): LengthAwarePaginator
    {
        $data = $contractManagementData->getCollection()
            ->map(function ($contractManagement) {

            return [
                'id' => $contractManagement->id,
                'name' => $contractManagement->user->name,
                'user_id' => $contractManagement->user_id,
                'contract_date' => formatDate($contractManagement->start_date) . ' - ' . formatDate($contractManagement->end_date),
                'expired' => Carbon::parse($contractManagement->end_date)->greaterThan(Carbon::now()),
            ];
        })->values();

        $contractManagementData->setCollection($data);
        return $contractManagementData;
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = ContractManagement::search($search)->query(function ($query) {
            $query->join('users', 'users.id', '=', 'contract_management.user_id');
        })->paginate(self::$perPage);
        return self::formattedData($query);
    }


    public function filter(Request $request): LengthAwarePaginator
    {

        $query = ContractManagement::with('user');
        $contract = ContractQueryFilter::apply($query, $request)->paginate(self::$perPage)->onEachSide(1);

        return self::formattedData($contract);
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
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $branchCode.'-'.$startValue.'/MY-PKWT/'.$contractRenewalMonth.'/'.$contractRenewalYear;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $branchCode.'-'.$startValue.'/MY-PKWT/'.$contractRenewalMonth.'/'.$contractRenewalYear;
    }


}
