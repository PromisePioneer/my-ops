@php use function App\Helper\formatDate; @endphp
@extends('layouts.template')
@section('page-title', 'Detail Aset')
@section('breadcrumbs', 'Master Keuangan - Aset - Detail Aset')
@section('content')
    <div x-data="assetDepreciationDetail()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <table>
                        <tr>
                            <th>Nama aset</th>
                            <th>:</th>
                            <th>{{ $asset->name }}</th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Nilai residu</th>
                            <th>:</th>
                            <th> Rp. {{ number_format($asset->residu) }}</th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Harga perolehan</th>
                            <th>:</th>
                            <th>Rp {{ number_format($asset->total_price)  }}</th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Tahun Perolehan</th>
                            <th>:</th>
                            <th>{{ formatDate($asset->date_received)  }}</th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Masa Manfaat</th>
                            <th>:</th>
                            <th>{{ $asset->useful_life }} Tahun</th>
                        </tr>
                        <tr>
                        </tr>
                    </table>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex" data-kt-user-table-toolbar="base">
                        <div class="d-flex" data-kt-user-table-toolbar="base">
                            <a href="{{ url('/master/assets/') }}" class="btn btn-light-danger btn-sm">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12 ">
                    <form id="form-delete" @submit.prevent="destroy()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                        <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <i class="ki-duotone ki-trash-square fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            Hapus
                        </button>
                    </form>
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th>Tahun</th>
                                <th>Depresiasi</th>
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
                            <template x-if="!isLoading && assetDepreciationData.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="asset in assetDepreciationData" :key="asset.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td x-text="asset.depreciation_date"></td>
                                    <td x-text="asset.depreciation_amount"></td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in assetDepreciationData.links">
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
                assetDepreciationData: [],
                async init() {
                    await this.getDepreciationData();
                },
                async getDepreciationData() {
                    const resp = await axios.get(`/master/accounting/assets/detail/data/${this.id}`);
                    this.assetDepreciationData = resp.data;
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.assetDepreciationData = resp.data
                    }
                },
            }
        }
    </script>
@endpush
