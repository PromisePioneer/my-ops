<?php

namespace App\Service\Journal;

use App\Models\Account;
use Illuminate\Http\Request;

class FinancialReportService
{
    public function __construct()
    {
    }

    public function getAsetLancar(Request $request): array
    {
        $getKasAccount = Account::where('code', '111')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getPersediaanBarangJaringanAccount = Account::where('code', '112')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getPiutangUsahaAccount = Account::where('code', '113')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getPajakDibayarDimukaAccount = Account::where('code', '113')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        return [
            'getKasAccount' => $getKasAccount,
            'getPersediaanBarangJarianganAccount' => $getPersediaanBarangJaringanAccount,
            'getPiutangUsahaAccount' => $getPiutangUsahaAccount,
            'getPajakDibayarDimukaAccount' => $getPajakDibayarDimukaAccount,
        ];
    }


    public function getAsetTetap(Request $request): array
    {
        $getTanahAccount = Account::where('code', '121')
            ->where('branch_id', $request->user()->branch_id)
            ->first();
        $getBangunanAccount = Account::where('code', '122')
            ->where('branch_id', $request->user()->branch_id)
            ->first();
        $getKendaraanAccount = Account::where('code', '123')
            ->where('branch_id', $request->user()->branch_id)
            ->first();
        $getMesinAccount = Account::where('code', '124')
            ->where('branch_id', $request->user()->branch_id)
            ->first();
        $getInventarisAccount = Account::where('code', '125')
            ->where('branch_id', $request->user()->branch_id)
            ->first();


        return [
            'getTanahAccount' => $getTanahAccount,
            'getBangunanAccount' => $getBangunanAccount,
            'getKendaraanAccount' => $getKendaraanAccount,
            'getMesinAccount' => $getMesinAccount,
            'getInventarisAccount' => $getInventarisAccount,
        ];
    }


    public function getUtangLancar(Request $request): array
    {
        $getUtangUsahaAccount = Account::where('code', '211')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getUtangDepositAlatAccount = Account::where('code', '212')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getUtangPajakAccount = Account::where('code', '213')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getPendapatanDiterimaDimukaAccount = Account::where('code', '214')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getBiayaYangHarusDibayarAccount = Account::where('code', '215')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getUtangLancarLainnyaAccount = Account::where('code', '216')
            ->where('branch_id', $request->user()->branch_id)
            ->first();


        return [
            'utangUsaha' => $getUtangUsahaAccount,
            'utangDepositAlat' => $getUtangDepositAlatAccount,
            'utangPajak' => $getUtangPajakAccount,
            'pendapatanDiterimaDimuka' => $getPendapatanDiterimaDimukaAccount,
            'biayaYangHarusDibayar' => $getBiayaYangHarusDibayarAccount,
            'utangLancarLainnya' => $getUtangLancarLainnyaAccount,
        ];
    }


    public function getUtangJangkaPanjang(Request $request): array
    {
        $getUtangBank = Account::where('code', '221')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getUtangKendaraan = Account::where('code', '222')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getUtangJangkaPanjangLainnya = Account::where('code', '223')
            ->where('branch_id', $request->user()->branch_id)
            ->first();


        return [
            'utangBank' => $getUtangBank,
            'utangKendaraan' => $getUtangKendaraan,
            'utangJangkaPanjangLainnya' => $getUtangJangkaPanjangLainnya,
        ];
    }


    public function getModal(Request $request): array
    {
        $getModalSahamAccount = Account::where('code', '310')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getSaldoLabaDitahan = Account::where('code', '312')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getLabaRugiBersihPeriodeBerjalan = Account::where('code', '313')
            ->where('branch_id', $request->user()->branch_id)
            ->first();


        return [
            'modalSaham' => $getModalSahamAccount,
            'saldoLabaDitahan' => $getSaldoLabaDitahan,
            'labaRugiBersihPeriodeBerjalan' => $getLabaRugiBersihPeriodeBerjalan,
        ];
    }
}