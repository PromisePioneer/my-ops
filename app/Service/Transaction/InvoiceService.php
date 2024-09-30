<?php

namespace App\Service\Transaction;

use App\Http\Requests\Transaction\Invoice\InvoiceRequest;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\InvoiceProductService;
use App\Service\Accounts\AccountTransactionService;
use App\Service\HelperService\HandleFileUploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

use function App\Helper\convertToRoman;

class InvoiceService
{
    private const string INVOICE_SENT_DESCRIPTION = 'Invoice dikirim ke %s No. Inv %s';
    private const string CALCULATE_PPN_AFTER_INVOICE_SENT_DESCRIPTION = 'PPN Keluaran Invoice %s No. Inv %s';
    private const string INCLUDE_PPH23_AFTER_INVOICE_PAID_DESCRIPTION = 'Diterima Bupot dari %s No. Inv %s Bupot';
    private const float PPN_RATE = 0.11;
    private const string PAID_STATUS = 'Lunas';
    private static int $perPage = 10;
    private Contact $contact;
    private AccountTransaction $accountTransaction;
    private AccountTransactionService $accountTransactionService;
    private HandleFileUploadService $handleFileUploadService;
    private Account $account;

    public function __construct()
    {
        $this->contact = new Contact();
        $this->accountTransaction = new AccountTransaction();
        $this->accountTransactionService = new AccountTransactionService();
        $this->handleFileUploadService = new HandleFileUploadService();
        $this->account = new Account();
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $data = Invoice::with('contact', 'user')
            ->where('branch_id', $request->user()->branch_id)
            ->latest()
            ->paginate(self::$perPage);

        return self::formattedData($data);
    }


