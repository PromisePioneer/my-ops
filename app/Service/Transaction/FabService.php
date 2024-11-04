<?php

namespace App\Service\Transaction;

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Contact;
use App\Models\Fab;
use App\Models\FabHasServiceCategories;
use App\Models\OfferingLetter;
use App\Models\SubAccount;
use App\Service\Accounts\AccountTransactionService;
use App\Service\HelperService\HandleFileUploadService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

use function App\Helper\convertToRoman;
use function App\Helper\formatDate;

class FabService
{

    private const string FAB_SENT_DESCRIPTION = 'FAB telah terbit ke %s No. Fab %s';
    private static int $perPage = 10;
    private HandleFileUploadService $handleFileUploadService;
    private Contact $contact;
    private AccountTransactionService $accountTransactionService;
    private Account $account;


    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService();
        $this->accountTransactionService = new AccountTransactionService();
        $this->contact = new Contact();
        $this->account = new Account();
    }


    public function generateFABNumber(Request $request): string
    {
        $fab = Fab::where('branch_id', $request->user()->branch_id)
            ->where('contact_id', $request->contact_id)
            ->latest()
            ->first();

        $companyCode = Contact::where('id', $request->contact_id)->first()->company_code;
        $month = convertToRoman(Carbon::parse($request->due_date)->format('m'));
        $year = Carbon::parse($request->due_date)->format('Y');


        if ($fab) {
            $convertInvNumberToArray = explode('/', $fab->fab_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '/' . 'SPH/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '/' . 'SPH/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
    }


    public function data(): LengthAwarePaginator
    {
        $data = Fab::with('branch', 'contact', 'user')
            ->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $fabData): LengthAwarePaginator
    {
        $data = $fabData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->fab_number,
                'company_name' => $item->contact->company_name,
                'subscription_status' => $item->subscription_status,
                'file' => $item->file,
                'date' => formatDate($item->date),
                'created_by' => $item->user->name,
            ];
        });

        $fabData->setCollection($data);
        return $fabData;
    }

    public function filterByBranch(int $branchId): LengthAwarePaginator
    {
        return Fab::where('branch_id', $branchId)->paginate(self::$perPage);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = Fab::with('contact', 'branch')
            ->where('subscription_status', 'like', '%' . $search . '%')
            ->whereHas('branch', function ($query) use ($request, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('contact', function ($query) use ($request, $search) {
                $query->where('full_name', 'like', '%' . $search . '%');
                $query->where('company_name', 'like', '%' . $search . '%');
            })->paginate(self::$perPage);

        return self::formattedData($query);
    }


    /**
     * @throws Throwable
     */
    public function store($request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['created_by'] = $request->user()->id;
            $fab = Fab::create($data);
            $this->fabHasServiceCategoriesStoreOrUpdate($request, $fab);
        });
    }

    private function fabHasServiceCategoriesStoreOrUpdate($request, $fab): void
    {
        foreach ($request['data'] as $key => $value) {
            $value['fab_id'] = $fab->id;
            FabHasServiceCategories::create($value);
        }
    }

    /**
     * @throws Throwable
     */
    public function update($request, $fab): void
    {
        DB::transaction(function () use ($request, $fab) {
            $data = $request->validated();
            $data['branch_id'] = $request->user()->branch_id;
            $data['file'] = $this->handleFileUploadService->upload($request, 'documents/fab', 'file', $fab->file);
            $data['created_by'] = $request->user()->id;
            $fab->update($data);
            FabHasServiceCategories::whereIn('fab_id', [$fab->id])->delete();
            $this->fabHasServiceCategoriesStoreOrUpdate($request, $fab);
        });
    }

    /**
     * @throws Throwable
     */
    public function confirm($fab, $fabService): void
    {
        $contact = $this->contact->getSelectedData($fab->contact_id);
        $description = sprintf(self::FAB_SENT_DESCRIPTION, $contact['company_name'], $fab->fab_number);
        $debitAccount = $this->account->getPenjualanAtauPendapatanJasaLainnyaAccount();
        $creditAccount = $this->account->findPiutangPelangganSubAccount();

        DB::transaction(function () use ($description, $fab, $fabService, $debitAccount, $creditAccount) {
            $this->accountTransactionService->createDebitTransaction(
                $fab->branch_id,
                $description,
                $fabService->sum('total_price'),
                $debitAccount->id
            );
            $this->accountTransactionService->createCreditTransaction(
                $fab->branch_id,
                $description,
                $fabService->sum('total_price'),
                $creditAccount->id
            );
            $fab->status_confirmation = true;
            $fab->save();
        });
    }

    public function jurnalEntry(Fab $fab): JsonResponse
    {
        $jurnalEntry = AccountTransaction::with('account')
            ->where('description', 'like', '%' . $fab->fab_number . '%')
            ->get()
            ->map(function ($query) {
                return [
                    'id' => $query->id,
                    'type' => $query->type,
                    'account_name' => $query->account->code . ' ' . $query->account->name,
                    'amount' => 'Rp.' . number_format($query->amount, 2),
                ];
            });

        return response()->json($jurnalEntry);
    }
}
