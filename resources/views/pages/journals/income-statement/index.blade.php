@extends('layouts.template')
@section('page-title', 'Laba Rugi')
@section('content')

    <div x-data="incomeStatementData()">
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
                            <tr class="w-50">
                                <td class="text-center" colspan="2">
                                    <h3 class="text-uppercase"><u>Pendapatan Usaha</u></h3>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    x-text="incomeStatement.pendapatan_usaha_layanan_internet?.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(incomeStatement.pendapatan_usaha_layanan_internet?.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    x-text="incomeStatement.pendapatan_jasa_layanan_jaringan_telkom?.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(incomeStatement.pendapatan_jasa_layanan_jaringan_telkom?.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">Pendapatan Bruto Usaha</td>
                                <td class="text-center"
                                    x-text="formatNumber(incomeStatement?.pendapatan_bruto_usaha)"></td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    x-text="incomeStatement.beban_pokok_pendapatan?.name"
                                ></td>
                                <td class="text-center"
                                    x-text="formatNumber(incomeStatement.beban_pokok_pendapatan?.debit_balance)"></td>
                            </tr>
                            <tr class="bg-danger">
                                <td class="text-center text-white text-uppercase fw-bold">Laba Bruto Usaha</td>
                                <td class="text-center text-white"
                                    x-text="formatNumber(incomeStatement?.laba_bruto_usaha)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">
                                    <h3 class="text-uppercase"><u>Beban Operasional</u></h3>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="incomeStatement.beban_penjualan?.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(incomeStatement.beban_penjualan?.debit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center">Beban Umum & Administrasi</td>
                                <td class="text-center">Belum Tau</td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    x-text="incomeStatement.beban_penyusutan?.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(incomeStatement.beban_penyusutan?.debit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                            <tr class="bg-danger">
                                <td class="text-center text-white text-uppercase fw-bold">LABA OPERASIONAL</td>
                                <td class="text-center text-white text-uppercase fw-bold"
                                    x-text="formatNumber(incomeStatement.laba_operasional)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">
                                    <h3 class="text-uppercase"><u>PENDAPATAN DARI LUAR USAHA</u></h3>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    x-text="incomeStatement.pendapatan_dari_luar_usaha?.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(incomeStatement.pendapatan_dari_luar_usaha?.credit_balance)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">
                                    <h3 class="text-uppercase"><u>BEBAN LAIN-LAIN</u></h3>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">Beban Lain-Lain</td>
                                <td class="text-center"
                                    x-text="formatNumber(incomeStatement.beban_lain_lain)"></td>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                            <tr class="bg-danger">
                                <td class="text-center text-white text-uppercase fw-bold">LABA SEBELUM PAJAK</td>
                                <td class="text-center text-white text-uppercase fw-bold"
                                    x-text="formatNumber(incomeStatement.laba_sebelum_pajak)"></td>
                            </tr>
                            <tr>
                                <td class="text-center" x-text="incomeStatement.pajak_penghasilan?.name"></td>
                                <td class="text-center"
                                    x-text="formatNumber(incomeStatement.pajak_penghasilan?.debit_balance)"></td>
                            </tr>
                            <tr class="bg-danger">
                                <td class="text-center text-white text-uppercase fw-bold">LABA BERSIH</td>
                                <td class="text-center text-white text-uppercase fw-bold"
                                    x-text="formatNumber(incomeStatement.laba_bersih)"></td>
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
        function incomeStatementData() {
            return {
                isLoading: false,
                incomeStatement: [],
                async init() {
                    await this.getIncomeStatementData();
                },
                async getIncomeStatementData() {
                    const resp = await axios.get('/journals/income-statement/data');
                    this.incomeStatement = resp.data;
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                },
            }
        }
    </script>
@endpush