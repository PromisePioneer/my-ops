<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\User;
use App\Notifications\InvoiceDueDate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendInvoiceDueReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:due-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder notification for invoices due in 7 days';

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
     *
     * @return int
     */
    public function handle(): void
    {

        $expDate = Carbon::now()->subDays(7);
        Invoice::whereDate('due_date', '>', $expDate)
            ->get()
            ->each(function ($invoice) {
                $users = User::where('branch_id', $invoice->branch_id)->get();
                Notification::sendNow($users, new InvoiceDueDate($invoice));
            });
    }
}
