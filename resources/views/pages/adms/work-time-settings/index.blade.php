@extends('layouts.template')
@section('page-title', 'Pengaturan Jam Kerja Kantor Cabang')
@section('breadcrumbs', 'Data Absensi - Pengaturan Jam Kerja - Kantor Cabang')
@section('content')
    <div x-data="branchDefaultWorkTimeData()">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Informasi</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 fs-5">
                        <ol>
                            <li class="text-danger">
                                <i>
                                    Jika pengaturan jam kerja kantor cabang tidak diatur, maka akan menggunakan
                                    pengaturan jam kerja default yaitu pagi (08:00 - 17:00)
                                </i>
                            </li>
                            <li class="text-danger">
                                <i>
                                    Jam kerja kantor cabang akan berlaku untuk semua karyawan yang ada di cabang,
                                    jadi jika
                                    ingin mengatur jam
                                    kerja untuk karyawan tertentu, maka harus diatur di menu pengaturan jadwal
                                    libur,
                                </i>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.adms.work-time-settings.form')
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
            </div>
            <div class="card-body py-3">
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
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Cabang</th>
                                <th class="min-w-125px">Jam Kerja Universal</th>
                            </thead>
                            <tbody class=" fw-bold text-center">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="4">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && shifts.data?.length === 0">
                                <tr>
                                    <td colspan="4">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(shift, index) in shifts?.data" :key="shift.id">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="shift.id"
                                                   :id="'checkbox-' + shift.id"/>
                                        </div>
                                    </td>
                                    <td x-text="shift.name"></td>
                                    <td>
                                        <template x-for="workTime in shift.work_time">
                                            <a href="#"
                                               data-bs-target="#modal-branch-work-time"
                                               data-bs-toggle="modal"
                                               x-text="workTime.name"
                                               @click="edit(workTime.id, shift.id)">
                                            </a>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ url('adms/work-time/') }}" class="btn btn-light-danger btn-sm">
                            <x-icons.back/>
                            Kembali
                        </a>
                        <ul class="pagination">
                            <template x-for="pagination in shifts?.links">
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
    @include('components.select2.script')
    @include('components.toast')
@endsection
@push('script')
    <script>
        function branchDefaultWorkTimeData() {
            return {
                isLoading: true,
                shifts: [],
                selectedCheckBox: [],
                editVal: '',
                buttonLoading: false,
                selectAll: false,
                singleChecked: false,
                search: '',
                formDelete: document.getElementById('form-delete'),
                form: document.getElementById('form-branch-work-time'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-branch-work-time')),
                async init() {
                    await select2('.main-branches-select2', 'Pilih Cabang', '/select2/main-branches-data');
                    await select2('.work-times-select2', 'Pilih Jam Kerja', '/select2/work-times-data');
                    await this.getShiftsData();
                },
                async getShiftsData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/adms/work-time-settings/data');
                        this.shifts = resp.data;
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/adms/work-time-settings/store', new FormData(this.form));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.form.reset();
                        this.modalForm.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(workTimeId, branchId) {
                    try {
                        const resp = await axios.get(`/adms/work-time-settings/edit/${branchId}/${workTimeId}`);
                        this.editVal = resp.data;
                        await selectedValue('selected-main-branch', `/select2/selected-branch/${this.editVal?.branch_id ?? branchId}`);
                        await selectedValue('selected-work-time', `/select2/selected-work-time/${this.editVal?.work_time_id ?? workTimeId}`);

                    } catch (error) {
                        console.log(error)
                    }
                },
                async paginationEndPoint(url) {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(url);
                        this.shifts = resp.data;
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>

@endpush
