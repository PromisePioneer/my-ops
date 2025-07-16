@php use function App\Helper\currencyFormat;use function App\Helper\formatDate; @endphp
@extends('layouts.template')
@section('page-title', 'Detail Aset')
@section('breadcrumbs', 'Master Keuangan - Aset - Detail Aset')
@section('content')
    <div x-data="assetDepreciationDetail()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title"></div>
                <div class="card-toolbar">
                    <div class="d-flex" data-kt-user-table-toolbar="base">
                        <div class="d-flex" data-kt-user-table-toolbar="base">
                            <a href="{{ url('/master/accounting/assets/') }}" class="btn btn-light-danger btn-sm">
                                <x-icons.back/>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <table class="table table-borderless fw-bolder">
                    <tr>
                        <th style="width: 170px">Nama aset</th>
                        <th style="width: 10px">:</th>
                        <th class="min-w-10px">{{ $asset->item->name }} ({{ $asset->code }})</th>
                    </tr>
                    <tr>
                        <th>Nilai Depresiasi / Tahun</th>
                        <th>:</th>
                        <th>{{ currencyFormat($asset->depreciation) }}</th>
                    </tr>
                    <tr>
                        <th>Tahun Perolehan</th>
                        <th>:</th>
                        <th>{{ formatDate($asset->date_received)  }}</th>
                    </tr>
                    <tr>
                        <th>Masa Manfaat</th>
                        <th>:</th>
                        <th>{{ $asset->useful_life }} Tahun</th>
                    </tr>
                    <tr>
                    </tr>
                </table>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Tahun</th>
                                <th class="min-w-125px">Depresiasi</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && assetDepreciations?.length === 0">
                                <tr class="fw-bolder">
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="asset in assetDepreciations" :key="asset.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td x-text="asset.date"></td>
                                    <td x-text="asset.amount"></td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in assetDepreciations.links">
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
        function assetDepreciationDetail() {
            return {
                isLoading: false,
                id: "{{ $asset->id }}",
                assetDepreciations: [],
                async init() {
                    await this.getDepreciationData();
                },
                async getDepreciationData() {
                    const resp = await axios.get(`/master/accounting/assets/detail/data/${this.id}`);
                    this.assetDepreciations = resp.data;
                },
                async paginationEndPoint(url) {
                    try {
                        this.assetDepreciations = [];
                        this.isLoading = true;
                        const resp = await axios.get(`${url}`);
                        this.assetDepreciations = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                    if (url) {
                    }
                },
            }
        }
    </script>
@endpush
