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
                                <th class="text-center w-50"></th>
                                <th class="text-center"></th>
                            </thead>
                            <tbody class="fw-bold">
                            <tr>
                                <td class="text-center text-uppercase" colspan="2">
                                    <u>Aset Tetap</u>
                                </td>
                            </tr>
                            <template x-for="(asset, index) in assets" :key="index">
                                <tr>
                                    <td class="text-center" x-text="asset.name"></td>
                                    <td class="text-center" x-text="asset.amount"></td>
                                </tr>
                            </template>
                            <tr>
                                <td class="text-center" x-text="assetAccumulatedOfAssetsData.name"></td>
                                <td class="text-center" x-text="assetAccumulatedOfAssetsData"></td>
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
        function financialReportData() {
            return {
                aktiva: [],
                passiva: [],
                isLoading: true,
                startIndex: null,
                assets: [],
                assetAccumulatedOfAssetsData: {},
                async init() {
                    await this.getFixedAssets();
                    await this.getAccumulatedOfAssetsData();
                },
                async getFixedAssets() {
                    const resp = await axios.get('/journals/financial-report/fixed-assets/data');
                    this.assets = resp.data
                },
                async getAccumulatedOfAssetsData() {
                    const resp = await axios.get('/journals/financial-report/accumulated-depreciation-of-fixed-assets-account');
                    this.assetAccumulatedOfAssetsData = resp.data;
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