<?php


namespace App\Service;

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\InitialJournal;
use App\Models\JournalAdjustment;
use Exception;
use Illuminate\Support\Facades\DB;

class JournalAdjustmentServices
{

    public function initialJournalData($request): array
    {
        if ($request->search === '') {
            $account = InitialJournal::orderBy('description', 'asc')
                ->limit(10)
                ->get();
        } else {
            $account = InitialJournal::where('description', 'like', '%' . $request->search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($account as $c) {
            $response[] = array(
                "id" => $c->id,
                "text" => $c->description
            );
        }

        return $response;
    }

    public function store($request): array
    {
        DB::beginTransaction();
        try {

            JournalAdjustment::create([
                'initial_journal_id' => $request->initial_journal_id,
                'payment_date' => $request->payment_date,
                'description' => $request->description,
                'total_payment_per_month' => $request->total_payment_per_month,
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => 'data berhasil disimpan',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
