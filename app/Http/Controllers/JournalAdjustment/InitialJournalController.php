<?php

namespace App\Http\Controllers\JournalAdjustment;

use App\Http\Controllers\Controller;
use App\Http\Requests\JournalAdjustment\InitialJournalRequest;
use App\Models\InitialJournal;
use App\Models\SubAccount;
use App\Service\InitialJournalServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InitialJournalController extends Controller
{
    private InitialJournalServices $initialJournal;

    public function __construct()
    {
        $this->initialJournal = new InitialJournalServices();
    }

    public function index(): View
    {
        return view('pages.journal-adjustment.initial-journal.index');
    }

    public function data(): JsonResponse
    {
        return response()->json(InitialJournal::with('subAccountDebit', 'subAccountCredit')->paginate(10));
    }

    public function selectAccountDebit(Request $request): JsonResponse
    {
        $account = $this->initialJournal->selectAccountDebit($request);

        return response()->json($account);
    }

    public function selectAccountCredit(Request $request): JsonResponse
    {
        $account = $this->initialJournal->selectAccountCredit($request);

        return response()->json($account);
    }

    public function search(Request $request): JsonResponse
    {
        $query = InitialJournal::with('subAccountDebit', 'subAccountCredit')->where(
            'description',
            'like',
            '%'.$request->search.'%'
        )
            ->orWhere('initial_payment', 'like', '%'.$request->search.'%')
            ->get();

        return response()->json($query);
    }

    public function store(InitialJournalRequest $request): JsonResponse
    {
        InitialJournal::create([
            'description' => $request->description,
            'sub_account_debit' => $request->sub_account_debit,
            'sub_account_credit' => $request->sub_account_credit,
            'initial_payment' => $request->initial_payment,
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(InitialJournal $initialJournal): JsonResponse
    {
        return response()->json($initialJournal);
    }

    public function selectedDebitAccount(InitialJournal $initialJournal): array
    {
        $subAccount = SubAccount::where('id', $initialJournal->sub_account_debit)->first();

        return [
            'id' => $subAccount->id,
            'name' => $subAccount->name,
        ];
    }

    public function selectedCreditAccount(InitialJournal $initialJournal): array
    {
        $subAccount = SubAccount::where('id', $initialJournal->sub_account_credit)->first();

        return [
            'id' => $subAccount->id,
            'name' => $subAccount->name,
        ];
    }

    public function update(InitialJournalRequest $request, InitialJournal $initialJournal): JsonResponse
    {
        $initialJournal->update([
            'description' => $request->description,
            'sub_account_debit' => $request->sub_account_debit,
            'sub_account_credit' => $request->sub_account_credit,
            'initial_payment' => $request->initial_payment,
        ]);

        return response()->json([
            'message' => 'data berhasil diubah',
        ]);
    }

    public function confirm(InitialJournal $initialJournal): JsonResponse
    {
        $response = $this->initialJournal->confirm($initialJournal);

        if ($response['success']) {
            return response()->json([
                'message' => $response['message'],
            ], 200);
        }

        return response()->json([
            'message' => $response['message'],
        ], 500);
    }

    public function destroy(InitialJournal $initialJournal): JsonResponse
    {
        if ($initialJournal->status_confirmation === 0) {
            return response()->json($initialJournal->delete());
        }

        return response()->json([
            'message' => 'data gagal dihapus',
        ], 500);
    }
}
