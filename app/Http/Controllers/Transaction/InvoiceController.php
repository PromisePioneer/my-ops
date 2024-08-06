<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Invoice\InvoiceRequest;
use App\Models\Branch;
use App\Models\CompanyProfile;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\InvoiceProductService;
use App\Models\LetterHead;
use App\Models\SubAccount;
use App\Service\CompanyProfileServices;
use App\Service\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public int $perPage = 10;

    private InvoiceProductService $invoiceProductService;

    private Invoice $invoice;

    private Contact $contact;

    private CompanyProfileServices $companyProfileServices;

    private SubAccount $subAccount;

    private InvoiceService $invoiceService;

    private Branch $branch;

    public function __construct()
    {
        $this->invoiceProductServices = new InvoiceProductService;
        $this->invoiceService = new InvoiceService;
        $this->invoice = new Invoice;
        $this->subAccount = new SubAccount;
        $this->contact = new Contact;
        $this->companyProfileServices = new CompanyProfileServices;
        $this->branch = new Branch;
    }

    public function index(): View
    {
        return view('pages.transaction.invoice.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->invoice->getDataWithPagination($request, $this->perPage));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->invoice->searchDataBasedOnUserBranch($request));
    }

    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        return response()->json($this->invoice->filterDataBasedOnBranch($branch->id, $this->perPage));
    }

    public function create()
    {
        return view('pages.transaction.invoice.create');
    }

    public function getContact(Request $request): JsonResponse
    {
        $contact = $this->contact->getData($request);

        return response()->json($contact);
    }

    public function getAccountData(Request $request): array
    {
        return $this->subAccount->getSubAccountForInvoiceStore($request);
    }

    public function store(InvoiceRequest $request): JsonResponse
    {
        $this->invoiceService->store($request);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(Invoice $invoice)
    {
        return view('pages.transaction.invoice.edit', compact('invoice'));
    }

    public function getSelectedContact(Invoice $invoice): JsonResponse
    {
        $contact = $this->contact->getSelectedData($invoice->contact_id);

        return response()->json($contact);
    }

    public function getSelectedSubAccount(Request $request, Invoice $invoice): array
    {
        $subAccount = $this->subAccount->getSelectedSubAccount($request, $invoice->account_id);

        return [
            'id' => $subAccount->id,
            'name' => $subAccount->name,
        ];
    }

    public function getSelectedInvoiceProductServices(Invoice $invoice): JsonResponse
    {
        return response()->json($this->invoiceProductServices->getSelectedInvoiceProductServices($invoice));
    }

    public function update(InvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        $this->invoiceService->update($request, $invoice);

        return response()->json([
            'message' => 'data berhasil diubah',
        ]);
    }

    public function confirm(Invoice $invoice): JsonResponse
    {
        $this->invoiceService->confirm($invoice);

        return response()->json([
            'message' => 'data berhasil diubah',
        ]);
    }

    public function detail(Invoice $invoice)
    {
        $invoiceServiceList = DB::table('invoice_services')
            ->where('invoice_id', $invoice->id)
            ->get();

        $letterHead = LetterHead::where('id', 1)->first();
        $companyProfile = $this->companyProfileServices->getCompanyProfile();

        return view('pages.transaction.invoice.detail', compact('invoice', 'invoiceServiceList', 'letterHead', 'companyProfile'));
    }

    public function updatePaymentStatus(Request $request, Invoice $invoice): JsonResponse
    {
        $this->invoiceService->updatePaymentStatus($request, $invoice);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function jurnalEntry(Invoice $invoice): JsonResponse
    {
        $jurnalEntry = DB::table('account_transactions')
            ->join('sub_accounts', 'sub_accounts.id', '=', 'account_transactions.sub_account_id')
            ->where('account_transactions.description', 'like', '%'.$invoice->invoice_number.'%')
            ->select('account_transactions.*', 'sub_accounts.name', 'sub_accounts.code', 'sub_accounts.id as sub_account_id')
            ->get();

        return response()->json($jurnalEntry);
    }

    public function viewFile(Invoice $invoice): View
    {
        return view('pages.transaction.invoice.view-file', compact('invoice'));
    }

    public function exportToPDF(Invoice $invoice): Response
    {
        $invoiceServiceList = DB::table('invoice_services')
            ->where('invoice_id', $invoice->id)
            ->get();

        $companyProfile = CompanyProfile::where('id', 1)->first();

        $pdf = Pdf::loadView('pages.transaction.invoice.export-pdf', compact('invoice', 'invoiceServiceList', 'companyProfile'))->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        $invoice->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
