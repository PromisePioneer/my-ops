<?php

namespace App\Service;

use App\Models\Contact;
use App\Models\Fab;
use App\Models\FabService;
use App\Models\SubAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FabServices
{
    private const FAB_SENT_DESCRIPTION = 'FAB telah terbit ke %s No. Fab %s';

    private HandleFileUploadService $handleFileUploadService;

    private Contact $contact;

    private AccountTransactionService $accountTransactionService;

    private SubAccount $subAccount;

    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService;
        $this->accountTransactionService = new AccountTransactionService;
        $this->subAccount = new SubAccount;
        $this->contact = new Contact;
    }

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

    public function update($request, $fab): void
    {

        DB::transaction(function () use ($request, $fab) {

            $data = $request->validated();
            $data['branch_id'] = $request->user()->branch_id;
            $data['file'] = $this->handleFileUploadService->upload($request, 'documents/fab', 'file');
            $data['created_by'] = $request->user()->id;
            $fab->update($data);
            FabService::whereIn('fab_id', [$fab->id])->delete();
            $this->fabServiceStoreOrUpdate($request, $fab);
        });
    }

    private function fabServiceStoreOrUpdate($request, $fab): void
    {
        foreach ($request['data'] as $key => $value) {
            $value['fab_id'] = $fab->id;
            FabService::create($value);
        }
    }

    public function confirm($fab, $fabService): void
    {
        $contact = $this->contact->getSelectedData($fab->contact_id);
        $description = sprintf(self::FAB_SENT_DESCRIPTION, $contact['company_name'], $fab->fab_number);
        $debitAccount = $this->subAccount->getPenjualanAtauPendapatanJasaLainnyaSubAccount($fab->branch_id);
        $creditAccount = $this->subAccount->findPiutangPelangganSubAccount($fab->branch_id);

        DB::transaction(function () use ($description, $fab, $fabService, $debitAccount, $creditAccount) {
            $this->accountTransactionService->createDebitTransaction($description, $fabService->sum('total_price'), null, $debitAccount->id);
            $this->accountTransactionService->createCreditTransaction($description, $fabService->sum('total_price'), null, $creditAccount->id);
            $fab->status_confirmation = true;
            $fab->save();
        });
    }

    public function jurnalEntry(Fab $fab): JsonResponse
    {
        $jurnalEntry = DB::table('account_transactions')
            ->join('sub_accounts', 'sub_accounts.id', '=', 'account_transactions.sub_account_id')
            ->where('account_transactions.description', 'like', '%'.$fab->fab_number.'%')
            ->select('account_transactions.*', 'sub_accounts.name', 'sub_accounts.code', 'sub_accounts.id as sub_account_id')
            ->get();

        return response()->json($jurnalEntry);
    }
}
