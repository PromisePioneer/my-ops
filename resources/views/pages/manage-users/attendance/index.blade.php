@extends('layouts.template')
@section('page-title', 'Data Absen')
@section('content')
    <div x-data="attendanceData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.manage-users.attendance.modal.create')
            @include('pages.manage-users.attendance.modal.edit')
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <button type="button" @click="add()" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modal-create">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">No</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">NIP</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jabatan</th>
                                <th class="min-w-125px">Department</th>
                                <th class="min-w-125px">Clock in</th>
                                <th class="min-w-125px">Clock out</th>
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
                            <template x-if="!isLoading && attendance.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(absent, index) in attendance?.data" :key="absent.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="absent.date"></td>
                                    <td x-text="absent.user.nip"></td>
                                    <td x-text="absent.user.name"></td>
                                    <td x-text="absent.user.roles[0].name"></td>
                                    <td x-text="absent.job_information?.department.name ?? '-'"></td>
                                    <td x-text="absent.clock_in"></td>
                                    <td x-text="absent.clock_out"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function attendanceData() {
            return {
                isLoading: false,
                buttonLoading: false,
                attendance: [],
                startIndex: null,
                search: '',
                editVal: '',
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formCreate: document.getElementById('form-create'),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formEdit: document.getElementById('form-edit'),
                async init() {
                    await this.getAttendanceData();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        this.attendance = await axios.get('/manage-users/attendance/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }

                },
                async nextPage() {
                    if (this.attendance.next_page_url) {
                        const resp = await axios.get(`${this.attendance.next_page_url}`);
                        this.startIndex = this.resp.from
                        this.attendance = resp.data
                    }
                },
                async previousPage() {
                    if (this.attendance.prev_page_url) {
                        const resp = await axios.get(`${this.attendance.prev_page_url}`);
                        this.startIndex = this.resp.from
                        this.attendance = resp.data
                    }
                },
                async add() {
                    await this.getUserData();
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/manage-users/attendance/', new FormData(this.formCreate));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.modalCreate.hide();
                        this.formCreate.reset();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getAttendanceData() {
                    this.isLoading = true
                    try {
                        const resp = await axios.get('/manage-users/attendance/data');
                        this.attendance = resp.data;
                        this.startIndex = this.attendance.from;
                    } catch (error) {
                        console.error(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getUserData() {
                    $(".users-select2").select2({
                        ajax: {
                            url: '/manage-users/attendance/users/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                }
            }
        }
    </script>
@endpush
