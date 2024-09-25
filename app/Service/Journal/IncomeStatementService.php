<?php

namespace App\Service\Journal;

use App\Models\Account;
use Illuminate\Http\Request;

class IncomeStatementService
{
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
}