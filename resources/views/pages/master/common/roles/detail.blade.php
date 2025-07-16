@extends('layouts.template')
@section('content')
    <div x-data="associatedUserData()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">{{ $role->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header pt-5">
                        <div class="card-title">
                            <h2 class="d-flex align-items-center">Total Karyawan
                                <span class="text-gray-600 fs-6 ms-1"
                                      x-text="`(${users.total_user ?? '-'})`"></span></h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1"
                                 data-kt-view-roles-table-toolbar="base">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="17.0365" y="15.1223"
                                                                      width="8.15546" height="2" rx="1"
                                                                      transform="rotate(45 17.0365 15.1223)"
                                                                      fill="black"></rect>
																<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                                      fill="black"></path>
															</svg>
														</span>
                                <input type="text" data-kt-roles-table-filter="search"
                                       class="form-control form-control-solid w-250px ps-15" placeholder="Search Users">
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
                                            Cabang
                                        </th>
                                        <th class="min-w-50px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending"
                                            style="width: 78.7969px;">
                                            NIK
                                        </th>
                                        <th class="min-w-150px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="User: activate to sort column ascending"
                                            style="width: 309.844px;">
                                            Karyawan
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Joined Date: activate to sort column ascending"
                                            style="width: 180.359px;">
                                            Joined Date
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
                                            <td colspan="9">
                                                <div style="text-align: center;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="!isLoading && users.data?.data?.length === 0">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="user in users.data?.data" :key="user.id">
                                        <tr>
                                            <td x-text="user.branch?.name ?? 'Pusat'"></td>
                                            <td x-text="user.nip"></td>
                                            <td class="d-flex align-items-center">
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="#">
                                                        <div class="symbol-label">
                                                            <img :src="getImageURL(user.profile_pic ?? null)"
                                                                 @click="$dispatch('lightbox', `${getImageURL(user.profile_pic) ?? null}`)"
                                                                 alt="Foto Karyawan" class="w-100"/>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a :href="`/manage-users/users/detail/${user.id}`"
                                                       class="text-gray-800 text-hover-primary mb-1"
                                                       x-text="user.name"></a>
                                                    <span x-text="user.email"></span>
                                                </div>
                                            </td>
                                            <td x-text="user.join_date"></td>
                                            <td class="text-end">
                                                <button class="btn btn-light btn-active-danger btn-sm"
                                                        @click="destroy(user.id)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                    <a href="{{ url('/master/common/roles') }}"
                                       class="btn btn-light btn-active-danger btn-sm">Kembali</a>
                                </div>
                                <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                    <div class="dataTables_paginate paging_simple_numbers"
                                         id="kt_roles_view_table_paginate">
                                        <ul class="pagination float-end mb-4">
                                            <li class="page-item previous">
                                                <button class="btn btn-light btn-sm" @click="previousPage">
                                                    Previous
                                                </button>
                                            </li>
                                            <li class="page-item next">
                                                <button class="btn btn-light btn-sm" @click="nextPage">
                                                    Next
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
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
        function associatedUserData() {
            return {
                isLoading: false,
                roleId: "{{ $role->id }}",
                users: [],
                startIndex: null,
                async init() {
                    await this.getUserData()
                },
                async getUserData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/master/common/roles/detail/associated-users/${this.roleId}`);
                        this.users = resp.data;
                        this.startIndex = this.users.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = 'assets/media/dummy/dummy-picture.png'
                        return "{{ asset('') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
                async nextPage() {
                    if (this.users.next_page_url) {
                        const resp = await axios.get(`${this.users.next_page_url}`);
                        this.startIndex = this.users.from
                        this.users = resp.data
                    }
                },
                async previousPage() {
                    if (this.users.prev_page_url) {
                        const resp = await axios.get(`${this.users.prev_page_url}`);
                        this.startIndex = this.users.from
                        this.users = resp.data
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/manage-users/users/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
            }
        }
    </script>
@endpush
