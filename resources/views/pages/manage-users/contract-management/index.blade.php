@extends('layouts.template')
@section('content')

    <div x-data="contractData()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="mb-0">Filter Data</h3>
                        </div>
                    </div>
                    <form id="form-filter" @submit.prevent="filter()">
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600">
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid branch-select2"
                                            name="branch_id" id="branch_id">
                                    </select>
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <input type="number" name="year" id="year" class="form-control form-control-solid"
                                           placeholder="Filter Berdasarkan Tahun">
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid"
                                            name="month" id="month" data-control="select2"
                                            data-placeholder="Pilih Bulan">
                                        <option></option>
                                        <template x-for="month in months" :key="index">
                                            <option :value="month.number" x-text="month.name"></option>
                                        </template>
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
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header pt-5">
                        <div class="card-title">
                            <h3>Data Karyawan Kontrak</h3>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1"
                                 data-kt-view-roles-table-toolbar="base">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viemanagewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="17.0365" y="15.1223"
                                                                      width="8.15546" height="2" rx="1"
                                                                      transform="rotate(45 17.0365 15.1223)"
                                                                      fill="black"></rect>
																<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                                      fill="black"></path>
															</svg>
														</span>
                                <input type="text" class="form-control form-control-solid w-250px ps-15"
                                       x-model="search" @input.debounce="searchData()" placeholder="Cari...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div id="kt_roles_view_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0 dataTable no-footer"
                                       id="kt_roles_view_table">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-200px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending"
                                            style="width: 78.7969px;">
                                            Nama
                                        </th>
                                        <th class="min-w-5px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="User: activate to sort column ascending"
                                            style="width: 309.844px;">
                                            Masa Berlaku
                                        </th>
                                        <th class="min-w-10px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Joined Date: activate to sort column ascending"
                                            style="width: 180.359px;">
                                            Status
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Joined Date: activate to sort column ascending"
                                            style="width: 180.359px;">
                                            Salinan Kontrak
                                        </th>
                                        <th class="text-end min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                            aria-label="Actions" style="width: 135.25px;">
                                            Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                    <template x-if="isLoading">
                                        <tr>
                                            <td colspan="5">
                                                <div style="text-align: center;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="!isLoading && contracts.data?.length === 0">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(contract, index) in contracts.data" :key="contract.id">
                                        <tr>
                                            <td>
                                                <a :href="`/manage-users/users/detail/${contract.id}`"
                                                   class="text-gray-800 text-hover-primary mb-1">
                                                    <span x-text="contract.name"></span>
                                                </a>
                                            </td>
                                            <td x-text="contract.contract_date"></td>
                                            <template x-if="contract.expired === true">
                                                <td>
                                                    <span class="badge bg-danger">Sudah Habis</span>
                                                </td>
                                            </template>
                                            <template x-if="contract.expired === false">
                                                <td>
                                                    <span class="badge bg-success">Masih Berlaku</span>
                                                </td>
                                            </template>
                                            <template x-if="contract.expired === false">
                                                <td>
                                                    <a :href="`/manage-users/contract-management/contract-pdf/${contract.id}`"
                                                       class="btn btn-active-danger btn-light-danger btn-sm">
                                                        <i class="bi bi-file-pdf-fill"></i>
                                                    </a>
                                                </td>
                                            </template>
                                            <template x-if="contract.expired === true">
                                                <td>
                                                    <button disabled
                                                            class="btn btn-active-danger btn-light-danger btn-sm">
                                                        <i class="bi bi-file-pdf-fill"></i>
                                                    </button>
                                                </td>
                                            </template>
                                            <template x-if="contract.expired === true">
                                                <td class="text-end">
                                                    <button class="btn btn-light btn-active-info btn-sm"
                                                            @click="extendContract(contract.id)">
                                                        Extend
                                                    </button>
                                                </td>
                                            </template>
                                            <template x-if="contract.expired === false">
                                                <td class="text-end">
                                                    <button class="btn btn-light btn-active-info btn-sm" disabled>
                                                        Extend
                                                    </button>
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-10">
                                <div class="col-sm-12  d-flex align-items-center justify-content-center">
                                    <template x-for="pagination in contracts.links">
                                        <ul class="pagination">
                                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                                <button class="page-link" @click="paginationEndPoint(pagination.url)"
                                                        x-html="pagination.label">
                                                </button>
                                            </li>
                                        </ul>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection


@push('script')
    <script>
        function contractData() {
            return {
                buttonLoading: false,
                isLoading: false,
                startIndex: null,
                contracts: [],
                months: [],
                search: '',
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                async init() {
                    await this.getContractData();
                    await this.getBranchData();
                    await this.getMonth();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/manage-users/contract-management/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.contracts = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async filter() {
                    const year = document.getElementById('year')?.value ?? '';
                    const month = document.getElementById('month')?.value ?? '';
                    const branch_id = $(".branch-select2")?.val();
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/contract-management/filter', {
                            params: {
                                month: month,
                                year: year,
                                branch_id: branch_id,
                            }
                        });
                        this.contracts = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.contracts = resp.data
                    }
                },
                getMonth() {
                    this.months.push(
                        {name: "Januari", number: '01'},
                        {name: "Februari", number: '02'},
                        {name: "Maret", number: '3'},
                        {name: "April", number: '04'},
                        {name: "Mei", number: '05'},
                        {name: "Juni", number: '06'},
                        {name: "Juli", number: '07'},
                        {name: "Agustus", number: '08'},
                        {name: "September", number: '09'},
                        {name: "Oktober", number: '10'},
                        {name: "November", number: '11'},
                        {name: "Desember", number: '12'},
                    )
                },
                async nextPage() {
                    if (this.contracts.next_page_url) {
                        const resp = await axios.get(`${this.contracts.next_page_url}`);
                        this.startIndex = this.contracts.from
                        this.contracts = resp.data
                    }
                },
                async previousPage() {
                    if (this.contracts.prev_page_url) {
                        const resp = await axios.get(`${this.contracts.prev_page_url}`);
                        this.startIndex = this.contracts.from
                        this.contracts = resp.data
                    }
                },

                async extendContract(id) {
                    showConfirmModal("Anda yakin?", "Perpanjang Kontrak ?", "Ya, Perpanjang!", async () => {
                        try {
                            await axios.post(`/manage-users/contract-management/extend-contract/${id}`)
                            await showAlert('success', 'Data sukses dieksekusi');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getBranchData() {
                    $(".branch-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/manage-users/contract-management/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    });
                },
                async getContractData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/contract-management/data');
                        this.contracts = resp.data;
                        this.startIndex = this.contracts.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
            }
        }
    </script>
@endpush