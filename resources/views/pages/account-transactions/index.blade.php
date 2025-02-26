@extends('layouts.template')
@section('page-title', 'Riwayat Transaksi Akun')
@section('breadcrumbs', 'Transaksi Akun')
@section('content')
    <div x-data="accountTransactionHistory()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-250px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <form id="form-filter" @submit.prevent="filter()">
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600">
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid main-branches-select2"
                                            name="branch_id" id="branch-id-filter">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pt-4 text-end">
                            <button type="submit" class="btn btn-light btn-active-primary btn-sm">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                                <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                                       class="form-control form-control-solid w-250px ps-14"
                                       placeholder="Search...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="py-5">
                            <div class="col-12 ">
                                <form id="deleteForm" @submit.prevent="destroy()">
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
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox"
                                                       @click="toggleAllCheckBox()">
                                            </div>
                                        </th>
                                        <th class="min-w-125px text-center">Keterangan</th>
                                        <th class="min-w-125px text-center">Debit</th>
                                        <th class="min-w-125px text-center">Kredit</th>
                                        <th class="min-w-125px text-center">
                                            Realtime Status
                                        </th>
                                        <th class="min-w-125px text-center">Last Download</th>
                                        <th class="min-w-250px text-center">Actions</th>
                                    </thead>
                                    <tbody class="fw-bold">
                                    <template x-if="isLoading">
                                        <tr>
                                            <td colspan="9">
                                                <div style="text-align: center;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="!isLoading && devices.data?.length === 0">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(device, index) in devices?.data" :key="device.id">
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <input class="form-check-input" type="checkbox"
                                                           :value="device.id"
                                                           :id="'checkbox-' + device.id"/>
                                                </div>
                                            </td>
                                            <td class="text-center"
                                                x-text="device.branch_name ?? 'Belum Diset'"></td>
                                            <td class="text-center" x-text="device.name"></td>
                                            <td class="text-center" x-text="device.ip_address"></td>
                                            <td class="text-center">
                                        <span
                                            :class="device.online === 'Offline' ? 'badge bg-danger text-white' : 'badge bg-success text-white'"
                                            x-text="device.online"></span>
                                            </td>
                                            <td class="text-center">
                                                <span x-text="formatDate(device.last_query_date)"></span>
                                                <span x-text="device.last_query_date"></span>
                                            </td>
                                            <td class="d-flex flex-column">
                                                <template x-if="Number(editPermission) === 1">
                                                    <button class="btn btn-light-primary btn-sm mb-4"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal-fp-device"
                                                            @click="edit(device.id)">
                                                        <i class="bi bi-pencil"></i> Ubah Data
                                                    </button>
                                                </template>
                                                <template x-if="Number(testConnectionPermission) === 1">
                                                    <button class="btn btn-light-info btn-sm mb-4"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Test Koneksi Mesin"
                                                            @click="testConnection(device.id)"
                                                    >
                                                        <i class="bi bi-ethernet"></i>
                                                        Tes Koneksi
                                                    </button>
                                                </template>

                                                <button class="btn btn-light-danger btn-sm mb-4"
                                                        data-bs-placement="top" title="Tarik Data"
                                                        @click="edit(device.id)" data-bs-target="#modal-query-attlog"
                                                        data-bs-toggle="modal"
                                                >
                                                    <i class="bi bi-info-circle-fill"></i>
                                                    Ambil Data
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                            <ul class="pagination float-end mb-4 mt-4">
                                <template x-for="pagination in devices.links">
                                    <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                        <button class="page-link" @click="paginate(pagination.url)"
                                                x-html="pagination.label">
                                        </button>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        function accountTransactionHistory() {
            return {
                search: '',
                accountTransactions: [],
                id: "{{ $accountTransaction->id }}",
                async init() {
                    const resp = await axios.get(`/account-transactions/data/${this.id}`);
                    this.accountTransactions = resp.data
                    console.log(this.accountTransactions);
                },
                async paginate(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.accountTransactions = resp.data;
                    }
                },
            }
        }
    </script>
@endpush
