<?php

namespace App\Http\Controllers\JournalAdjustment;

use App\Http\Controllers\Controller;
use App\Http\Requests\JournalAdjustment\AdjustmentRequest;
use App\Models\InitialJournal;
use App\Models\JournalAdjustment;
use App\Service\JournalAdjustmentServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JournalAdjustmentController extends Controller
{
    private $journalAdjustmentService;

    public function __construct()
    {
        $this->journalAdjustmentService = new JournalAdjustmentServices;
    }

    public function index()
    {
        return view('pages.journal-adjustment.adjustment.index');
    }

    public function data(): JsonResponse
    {
        $journalAdjustment = JournalAdjustment::with('initialJournal')->paginate(10);

        return response()->json($journalAdjustment);
    }

    public function initialJournalData(Request $request): JsonResponse
    {
        $initialJournal = $this->journalAdjustmentService->initialJournalData($request);

        return response()->json($initialJournal);
    }

    public function store(AdjustmentRequest $request): JsonResponse
    {
        JournalAdjustment::create([
            'initial_journal_id' => $request->initial_journal_id,
            'payment_date' => $request->payment_date,
            'description' => $request->description,
            'total_payment_per_month' => $request->total_payment_per_month,
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(JournalAdjustment $journalAdjustment): JsonResponse
    {
        return response()->json($journalAdjustment);
    }

    public function selectedInitialJournal(JournalAdjustment $journalAdjustment): array
    {
        $initialJournal = InitialJournal::where('id', $journalAdjustment->initial_journal_id)->first();

        return [
            'id' => $initialJournal->id,
            'name' => $initialJournal->description,
        ];
    }

    public function update(AdjustmentRequest $request, JournalAdjustment $journalAdjustment): JsonResponse
    {
        $journalAdjustment->update([
            'initial_journal_id' => $request->initial_journal_id,
            'payment_date' => $request->payment_date,
            'description' => $request->description,
            'total_payment_per_month' => $request->total_payment_per_month,
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    public function destroy(JournalAdjustment $journalAdjustment): JsonResponse
    {
        return response()->json($journalAdjustment->delete());
    }
}
