<?php

namespace App\Http\Controllers\Accounting\Journals;

use App\Http\Controllers\Controller;
use App\Support\Journal\IncomeStatementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncomeStatementController extends Controller
{

    private IncomeStatementService $incomeStatementService;

    public function __construct()
    {
        $this->incomeStatementService = new IncomeStatementService();
    }

    public function index(): View
    {
        return view('pages.journals.income-statement.index');
    }

    public function data(Request $request): JsonResponse
    {
        $pendapatanBrutoUsaha = $this->incomeStatementService->getpendapatanBrutoUsaha($request);
        $labaBrutoUsaha = $this->incomeStatementService->getLabaBrutoUsaha($request, $pendapatanBrutoUsaha);
        $labaOperasional = $this->incomeStatementService->getTotalLabaOperasional($request, $labaBrutoUsaha);
        $bebanLainLain = $this->incomeStatementService->getBebanLainLain($request);
        $labaSebelumPajak = $this->incomeStatementService->getLabaSebelumPajak($request, $labaBrutoUsaha);
        $labaBersih = $this->incomeStatementService->getLabaBersih($request, $labaSebelumPajak);

        return response()->json([
            'pendapatan_usaha_layanan_internet' => $this->incomeStatementService->getPendapatanUsaha(
                $request
            )['pendapatanUsahaLayananInternet'],
            'pendapatan_jasa_layanan_jaringan_telkom' => $this->incomeStatementService->getPendapatanUsaha(
                $request
            )['pendapatanJasaLayananJaringanTelkom'],
            'pendapatan_bruto_usaha' => $pendapatanBrutoUsaha,
            'beban_pokok_pendapatan' => $this->incomeStatementService->getPendapatanUsaha(
                $request
            )['bebanPokokPendapatan'],
            'laba_bruto_usaha' => $labaBrutoUsaha,
            'beban_penjualan' => $this->incomeStatementService->getLabaOperasional($request)['bebanPenjualan'],
            'beban_penyusutan' => $this->incomeStatementService->getLabaOperasional($request)['bebanPenyusutan'],
            'laba_operasional' => $labaOperasional,
            'pendapatan_dari_luar_usaha' => $this->incomeStatementService->getLabaOperasional(
                $request
            )['pendapatanLainnya'],
            'beban_lain_lain' => $bebanLainLain,
            'laba_sebelum_pajak' => $labaSebelumPajak,
            'pajak_penghasilan' => $this->incomeStatementService->getLabaOperasional($request)['bebanPajakPenghasilan'],
            'laba_bersih' => $labaBersih,
        ]);
    }

}
