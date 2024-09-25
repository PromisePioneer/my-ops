<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use App\Service\Journal\IncomeStatementService;
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
        $pendapatanBrutoUsaha = $this->incomeStatementService
                ->getPendapatanUsaha($request)['pendapatanUsahaLayananInternet']
                ->credit_balance
            + $this->incomeStatementService
                ->getPendapatanUsaha($request)['pendapatanUsahaLayananInternet']
                ->credit_balance;

        $labaBrutoUsaha = $pendapatanBrutoUsaha -
            $this->incomeStatementService->getPendapatanUsaha($request)['bebanPokokPendapatan']->debit_balance;


        $labaOperasional = $labaBrutoUsaha - (
                $this->incomeStatementService->getLabaOperasional($request)['bebanPenjualan']->debit_balance
                +
                $this->incomeStatementService->getLabaOperasional($request)['bebanPenyusutan']->debit_balance
            );


        $bebanLainLain = $this->incomeStatementService->getLabaOperasional(
                $request
            )['bebanLainLain']->debit_balance + $this->incomeStatementService->getLabaOperasional(
                $request
            )['bebanBunga']->debit_balance;


        $labaSebelumPajak = $labaOperasional + ($this->incomeStatementService->getLabaOperasional(
                    $request
                )['pendapatanLainnya']->credit_balance - $bebanLainLain);


        $labaBersih = $labaSebelumPajak - $this->incomeStatementService->getLabaOperasional(
                $request
            )['bebanPajakPenghasilan']->debit_balance;

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
