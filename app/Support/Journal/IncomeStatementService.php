<?php

namespace App\Support\Journal;

use App\Models\Account;
use Illuminate\Http\Request;

class IncomeStatementService
{
    public function getpendapatanBrutoUsaha(Request $request)
    {
        return $this->getPendapatanUsaha($request)['pendapatanUsahaLayananInternet']->credit_balance
            +
            $this->getPendapatanUsaha($request)['pendapatanUsahaLayananInternet']->credit_balance;
    }

    public function getPendapatanUsaha(Request $request): array
    {
        $pendapatanUsahaLayananInternet = Account::where('code', '401')
            ->where('branch_id', $request->user()->branch_id)
            ->first();
        $pendapatanJasaLayananJaringanTelkom = Account::where('code', '402')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $bebanPokokPendapatan = Account::where('code', '500')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        return [
            'pendapatanUsahaLayananInternet' => $pendapatanUsahaLayananInternet,
            'pendapatanJasaLayananJaringanTelkom' => $pendapatanJasaLayananJaringanTelkom,
            'bebanPokokPendapatan' => $bebanPokokPendapatan,
        ];
    }

    public function getLabaBrutoUsaha(Request $request, $pendapatanBrutoUsaha)
    {
        return $pendapatanBrutoUsaha - $this->getPendapatanUsaha($request)['bebanPokokPendapatan']->debit_balance;
    }

    public function getTotalLabaOperasional(Request $request, $labaBrutoUsaha)
    {
        return $labaBrutoUsaha - (
                $this->getLabaOperasional($request)['bebanPenjualan']->debit_balance
                +
                $this->getLabaOperasional($request)['bebanPenyusutan']->debit_balance
            );
    }

    public function getLabaOperasional(Request $request): array
    {
        $getBebanPenjualan = Account::where('code', '501')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getBebanPenyusutan = Account::where('code', '512')
            ->where('branch_id', $request->user()->branch_id)
            ->first();


        $getBebanKaryawan = Account::where('code', '502')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getPendapatanLainnya = Account::where('code', '403')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getBebanLainLain = Account::where('code', '511')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getBebanBunga = Account::where('code', '513')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        $getBebanPajakPenghasilan = Account::where('code', '514')
            ->where('branch_id', $request->user()->branch_id)
            ->first();

        return [
            'bebanPenjualan' => $getBebanPenjualan,
            'bebanPenyusutan' => $getBebanPenyusutan,
            'pendapatanLainnya' => $getPendapatanLainnya,
            'bebanLainLain' => $getBebanLainLain,
            'bebanBunga' => $getBebanBunga,
            'bebanPajakPenghasilan' => $getBebanPajakPenghasilan,
        ];
    }

    public function getLabaSebelumPajak(Request $request, $totalLabaOperasional)
    {
        return $totalLabaOperasional + (
                $this->getLabaOperasional($request)['pendapatanLainnya']->credit_balance
                -
                $this->getBebanLainLain($request)
            );
    }

    public function getBebanLainLain(Request $request)
    {
        return $this->getLabaOperasional($request)['bebanLainLain']->debit_balance
            + $this->getLabaOperasional($request)['bebanBunga']->debit_balance;
    }

    public function getLabaBersih(Request $request, $labaSebelumPajak)
    {
        return $labaSebelumPajak - $this->getLabaOperasional(
                $request
            )['bebanPajakPenghasilan']->debit_balance;
    }
}
