<?php

namespace App\Service;

use App\Http\Requests\Transaction\Invoice\InvoiceRequest;
use App\Models\AccountTransaction;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\InvoiceProductService;
use App\Models\SubAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helper\convertToRoman;

class InvoiceService
{
    private const string INVOICE_SENT_DESCRIPTION = 'Invoice dikirim ke %s No. Inv %s';

    private const string CALCULATE_PPN_AFTER_INVOICE_SENT_DESCRIPTION = 'PPN Keluaran Invoice %s No. Inv %s';

    private const string INCLUDE_PPH23_AFTER_INVOICE_PAID_DESCRIPTION = 'Diterima Bupot dari %s No. Inv %s Bupot';

    private const float PPN_RATE = 0.11;

    private const string PAID_STATUS = 'Lunas';

    private SubAccount $subAccount;

    private Contact $contact;

    private AccountTransaction $accountTransaction;

    private AccountTransactionService $accountTransactionService;

    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->subAccount = new SubAccount();
        $this->contact = new Contact();
        $this->accountTransaction = new AccountTransaction();
        $this->accountTransactionService = new AccountTransactionService();
        $this->handleFileUploadService = new HandleFileUploadService();
    }

    public function store(InvoiceRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['branch_id'] = $request->user()->branch_id;
            $data['invoice_number'] = self::generateInvoiceNumber($request);
            $data['baa_file'] = $this->handleFileUploadService->upload($request, 'documents/invoice/baa', 'baa_file');
            $data['cooperative_contract_file'] = $this->handleFileUploadService->upload($request,
                'documents/invoice/cooperative-contract', 'cooperative_contract_file');
            $data['created_by'] = $request->user()->id;
            $data['grand_total'] = $request->grand_total;
            $invoice = Invoice::create($data);
            self::invoiceProductServiceUpdateOrCreate($request, $invoice);
        });
    }

    private static function generateInvoiceNumber(Request $request): string
    {
        $invoice = Invoice::where('branch_id', $request->user()->branch_id)->where('contact_id',
            $request->contact_id)->latest()->first();
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
            $startValue = str_pad((int) $startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue.'/'.'INV/'.'MYT-'.$acronym.'/'.$invoiceDate.'/'.$invoiceYear;
        }

        $startingNumber = '000';
        $startValue = str_pad((int) $startingNumber + 1, 3, '0', STR_PAD_LEFT);

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

    public function update(InvoiceRequest $request, Invoice $invoice): void
    {
        DB::transaction(function () use ($request, $invoice) {
            $data = $request->validated();
            $data['branch_id'] = $request->user()->branch_id;
            $data['invoice_number'] = self::generateInvoiceNumber($request);
            $data['baa_file'] = $this->handleFileUploadService->upload($request, 'documents/invoice/baa', 'baa_file',
                $invoice->baa_file);
            $data['cooperative_contract_file'] = $this->handleFileUploadService->upload($request,
                'documents/invoice/cooperative-contract', 'cooperative_contract_file',
                $invoice->cooperative_contract_file);
            $data['created_by'] = $request->user()->id;
            $data['grand_total'] = $request->grand_total;
            $invoice->update($data);
            InvoiceProductService::whereIn('invoice_id', [$invoice->id])->delete();
            self::invoiceProductServiceUpdateOrCreate($request, $invoice);
        });
    }

    public function confirm(Invoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $this->accountTransactionAfterInvoiceSent($invoice);
            $this->calculatePPNAccountTransactionAfterInvoiceSent($invoice);
            $invoice->status = 1;
            $invoice->save();
        });
    }

    public function accountTransactionAfterInvoiceSent(Invoice $invoice): void
    {
        $piutangPelanggan = $this->subAccount->findPiutangPelangganSubAccount($invoice->branch_id);
        $selectedContact = $this->contact->getSelectedData($invoice->contact_id);
        $description = sprintf(self::INVOICE_SENT_DESCRIPTION, $selectedContact['company_name'],
            $invoice->invoice_number);

        DB::transaction(function () use ($piutangPelanggan, $description, $invoice) {
            $this->accountTransactionService->createDebitTransaction($description, $invoice->grand_total, null,
                $piutangPelanggan->id);
            $this->accountTransactionService->createCreditTransaction($description, $invoice->grand_total, null,
                $invoice->account_id);
        });
    }

    public function calculatePPNAccountTransactionAfterInvoiceSent(Invoice $invoice): void
    {
        $piutangPelanggan = $this->subAccount->findPiutangPelangganSubAccount($invoice->branch_id);
        $selectedContact = $this->contact->getSelectedData($invoice->contact_id);
        $totalPlusTax = self::PPN_RATE * $invoice['grand_total'];
        $description = sprintf(self::CALCULATE_PPN_AFTER_INVOICE_SENT_DESCRIPTION, $selectedContact['company_name'],
            $invoice->invoice_number);
        $ppn = $this->subAccount->findPPNSubAccount($invoice->branch_id);

        DB::transaction(function () use ($ppn, $piutangPelanggan, $description, $totalPlusTax) {
            $this->accountTransactionService->createDebitTransaction($description, $totalPlusTax, null,
                $piutangPelanggan->id);
            $this->accountTransactionService->createCreditTransaction($description, $totalPlusTax, null, $ppn->id);
        });
    }

    public function updatePaymentStatus(Request $request, Invoice $invoice): void
    {
        $selectedContact = $this->contact->getSelectedData($invoice->contact_id);
        $rekeningMayatamaPusat = $this->subAccount->findRekeningMayatamaPusatSubAccount($invoice->branch_id);
        $piutangPelanggan = $this->subAccount->findPiutangPelangganSubAccount($invoice->branch_id);
        $description = sprintf(self::CALCULATE_PPN_AFTER_INVOICE_SENT_DESCRIPTION, $selectedContact['company_name'],
            $invoice->invoice_number);
        $currentPPN = $this->accountTransaction->getCurrentPPNOnInvoice($invoice->branch_id, $description);

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
            $this->accountTransactionService->createDebitTransaction($description, $totalAfterInvoicePaid, null,
                $rekeningMayatamaPusat->id);
            $this->accountTransactionService->createCreditTransaction($description, $totalAfterInvoicePaid, null,
                $piutangPelanggan->id);
            $invoice->payment_status = self::PAID_STATUS;
            $invoice->save();
        });
    }

    public function includePPh23AfterInvoicePaid(Request $request, Invoice $invoice, string $companyName): void
    {
        $pph23Account = $this->subAccount->findPPH23SubAccount($invoice->branch_id);
        $piutangPelanggan = $this->subAccount->findPiutangPelangganSubAccount($invoice->branch_id);
        $description = sprintf(self::INCLUDE_PPH23_AFTER_INVOICE_PAID_DESCRIPTION, $companyName,
            $invoice->invoice_number);

        DB::transaction(function () use ($pph23Account, $piutangPelanggan, $description, $request) {
            $this->accountTransactionService->createDebitTransaction($description, $request->pph23_form, null,
                $pph23Account->id);
            $this->accountTransactionService->createCreditTransaction($description, $request->pph23_form, null,
                $piutangPelanggan->id);
        });
    }
}
