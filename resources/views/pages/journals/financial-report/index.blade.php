@extends('layouts.template')
@section('page-title', 'Laporan Keuangan')
@section('content')

    <div x-data="financialReportData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="text-center w-50">Aktiva</th>
                                <th class="text-center">Nominal</th>
                            </thead>
                            <tbody class="fw-bold">
                            <tr>
                                <td class="text-center" colspan="2">Aset Lancar</td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="aktiva.kas_account.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(aktiva.kas_account.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="aktiva.persediaan_barang_jaringan.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(aktiva.persediaan_barang_jaringan.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="aktiva.piutang_usaha_account.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(aktiva.piutang_usaha_account.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="aktiva.pajak_dibayar_dimuka.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(aktiva.pajak_dibayar_dimuka.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">Total Aset Lancar</td>
                                <td class="text-center" x-text="formatNumber(aktiva.total_aset_lancar)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">Aset Tetap</td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="aktiva.tanah.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(aktiva.tanah.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="aktiva.bangunan.name"></td>
                                <td class="text-center" x-text="formatNumber(aktiva.bangunan.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="aktiva.kendaraan.name"></td>
                                <td class="text-center" x-text="formatNumber(aktiva.kendaraan.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="aktiva.mesin.name"></td>
                                <td class="text-center" x-text="formatNumber(aktiva.mesin.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="aktiva.inventaris.name"></td>
                                <td class="text-center" x-text="formatNumber(aktiva.inventaris.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">Total Aset Lancar</td>
                                <td class="text-center" x-text="formatNumber(aktiva.total_aset_tetap)"></td>
                            </tr>
                            <tr class="bg-danger">
                                <td class="text-center text-white text-uppercase fw-bold">Total Aktifa</td>
                                <td class="text-center text-white text-uppercase fw-bold"
                                    x-text="formatNumber(aktiva.total_aktiva)"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in branches.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="paginationEndPoint(pagination.url)"
                                        x-html="pagination.label">
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="text-center w-50">Passiva</th>
                                <th class="text-center">Nominal</th>
                            </thead>
                            <tbody class="fw-bold">
                            <tr>
                                <td class="text-center" colspan="2">Utang Lancar</td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.utang_usaha.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.utang_usaha.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.utang_deposit_alat.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.utang_deposit_alat.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.utang_pajak.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.utang_pajak.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.pendapatan_diterima_dimuka.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.pendapatan_diterima_dimuka.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.biaya_yang_harus_dibayar.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.biaya_yang_harus_dibayar.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.utang_lancar_lainnya.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.utang_lancar_lainnya.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">Total Utang Lancar</td>
                                <td class="text-center" x-text="formatNumber(passiva.total_utang_lancar)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">Utang Jangka Panjang</td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.utang_bank.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.utang_bank.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.utang_kendaraan.name"></td>
                                <td class="text-center" x-text="formatNumber(passiva.utang_kendaraan.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.utang_jangka_panjang_lainnya.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.utang_jangka_panjang_lainnya.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">Total Utang Jangka Panjang</td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.total_utang_jangka_panjang)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">Modal</td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.modal_saham.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.modal_saham.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.saldo_laba_ditahan.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.saldo_laba_ditahan.balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="passiva.laba_rugi_bersih_periode_berjalana.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(passiva.laba_rugi_bersih_periode_berjalana.balance)"></td>
                            </tr>
                            <tr class="bg-danger">
                                <td class="text-center text-white text-uppercase fw-bold">Total Passiva</td>
                                <td class="text-center text-white text-uppercase fw-bold"
                                    x-text="formatNumber(passiva.total_passiva)"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in branches.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="paginationEndPoint(pagination.url)"
                                        x-html="pagination.label">
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        function financialReportData() {
            return {
                aktiva: [],
                passiva: [],
                isLoading: true,
                startIndex: null,
                async init() {
                    await this.aktivaData();
                    await this.passivaData();
                },
                async aktivaData() {
                    const resp = await axios.get('/journals/financial-report/data/aktiva');
                    this.aktiva = resp.data;
                },
                async passivaData() {
                    const resp = await axios.get('/journals/financial-report/data/passiva')
                    this.passiva = resp.data;
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