<?php

namespace App\Http\Controllers\Accounting\Journals;

use App\Http\Controllers\Controller;
use App\Support\Journal\CashflowStatementService;
use App\Support\Journal\FinancialReportService;
use App\Support\Journal\IncomeStatementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashflowStatementController extends Controller
{

    private CashflowStatementService $cashflowStatementService;
    private IncomeStatementService $incomeStatementService;

    public function __construct()
    {
        $this->financialReportService = new FinancialReportService();
        $this->incomeStatementService = new IncomeStatementService();
        $this->cashflowStatementService = new CashflowStatementService();
    }


    public function index(): View
    {
        return view('pages.journals.cashflow-statement.index');
    }

    public function data(Request $request): JsonResponse
    {
        $pendapatanBrutoUsaha = $this->incomeStatementService->getpendapatanBrutoUsaha($request);
        $labaBrutoUsaha = $this->incomeStatementService->getLabaBrutoUsaha($request, $pendapatanBrutoUsaha);
        $labaSebelumPajak = $this->incomeStatementService->getLabaSebelumPajak($request, $labaBrutoUsaha);
        $labaRugiBersih = $this->incomeStatementService->getLabaBersih($request, $labaSebelumPajak);


        $akumulasiPenyusutanAsetTetap = $this->financialReportService
            ->getAsetTetap($request)['getAkumulasiPenyusutanAsetTetap'];


        $persediaanBarangJaringan = $this->financialReportService->getAsetLancar(
            $request
        )['getPersediaanBarangJarianganAccount'];

        $piutangUsaha = $this->financialReportService->getAsetLancar($request)['getPiutangUsahaAccount'];
        $biayaDibayarDimuka = $this->financialReportService->getUtangLancar($request)['biayaYangHarusDibayar'];
        $pajakDibayarDimuka = $this->financialReportService->getAsetLancar($request)['getPajakDibayarDimukaAccount'];
        $utangUsaha = $this->financialReportService->getUtangLancar($request)['utangUsaha'];
        $utangDepositAlat = $this->financialReportService->getUtangLancar($request)['utangDepositAlat'];
        $utangPajak = $this->financialReportService->getUtangLancar($request)['utangPajak'];
        $pendapatanDiterimaDimuka = $this->financialReportService->getUtangLancar($request)['pendapatanDiterimaDimuka'];
        $biayaYangMasihHarusDibayar = $this->financialReportService->getUtangLancar($request)['biayaYangHarusDibayar'];
        $utangLancarLainnya = $this->financialReportService->getUtangLancar($request)['utangLancarLainnya'];
        $utangKendaraanLainnya = $this->financialReportService->getUtangJangkaPanjang($request)['utangKendaraan'];


        $sumTotalUtangLancar = $this->financialReportService->getUtangLancar($request)['utangUsaha']->credit_balance
            + $this->financialReportService->getUtangLancar($request)['utangDepositAlat']->credit_balance
            + $this->financialReportService->getUtangLancar($request)['utangPajak']->credit_balance
            + $this->financialReportService->getUtangLancar($request)['pendapatanDiterimaDimuka']->credit_balance
            + $this->financialReportService->getUtangLancar($request)['biayaYangHarusDibayar']->credit_balance
            + $this->financialReportService->getUtangLancar($request)['utangLancarLainnya']->credit_balance;


        $sumArusKasKegiatanOperasional = $labaRugiBersih + $akumulasiPenyusutanAsetTetap->credit_balance + $piutangUsaha->debit_balance + $biayaDibayarDimuka->debit_balance + $pajakDibayarDimuka->debit_balance + $utangUsaha->credit_balance + $utangDepositAlat->credit_balance + $utangPajak->credit_balance + $pendapatanDiterimaDimuka->credit_balance + $biayaYangMasihHarusDibayar->credit_balance + $utangLancarLainnya->credit_balance + $utangKendaraanLainnya->credit_balance;


        $arusKasDariKegiatanInvestasi = $this->financialReportService
                ->getAsetTetap($request)['getTanahAccount']->debit_balance
            + $this->financialReportService
                ->getAsetTetap($request)['getBangunanAccount']->debit_balance
            + $this->financialReportService
                ->getAsetTetap($request)['getKendaraanAccount']->debit_balance
            + $this->financialReportService
                ->getAsetTetap($request)['getMesinAccount']->debit_balance
            + $this->financialReportService
                ->getAsetTetap($request)['getInventarisKantorAccount']->debit_balance
            + $this->financialReportService
                ->getAsetTetap($request)['getInventarisJaringanAccount']->debit_balance;


        $utangBank = $this->financialReportService->getUtangJangkaPanjang($request)['utangBank'];
        $utangJangkaPanjangLainnya = $this->financialReportService->getUtangJangkaPanjang(
            $request
        )['utangJangkaPanjangLainnya'];

        return response()->json([
            'laba_rugi_bersih' => $labaRugiBersih,
            'akumulasi_penyusutan_aset_tetap' => $akumulasiPenyusutanAsetTetap,
            'persediaan_barang_jaringan' => $persediaanBarangJaringan,
            'piutang_usaha' => $piutangUsaha,
            'biaya_dibayar_dimuka' => $biayaDibayarDimuka,
            'pajak_dibayar_dimuka' => $pajakDibayarDimuka,
            'utang_usaha' => $utangUsaha,
            'utang_deposit_alat' => $utangDepositAlat,
            'utang_pajak' => $utangPajak,
            'pendapatan_diterima_dimuka' => $pendapatanDiterimaDimuka,
            'biaya_yang_harus_dibayar' => $biayaYangMasihHarusDibayar,
            'utang_lancar_lainnya' => $utangLancarLainnya,
            'total_utang_lancar' => $sumTotalUtangLancar,
            'utang_kendaraan_lainnya' => $utangKendaraanLainnya,
            'jumlah_arus_kas_dari_kegiatan_operasional' => $sumArusKasKegiatanOperasional,
            'kenaikan_atau_penurunan_aktiva_tetap' => $arusKasDariKegiatanInvestasi,
            'utang_bank' => $utangBank,
            'utang_jangka_panjang_lainnya' => $utangJangkaPanjangLainnya,
        ]);
    }
}
