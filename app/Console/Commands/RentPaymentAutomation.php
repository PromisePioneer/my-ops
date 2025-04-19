<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\InitialJournal;
use App\Models\JournalAdjustment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RentPaymentAutomation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rent:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rent Payment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        JournalAdjustment::join('initial_journal', 'initial_journal.id', '=', 'journal_adjustment.initial_journal_id')
            ->join('sub_accounts', 'sub_accounts.id', '=', 'initial_journal.sub_account_debit')
            ->join('accounts', 'accounts.id', '=', 'sub_accounts.account_id')
            ->where('sub_accounts.code', '114-01')
            ->select('journal_adjustment.*')
            ->get()
            ->each(function ($payment) {
                DB::beginTransaction();
                try {
                    $bebanSewaAccount = Account::where('code', '510')->get();
                    $sewaDibayarDimukaAccount = Account::join('sub_accounts', 'accounts.id', '=', 'sub_accounts.account_id')
                        ->where('sub_accounts.code', '114-01')->get();

                    foreach ($bebanSewaAccount as $beban) {

                        AccountTransaction::create([
                            'date' => date('Y-m-d'),
                            'account_id' => $beban->id,
                            'description' => $payment->description,
                            'debit' => $payment->total_payment_per_month,
                            'credit' => 0,
                        ]);
                    }

                    foreach ($sewaDibayarDimukaAccount as $sewaDibayar) {
                        AccountTransaction::create([
                            'date' => date('Y-m-d'),
                            'sub_account_id' => $sewaDibayar->id,
                            'description' => $payment->description,
                            'debit' => 0,
                            'credit' => $payment->total_payment_per_month,
                        ]);

                    }

                    $initialJournal = InitialJournal::where('id', '=', $payment->initial_journal_id)->first();
                    $initialJournal->initial_payment = $initialJournal->initial_payment - $payment->total_payment_per_month;
                    $initialJournal->save();

                    DB::commit();

                    return [
                        'success' => true,
                        'message' => 'data berhasil disimpan',
                    ];
                } catch (\Exception $e) {
                    DB::rollBack();

                    return [
                        'success' => false,
                        'message' => $e->getMessage(),
                    ];
                }
            });
    }
}
