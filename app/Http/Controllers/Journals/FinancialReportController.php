<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use App\Service\Journal\FinancialReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialReportController extends Controller
{

    private FinancialReportService $financialReportService;

    public function __construct()
    {
        $this->financialReportService = new FinancialReportService();
    }

    public function index(): View
    {
        return view('pages.journals.financial-report.index');
    }


    public function getAktiva(Request $request): JsonResponse
    {
        $sumTotalAssetLancar = $this->financialReportService->getAsetLancar($request)['getKasAccount']->balance
            + $this->financialReportService->getAsetLancar($request)['getPersediaanBarangJarianganAccount']->balance
            + $this->financialReportService->getAsetLancar($request)['getPiutangUsahaAccount']->balance
            + $this->financialReportService->getAsetLancar($request)['getPajakDibayarDimukaAccount']->balance;


        $sumTotalAssetTetap = $this->financialReportService->getAsetTetap($request)['getTanahAccount']->balance
            + $this->financialReportService->getAsetTetap($request)['getBangunanAccount']->balance
            + $this->financialReportService->getAsetTetap($request)['getKendaraanAccount']->balance
            + $this->financialReportService->getAsetTetap($request)['getMesinAccount']->balance
            + $this->financialReportService->getAsetTetap($request)['getInventarisKantorAccount']->balance;
        +$this->financialReportService->getAsetTetap($request)['getInventarisJaringanAccount']->balance;

        return response()->json([
            'kas_account' => $this->financialReportService->getAsetLancar($request)['getKasAccount'],
            'persediaan_barang_jaringan' => $this->financialReportService
                ->getAsetLancar($request)['getPersediaanBarangJarianganAccount'],
            'piutang_usaha_account' => $this->financialReportService->getAsetLancar($request)['getPiutangUsahaAccount'],
            'pajak_dibayar_dimuka' => $this->financialReportService->getAsetLancar(
                $request
            )['getPajakDibayarDimukaAccount'],
            'total_aset_lancar' => $sumTotalAssetLancar,
            'tanah' => $this->financialReportService->getAsetTetap($request)['getTanahAccount'],
            'bangunan' => $this->financialReportService->getAsetTetap($request)['getBangunanAccount'],
            'kendaraan' => $this->financialReportService->getAsetTetap($request)['getKendaraanAccount'],
            'mesin' => $this->financialReportService->getAsetTetap($request)['getMesinAccount'],
            'inventaris_kantor' => $this->financialReportService->getAsetTetap($request)['getInventarisKantorAccount'],
            'inventaris_jaringan' => $this->financialReportService->getAsetTetap(
                $request
            )['getInventarisJaringanAccount'],
            'penyusutan_aset_tetap' => $this->financialReportService->getAsetTetap(
                $request
            )['getAkumulasiPenyusutanAsetTetap'],
            'total_aset_tetap' => $sumTotalAssetTetap,
            'total_aktiva' => $sumTotalAssetLancar + $sumTotalAssetTetap,
        ]);
    }


    public function getPassiva(Request $request): JsonResponse
    {
        $sumTotalUtangLancar = $this->financialReportService->getUtangLancar($request)['utangUsaha']->credit_balance
            + $this->financialReportService->getUtangLancar($request)['utangDepositAlat']->credit_balance
            + $this->financialReportService->getUtangLancar($request)['utangPajak']->credit_balance
            + $this->financialReportService->getUtangLancar($request)['pendapatanDiterimaDimuka']->credit_balance
            + $this->financialReportService->getUtangLancar($request)['biayaYangHarusDibayar']->credit_balance
            + $this->financialReportService->getUtangLancar($request)['utangLancarLainnya']->credit_balance;


        $sumTotalUtangJangkaPanjang = $this->financialReportService
                ->getUtangJangkaPanjang($request)['utangBank']->balance
            + $this->financialReportService
                ->getUtangJangkaPanjang($request)['utangKendaraan']->balance
            + $this->financialReportService->getUtangJangkaPanjang(
                $request
            )['utangJangkaPanjangLainnya']->balance;


        $sumTotalModal = $this->financialReportService->getModal($request)['modalSaham']->balance;
        +$this->financialReportService->getModal($request)['saldoLabaDitahan']->balance;
        +$this->financialReportService->getModal($request)['labaRugiBersihPeriodeBerjalan']->balance;


        $totalPassiva = $sumTotalUtangJangkaPanjang + $sumTotalModal;


        return response()->json([
            'utang_usaha' => $this->financialReportService->getUtangLancar($request)['utangUsaha'],
            'utang_deposit_alat' => $this->financialReportService->getUtangLancar($request)['utangDepositAlat'],
            'utang_pajak' => $this->financialReportService->getUtangLancar($request)['utangPajak'],
            'pendapatan_diterima_dimuka' => $this->financialReportService->getUtangLancar(
                $request
            )['pendapatanDiterimaDimuka'],
            'biaya_yang_harus_dibayar' => $this->financialReportService->getUtangLancar(
                $request
            )['biayaYangHarusDibayar'],
            'utang_lancar_lainnya' => $this->financialReportService->getUtangLancar($request)['utangLancarLainnya'],
            'total_utang_lancar' => $sumTotalUtangLancar,
            'utang_bank' => $this->financialReportService->getUtangJangkaPanjang($request)['utangBank'],
            'utang_kendaraan' => $this->financialReportService->getUtangJangkaPanjang($request)['utangKendaraan'],
            'utang_jangka_panjang_lainnya' => $this->financialReportService->getUtangJangkaPanjang(
                $request
            )['utangJangkaPanjangLainnya'],
            'total_utang_jangka_panjang' => $sumTotalUtangJangkaPanjang,
            'modal_saham' => $this->financialReportService->getModal($request)['modalSaham'],
            'saldo_laba_ditahan' => $this->financialReportService->getModal($request)['saldoLabaDitahan'],
            'laba_rugi_bersih_periode_berjalana' => $this->financialReportService->getModal(
                $request
            )['labaRugiBersihPeriodeBerjalan'],
            'total_modal' => $sumTotalModal,
            'total_passiva' => $totalPassiva,
        ]);
    }

}
