@extends('layouts.template')
@section('page-title', 'Laporan Arus Kas')
@section('content')
    <div x-data="cashflowStatementData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="text-center w-50"></th>
                                <th class="text-center"></th>
                            </thead>
                            <tbody class="fw-bold">
                            <tr>
                                <td class="text-center text-uppercase" colspan="2">
                                    <u>Arus Kas Dari Kegiatan Operasional</u>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">Laba Rugi Bersih</td>
                                <td class="text-center" x-text="formatNumber(cashflowStatement?.laba_rugi_bersih)"></td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    x-text="cashflowStatement.akumulasi_penyusutan_aset_tetap.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.akumulasi_penyusutan_aset_tetap.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    (Kenaikan)/Penurunan Persediaan Barang Jaringan
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.persediaan_barang_jaringan.debit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    (Kenaikan)/Penurunan Piutang Usaha
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.piutang_usaha.debit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    (Kenaikan)/Penurunan Biaya Dibayar Dimuka
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.biaya_dibayar_dimuka.debit_balance)"></td>
                            </tr>
                            <tr>
                            <tr>
                                <td class="text-center">
                                    (Kenaikan)/Penurunan Pajak Dibayar Dimuka
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.pajak_dibayar_dimuka.debit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Kenaikan/(Penurunan) Utang Usaha
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.utang_usaha.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Kenaikan/(Penurunan) Utang Deposit Alat
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.utang_deposit_alat.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Kenaikan/(Penurunan) Utang Pajak
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.utang_pajak.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Kenaikan/(Penurunan) Pendapatan Diterima Dimuka
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.pendapatan_diterima_dimuka.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Kenaikan/(Penurunan) Biaya Yang Masih Harus Dibayar
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.biaya_yang_harus_dibayar.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Kenaikan/(Penurunan) Utang Lancar Lainnya
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.utang_lancar_lainnya.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Kenaikan/(Penurunan) Utang Kendaraan
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.utang_kendaraan_lainnya.credit_balance)"></td>
                            </tr>
                            <tr class="bg-danger">
                                <td class="text-center text-white text-uppercase fw-bold">
                                    Jumlah Arus Kas Dari Kegiatan Operasional
                                </td>
                                <td class="text-center text-white text-uppercase fw-bold"
                                    x-text="formatNumber(cashflowStatement.jumlah_arus_kas_dari_kegiatan_operasional)"></td>
                            </tr>
                            <tr>
                                <td class="text-center text-uppercase" colspan="2">
                                    <u>Arus Kas Dari Kegiatan Investasi</u>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    (Kenaikan)/Penurunan Aktiva Tetap
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.kenaikan_atau_penurunan_aktiva_tetap)"></td>
                            </tr>
                            <tr>
                                <td class="text-center text-uppercase" colspan="2">
                                    <u>Arus Kas Dari Kegiatan Pembiayaan</u>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Kenaikan/(Penurunan) Utang Bank
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.utang_bank?.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Kenaikan/(Penurunan) Utang Jangka Panjang Lainnya
                                </td>
                                <td class="text-center"
                                    x-text="formatNumber(cashflowStatement.utang_jangka_panjang_lainnya?.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Kenaikan/(Penurunan) Modal Saham
                                </td>
                                <td class="text-center">Belum Ngerti</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    (Kenaikan)/Penurunan Dividen
                                </td>
                                <td class="text-center">Belum Ngerti</td>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                            <tr class="bg-danger">
                                <td class="text-center text-white text-uppercase fw-bold">
                                    Jumlah Arus Kas Dari Kegiatan Pembiayaan
                                </td>
                                <td class="text-center text-white text-uppercase fw-bold"
                                    x-text="formatNumber(cashflowStatement.jumlah_arus_kas_dari_kegiatan_operasional)"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function cashflowStatementData() {
            return {
                isLoading: false,
                cashflowStatement: [],
                async init() {
                    await this.getCashflowStatementData();
                },
                async getCashflowStatementData() {
                    const resp = await axios.get('/journals/cashflow-statement/data');
                    this.cashflowStatement = resp.data;
                },
                formatNumber(val) {
                    return new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR"
                    }).format(val);
                }
            }
        }
    </script>
@endpush