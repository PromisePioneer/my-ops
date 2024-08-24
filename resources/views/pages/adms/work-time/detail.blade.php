@extends('layouts.template')
@section('page-title', 'Pengaturan Shift')
@section('content')

    <div x-data="workTimeDetail()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex my-1 align-items-center">
                        <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                            <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                                   class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                        </div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <h6>Karyawan Dengan Jam Kerja {{ $workTime->name }}</h6>
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none"
                         data-kt-user-table-toolbar="selected">
                        <div class="fw-bolder me-5">
                            <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete
                            Selected
                        </button>
                    </div>
                </div>

            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    No
                                </th>
                                <th class="min-w-125px">NIK</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jabatan</th>
                                <th class="min-w-125px">ID Absen</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
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
                            <template x-if="!isLoading && users.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(user, index) in users?.data" :key="user.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="user.user.nip"></td>
                                    <td x-text="user.user.name"></td>
                                    <td>
                                        <span class="badge bg-danger"
                                              x-text="user.user.roles[0].name ?? 'Tidak Ada'"></span>
                                    </td>
                                    <td x-text="user.user.absent_id"></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" @click="destroy(shift.id)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        function workTimeDetail() {
            return {
                isLoading: false,
                startIndex: null,
                users: [],
                workTimeId: "{{ $workTime->id }}",
                async init() {
                    this.isLoading = true;
                    await this.getUserData();
                    this.isLoading = false
                },
                async getUserData() {
                    const resp = await axios.get(`/adms/work-time/detail/data/${this.workTimeId}`);
                    this.users = resp.data;
                    this.startIndex = this.users.from;
                }
            }
        }
    </script>
@endpush