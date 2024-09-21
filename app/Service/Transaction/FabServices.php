<?php

namespace App\Service\Transaction;

use App\Models\Contact;
use App\Models\Fab;
use App\Models\SubAccount;
use App\Service\Accounts\AccountTransactionService;
use App\Service\HelperService\HandleFileUploadService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

use function App\Helper\formatDate;

class FabServices
{

    private const string FAB_SENT_DESCRIPTION = 'FAB telah terbit ke %s No. Fab %s';
    private static int $perPage = 10;
    private HandleFileUploadService $handleFileUploadService;
    private Contact $contact;
    private AccountTransactionService $accountTransactionService;
    private SubAccount $subAccount;


    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService();
        $this->accountTransactionService = new AccountTransactionService();
        $this->subAccount = new SubAccount();
        $this->contact = new Contact();
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $data = Fab::with('branch', 'contact', 'user')
            ->where('branch_id', $request->user()->branch_id)
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
                'branch' => $item->branch->name ?? '-',
                'subscription_status' => $item->subscription_status,
                'file' => $item->file,
                'date' => formatDate($item->date),
                'created_by' => $item->user->name,
            ];
        });

        $fabData->setCollection($data);
        return $fabData;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = Fab::with('contact', 'branch')
            ->where('subscription_status', 'like', '%'.$search.'%')
            ->whereHas('branch', function ($query) use ($request, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })->orWhereHas('contact', function ($query) use ($request, $search) {
                $query->where('full_name', 'like', '%'.$search.'%');
                $query->where('company_name', 'like', '%'.$search.'%');
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
            $data['branch_id'] = $request->user()->branch_id;
            $data['file'] = $this->handleFileUploadService->upload($request, 'documents/fab', 'file');
            $data['created_by'] = $request->user()->id;
            $fab = Fab::create($data);
            $this->fabServiceStoreOrUpdate($request, $fab);
        });
    }

    private function fabServiceStoreOrUpdate($request, $fab): void
    {
        foreach ($request['data'] as $key => $value) {
            $value['fab_id'] = $fab->id;
            FabServices::create($value);
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
            $data['file'] = $this->handleFileUploadService->upload($request, 'documents/fab', 'file');
            $data['created_by'] = $request->user()->id;
            $fab->update($data);
            FabServices::whereIn('fab_id', [$fab->id])->delete();
            $this->fabServiceStoreOrUpdate($request, $fab);
        });
    }

    /**
     * @throws Throwable
     */
    public function confirm($fab, $fabService): void
    {
        $contact = $this->contact->getSelectedData($fab->contact_id);
        $description = sprintf(self::FAB_SENT_DESCRIPTION, $contact['company_name'], $fab->fab_number);
        $debitAccount = $this->subAccount->getPenjualanAtauPendapatanJasaLainnyaSubAccount($fab->branch_id);
        $creditAccount = $this->subAccount->findPiutangPelangganSubAccount($fab->branch_id);

        DB::transaction(function () use ($description, $fab, $fabService, $debitAccount, $creditAccount) {
            $this->accountTransactionService->createDebitTransaction(
                $description,
                $fabService->sum('total_price'),
                null,
                $debitAccount->id
            );
            $this->accountTransactionService->createCreditTransaction(
                $description,
                $fabService->sum('total_price'),
                null,
                $creditAccount->id
            );
            $fab->status_confirmation = true;
            $fab->save();
        });
    }

    public function jurnalEntry(Fab $fab): JsonResponse
    {
        $jurnalEntry = DB::table('account_transactions')
            ->join('sub_accounts', 'sub_accounts.id', '=', 'account_transactions.sub_account_id')
            ->where('account_transactions.description', 'like', '%'.$fab->fab_number.'%')
            ->select(
                'account_transactions.*',
                'sub_accounts.name',
                'sub_accounts.code',
                'sub_accounts.id as sub_account_id'
            )->get();

        return response()->json($jurnalEntry);
    }
}
