<?php

namespace App\Http\Controllers\Accounting\Transaction\IncomeTransactions;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\IncomeTransactions\Invoice\InvoiceRequest;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\CompanyProfile;
use App\Models\Invoice;
use App\Models\InvoiceProductService;
use App\Models\LetterHead;
use App\Models\Master\Common\Branch;
use App\Models\Master\Common\Contact;
use App\Support\HelperService\CompanyProfileServices;
use App\Support\IncomeTransaction\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Throwable;

#[AllowDynamicProperties] class InvoiceController extends Controller
{
    public int $perPage = 10;
    private InvoiceProductService $invoiceProductService;

    public function __construct()
    {
        $this->invoiceProductServices = new InvoiceProductService();
        $this->invoiceService = new InvoiceService();
        $this->invoice = new Invoice();
        $this->contact = new Contact();
        $this->companyProfileServices = new CompanyProfileServices();
        $this->branch = new Branch();
        $this->account = new Account();
    }

    public function index(): View
    {
        return view('pages.transaction.income-transactions.invoice.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->invoiceService->data($request));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->invoiceService->search($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        $invoice = Invoice::with('contact', 'user')->where('branch_id', $branch->id)->paginate(10);

        return response()->json($invoice);
    }

    public function create(): View
    {
        return view('pages.transaction.income-transactions.invoice.create');
    }

    public function getContact(Request $request): JsonResponse
    {
        $contact = $this->contact->getData($request);

        return response()->json($contact);
    }

    public function getAccountData(Request $request): JsonResponse
    {
        return response()->json($this->account->getSubAccountForInvoiceStore($request));
    }

    /**
     * @throws Throwable
     */
    public function store(InvoiceRequest $request): JsonResponse
    {
        $this->invoiceService->store($request);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(Invoice $invoice): View
    {
        return view('pages.transaction.income-transactions.invoice.edit', compact('invoice'));
    }

    public function getSelectedContact(Invoice $invoice): JsonResponse
    {
        $contact = $this->contact->getSelectedData($invoice->contact_id);

        return response()->json($contact);
    }

    public function getSelectedSubAccount(Request $request, Invoice $invoice): JsonResponse
    {
        return response()->json($this->account->getSelectedAccount($invoice->account_id));
    }

    public function getSelectedInvoiceProductServices(Invoice $invoice): JsonResponse
    {
        $invoice = InvoiceProductService::where('invoice_id', $invoice->id)->get();
        return response()->json($invoice);
    }

    /**
     * @throws Throwable
     */
    public function update(InvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        $this->invoiceService->update($request, $invoice);

        return response()->json([
            'message' => 'data berhasil diubah',
        ]);
    }

    /**
     * @throws Throwable
     */
    public function confirm(Invoice $invoice): JsonResponse
    {
        $this->invoiceService->confirm($invoice);

        return response()->json([
            'message' => 'data berhasil diubah',
        ]);
    }

    public function detail(Invoice $invoice): View
    {
        $invoiceServiceList = DB::table('invoice_services')
            ->where('invoice_id', $invoice->id)
            ->get();

        $letterHead = LetterHead::where('id', 1)->first();
        $companyProfile = $this->companyProfileServices->getCompanyProfile();

        return view(
            'pages.transaction.income-transactions.invoice.detail',
            compact('invoice', 'invoiceServiceList', 'letterHead', 'companyProfile')
        );
    }

    /**
     * @throws Throwable
     */
    public function updatePaymentStatus(Request $request, Invoice $invoice): JsonResponse
    {
        $this->invoiceService->updatePaymentStatus($request, $invoice);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function jurnalEntry(Invoice $invoice): JsonResponse
    {
        $jurnalEntry = AccountTransaction::with('account')->where(
            'description',
            'like',
            '%'.$invoice->invoice_number.'%'
        )->get()
            ->map(function ($query) {
                return [
                    'id' => $query->id,
                    'account_name' => $query->account->code.' '.$query->account->name,
                    'type' => $query->type,
                    'amount' => 'Rp.'.number_format($query->amount, 2),
                ];
            });

        return response()->json($jurnalEntry);
    }

    public function viewFile(Invoice $invoice): View
    {
        return view('pages.transaction.income-transactions.invoice.view-file', compact('invoice'));
    }

    public function exportToPDF(Invoice $invoice): Response
    {
        $invoiceServiceList = DB::table('invoice_services')
            ->where('invoice_id', $invoice->id)
            ->get();

        $companyProfile = CompanyProfile::where('id', 1)->first();

        $pdf = Pdf::loadView(
            'pages.transaction.income-transactions.invoice.export-pdf',
            compact('invoice', 'invoiceServiceList', 'companyProfile')
        )->setPaper('A4', 'portrait');


        $view = view('pages.transaction.income-transactions.invoice.export-pdf', compact('invoice', 'invoiceServiceList', 'companyProfile'));
        $pdf = Browsershot::html($view)
            ->setChromePath('/usr/bin/chromium')
            ->noSandbox()
            ->waitUntilNetworkIdle()
            ->ignoreHttpsErrors()
            ->format('A4')
            ->setEnvironmentOptions([
                'CHROME_CONFIG_HOME' => storage_path('app/chrome/.config')
            ])->pdf();

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="example.pdf"',
        ]);
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        $invoice->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