    public function formattedData(LengthAwarePaginator $invoiceData): LengthAwarePaginator
    {
        $data = $invoiceData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'invoice_number' => $item->invoice_number,
                'contact' => $item->contact->company_name,
                'payment_status' => $item->payment_status,
                'file' => $item->file,
                'due_date' => $item->due_date,
                'created_by' => $item->user->name,
                'created_at' => $item->created_at,
            ];
        });

        $invoiceData->setCollection($data);
        return $invoiceData;
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = Invoice::with('contact', 'user')
            ->where('branch_id', $request->user()->branch_id);

        if (!empty($search)) {
            $query->where('invoice_number', 'like', '%'.$search.'%')
                ->where('branch_id', $request->user()->branch_id)
                ->orWhereHas('contact', function ($query) use ($search) {
                    $query->where('company_name', 'like', '%'.$search.'%');
                })
                ->orWhereHas('branch', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })
                ->orWhere('due_date', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%')
                ->orWhere('grand_total', 'like', '%'.$search.'%')
                ->orWhere('created_by', 'like', '%'.$search.'%');
        }

        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }

    /**
     * @throws Throwable
     */
    public function store(InvoiceRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['branch_id'] = $request->user()->branch_id;
            $data['invoice_number'] = self::generateInvoiceNumber($request);
            $data['baa_file'] = $this->handleFileUploadService->upload($request, 'documents/invoice/baa', 'baa_file');
            $data['cooperative_contract_file'] = $this->handleFileUploadService->upload(
                $request,
                'documents/invoice/cooperative-contract',
                'cooperative_contract_file'
            );
            $data['created_by'] = $request->user()->id;
            $data['grand_total'] = $request->grand_total;
            $invoice = Invoice::create($data);
            self::invoiceProductServiceUpdateOrCreate($request, $invoice);
        });
    }

    private static function generateInvoiceNumber(Request $request): string
    {
        $invoice = Invoice::where('branch_id', $request->user()->branch_id)->where(
            'contact_id',
            $request->contact_id
        )->latest()->first();
        $findCompanyName = Contact::where('id', $request->contact_id)->first()->company_name;
        $invoiceDate = convertToRoman(Carbon::parse($request->due_date)->format('m'));
        $invoiceYear = convertToRoman(Carbon::parse($request->due_date)->format('Y'));

        $abbr = explode(' ', $findCompanyName);
        array_shift($abbr);

        $acronym = '';

        foreach ($abbr as $value) {
            $acronym .= mb_substr($value, 0, 1);
        }

        if ($invoice) {
            $convertInvNumberToArray = explode('/', $invoice->invoice_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue.'/'.'INV/'.'MYT-'.$acronym.'/'.$invoiceDate.'/'.$invoiceYear;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue.'/'.'INV/'.'MYT-'.$acronym.'/'.$invoiceDate.'/'.$invoiceYear;
    }

    private static function invoiceProductServiceUpdateOrCreate(InvoiceRequest $request, Invoice $invoice): void
    {
        foreach ($request['data'] as $key => $value) {
            $value['invoice_id'] = $invoice->id;
            $value['total_price'] = $value['qty'] * $value['unit_price'];
            InvoiceProductService::create($value);
        }
    }

    /**
     * @throws Throwable
     */
    public function update(InvoiceRequest $request, Invoice $invoice): void
    {
        DB::transaction(function () use ($request, $invoice) {
            $data = $request->validated();
            $data['branch_id'] = $request->user()->branch_id;
            $data['invoice_number'] = self::generateInvoiceNumber($request);
            $data['baa_file'] = $this->handleFileUploadService->upload(
                $request,
                'documents/invoice/baa',
                'baa_file',
                $invoice->baa_file
            );
            $data['cooperative_contract_file'] = $this->handleFileUploadService->upload(
                $request,
                'documents/invoice/cooperative-contract',
                'cooperative_contract_file',
                $invoice->cooperative_contract_file
            );
            $data['created_by'] = $request->user()->id;
            $data['grand_total'] = $request->grand_total;
            $invoice->update($data);
            InvoiceProductService::whereIn('invoice_id', [$invoice->id])->delete();
            self::invoiceProductServiceUpdateOrCreate($request, $invoice);
        });
    }

    /**
     * @throws Throwable
     */
    public function confirm(Request $request, Invoice $invoice): void
    {
        DB::transaction(function () use ($request, $invoice) {
            $this->accountTransactionAfterInvoiceSent($request, $invoice);
            $this->calculatePPNAccountTransactionAfterInvoiceSent($request, $invoice);
            $invoice->status = 1;
            $invoice->save();
        });
    }

    /**
     * @throws Throwable
     */
    public function accountTransactionAfterInvoiceSent(Request $request, Invoice $invoice): void
    {
        $piutangPelanggan = $this->account->findPiutangPelangganSubAccount();
        $selectedContact = $this->contact->getSelectedData($invoice->contact_id);
        $description = sprintf(
            self::INVOICE_SENT_DESCRIPTION,
            $selectedContact['company_name'],
            $invoice->invoice_number
        );

        DB::transaction(function () use ($request, $piutangPelanggan, $description, $invoice) {
            $this->accountTransactionService->createDebitTransaction(
                $request,
                $description,
                $invoice->grand_total,
                $piutangPelanggan->id
            );
            $this->accountTransactionService->createCreditTransaction(
                $request,
                $description,
                $invoice->grand_total,
                $invoice->account_id
            );
        });
    }

    /**
     * @throws Throwable
     */
    public function calculatePPNAccountTransactionAfterInvoiceSent(Request $request, Invoice $invoice): void
    {
        $piutangPelanggan = $this->account->findPiutangPelangganSubAccount();
        $selectedContact = $this->contact->getSelectedData($invoice->contact_id);
        $totalPlusTax = self::PPN_RATE * $invoice['grand_total'];
        $description = sprintf(
            self::CALCULATE_PPN_AFTER_INVOICE_SENT_DESCRIPTION,
            $selectedContact['company_name'],
            $invoice->invoice_number
        );
        $ppn = $this->account->findPPNSubAccount();

        DB::transaction(function () use ($request, $ppn, $piutangPelanggan, $description, $totalPlusTax) {
            $this->accountTransactionService->createDebitTransaction(
                $request,
                $description,
                $totalPlusTax,
                $piutangPelanggan->id
            );
            $this->accountTransactionService->createCreditTransaction(
                $request,
                $description,
                $totalPlusTax,
                $ppn->id
            );
        });
    }

    /**
     * @throws Throwable
     */
    public function updatePaymentStatus(Request $request, Invoice $invoice): void
    {
        $selectedContact = $this->contact->getSelectedData($invoice->contact_id);
        $rekeningMayatamaPusat = $this->account->findRekeningMayatamaPusatSubAccount();
        $piutangPelanggan = $this->account->findPiutangPelangganSubAccount();
        $description = sprintf(
            self::CALCULATE_PPN_AFTER_INVOICE_SENT_DESCRIPTION,
            $selectedContact['company_name'],
            $invoice->invoice_number
        );
        $currentPPN = $this->accountTransaction->getCurrentPPNOnInvoice($description);

        DB::transaction(function () use (
            $request,
            $invoice,
            $selectedContact,
            $description,
            $rekeningMayatamaPusat,
            $piutangPelanggan,
            $currentPPN
        ) {
            if ($request->pph23_form) {
                $this->includePPh23AfterInvoicePaid($request, $invoice, $selectedContact['company_name']);
            }
            $totalAfterInvoicePaid = $invoice->grand_total + $currentPPN->credit - ($request->pph23_form ?? 0);
            $this->accountTransactionService->createDebitTransaction(
                $request,
                $description,
                $totalAfterInvoicePaid,
                $rekeningMayatamaPusat->id
            );
            $this->accountTransactionService->createCreditTransaction(
                $request,
                $description,
                $totalAfterInvoicePaid,
                $piutangPelanggan->id
            );
            $invoice->payment_status = self::PAID_STATUS;
            $invoice->save();
        });
    }

    /**
     * @throws Throwable
     */
    public function includePPh23AfterInvoicePaid(Request $request, Invoice $invoice, string $companyName): void
    {
        $pph23Account = $this->account->findPPH23SubAccount();
        $piutangPelanggan = $this->account->findPiutangPelangganSubAccount();
        $description = sprintf(
            self::INCLUDE_PPH23_AFTER_INVOICE_PAID_DESCRIPTION,
            $companyName,
            $invoice->invoice_number
        );

        DB::transaction(function () use ($pph23Account, $piutangPelanggan, $description, $request) {
            $this->accountTransactionService->createDebitTransaction(
                $request,
                $description,
                $request->pph23_form,
                $pph23Account->id
            );
            $this->accountTransactionService->createCreditTransaction(
                $request,
                $description,
                $request->pph23_form,
                $piutangPelanggan->id
            );
        });
    }
}
