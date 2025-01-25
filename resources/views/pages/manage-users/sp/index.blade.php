@extends('layouts.template')
@section('page-title', 'Manajemen Surat Peringatan')
@section('content')
    <div x-data="spData()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-400px mb-10" x-show="spDetailCard !== null" x-transition
                 x-cloak>
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0" x-text="spDetailCard?.sp_number"></h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column text-gray-600">
                            <div class="d-flex align-items-center py-2">
                                <span class="fw-bold text-gray-600">Cabang: &nbsp;</span>
                                <span x-text="spDetailCard?.branch?.name ?? 'Pusat'"></span>
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <span class="fw-bold text-gray-600">NAMA: &nbsp;</span>
                                <span x-text="spDetailCard?.user_id"></span>
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <span class="fw-bold text-gray-600">TIPE SP : &nbsp;</span>
                                <span class="badge bg-danger" x-text="spDetailCard?.sp_type"></span>
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <span class="fw-bold text-gray-600">Tanggal Berlaku : &nbsp;</span>
                                <span x-text="spDetailCard?.date"></span>
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <span class="fw-bold text-gray-600">Diberi Sanksi Oleh : &nbsp;</span>
                                <span x-text="spDetailCard?.punished_by"></span>
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <span class="fw-bold text-gray-600">Dibuat Oleh : &nbsp;</span>
                                <span x-text="spDetailCard?.created_by"></span>
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <span class="fw-bold text-gray-600">File : &nbsp;</span>
                                <a :href="`/manage-users/sp/export-pdf/${spDetailCard?.id}`"
                                   class="btn btn-danger btn-sm">
                                    <i class="bi bi-file-pdf-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header pt-5">
                        <div class="card-title">
                            <a href="{{ url('manage-users/sp/create') }}"
                               class="btn btn-light btn-active-primary btn-sm mx-1">
                                <i class="bi bi-plus-circle-fill"></i> Tambah
                            </a>
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
                                        <th class="min-w-50px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending">
                                            Nomor SP
                                        </th>
                                        <th class="min-w-50px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending">
                                            Cabang
                                        </th>
                                        <th class="min-w-150px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1"
                                            aria-label="User: activate to sort column ascending">
                                            Karyawan
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Joined Date: activate to sort column ascending">
                                            Status
                                        </th>
                                        <th class="text-end min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                            aria-label="Actions">
                                            Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <template x-if="isLoading">
                                        <tbody class="fw-bold text-gray-600">
                                        <tr>
                                            <td colspan="5">
                                                <div style="text-align: center;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-if="!isLoading && spList.data?.length === 0">
                                        <tbody class="fw-bold text-gray-600">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="sp in spList.data" :key="sp.id">
                                        <tbody class="fw-bold">
                                        <tr @click="spDetail(sp.id)" style="cursor: pointer"
                                            :class="{'table-active': spDetailCard?.id === sp.id}">
                                            <td x-text="sp.sp_number"></td>
                                            <td x-text="sp.branch_name ?? 'Pusat'"></td>
                                            <td x-text="sp.user_id"></td>
                                            <td>
                                            <span x-text="sp.expired  ? 'Masih Berlaku' : 'Sudah Habis'"
                                                  :class="sp.expired ? 'badge bg-success text-white fs-6'  : 'badge bg-danger text-white fs-6'"></span>
                                            </td>
                                            <td class="text-end">
                                                <a :href="`/manage-users/sp/${sp.id}`"
                                                   class="btn btn-light btn-active-primary btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button class="btn btn-light btn-active-danger btn-sm"
                                                        @click="destroy(sp.id)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                        </tr>
                                        </tbody>
                                    </template>
                                </table>
                            </div>
                            <ul class="pagination float-end mb-4 mt-4">
                                <template x-for="pagination in spList.links">
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
        </div>
    </div>
        @include('components.toast')
@endsection
@push('script')
    <script>
        function spData() {
            return {
                isLoading: false,
                spList: [],
                startIndex: null,
                selected: null,
                search: '',
                spDetailCard: null,
                async init() {
                    await this.getSpData();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/manage-users/sp/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.spList = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getSpData() {
                    const resp = await axios.get('/manage-users/sp/data');
                    this.spList = resp.data;
                    this.startIndex = this.spList.from;
                },
                async nextPage() {
                    if (this.spList.next_page_url) {
                        const resp = await axios.get(`${this.spList.next_page_url}`);
                        this.spList = resp.data
                        this.startIndex = this.spList.from
                    }
                },
                async previousPage() {
                    if (this.spList.prev_page_url) {
                        const resp = await axios.get(`${this.spList.prev_page_url}`);
                        this.spList = resp.data
                        this.startIndex = this.spList.from
                    }
                },
                async spDetail(id) {
                    const resp = await axios.get(`/manage-users/sp/show/${id}`);
                    this.spDetailCard = resp.data
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/manage-users/sp/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                }
            }
        }
    </script>
@endpush
