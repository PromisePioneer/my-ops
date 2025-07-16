@extends('layouts.template')
@section('page-title', 'Cabang' . ' - ' . $branch->name)
@section('breadcrumbs', 'Data Absensi - Pengaturan Jam Kerja - Kantor Cabang - Jabatan')
@section('content')
    <div x-data="branchRoleDefaultWorkTimeData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.adms.work-time-settings.branch-role.form')
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
                    <h3 x-text="`Jam Kerja Kantor Cabang: ${branchDefaultWorkTime ?? 'Tidak Ada'}`"></h3>
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
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jam Kerja Bawaan (Pusat)</th>
                                <th class="min-w-125px">Jam Kerja Jabatan</th>
                            </thead>
                            <tbody class=" fw-bold text-center">
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
                            <template x-if="!isLoading && shifts.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(shift, index) in shifts?.data" :key="shift.id">
                                <tr>
                                    <td x-text="shift.name"></td>
                                    <td>
                                        <a href="#" x-text="shift.role_default_work_time ?? 'Tidak Ada'"></a>
                                    </td>
                                    <td>
                                        <template x-if="shift.actual_work_time_id === null">
                                            <button class="btn btn-sm btn-light-primary" type="button"
                                                    :disabled="Number(createPermission) !== 1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-branch-role-work-time"
                                                    @click="edit(null,shift.id, null)"
                                            >
                                                <x-icons.add-item/>
                                            </button>
                                        </template>
                                        <div class="d-flex align-items-center justify-content-around">
                                            <button :disabled="Number(createPermission) !== 1" data-bs-toggle="modal"
                                                    data-bs-target="#modal-branch-role-work-time"
                                                    class="btn btn-link me-2"
                                                    x-text="shift.branch_role_default_work_time"
                                                    @click="edit(shift.branch_id,shift.id, shift.work_time_id)"></button>
                                            <template x-if="shift.actual_work_time_id !== null">
                                                <button class="btn btn-sm btn-light-danger" type="button"
                                                        :disabled="Number(resetPermission) !== 1"
                                                        @click="resetWorkTime(shift.branch_id,shift.id, shift.actual_work_time_id)">
                                                    <x-icons.close/>
                                                    Reset
                                                </button>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ url('adms/work-time-settings') }}" class="btn btn-light-danger btn-sm"
                           @click="add()">
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
@endsection
@push('script')
    <script>
        function branchRoleDefaultWorkTimeData() {
            return {
                buttonLoading: false,
                isLoading: false,
                startIndex: null,
                shifts: null,
                selectedCheckBox: [],
                branchDefaultWorkTime: null,
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                branchId: '{{ $branch->id }}',
                shiftId: '',
                modalForm: new bootstrap.Modal(document.getElementById('modal-branch-role-work-time')),
                form: document.getElementById('form-branch-role-work-time'),
                createPermission: "{{ request()->user()->can('Tambah Data Jam Kerja Jabatan Di Cabang') }}",
                resetPermission: "{{ request()->user()->can('Reset Data Jam Kerja Jabatan Di Cabang') }}",
                async init() {
                    await this.getShiftsData();
                    await select2('.work-times-select2', 'Pilih Jam Kerja', '/select2/work-times-data');
                    await select2('.roles-select2', 'Pilih Jabatan', '/select2/roles-data');
                    await this.getBranchDefaultWorkTime();
                },
                add() {
                    this.editVal = '';
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get(`/adms/work-time-settings/branch-role/search/${this.branchId}`, {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.shifts = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getShiftsData() {
                    this.isLoading = true
                    try {
                        const resp = await axios.get(`/adms/work-time-settings/branch-role/data/${this.branchId}`);
                        this.shifts = resp.data;
                        this.startIndex = this.shifts.from;
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.shifts = resp.data
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/adms/work-time-settings/branch-role/store/${this.branchId}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        this.modalForm.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error?.response?.data?.errors;
                        if (respError) {
                            Object.keys(respError).map(err => toastr.error(respError[err][0]))
                        }
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(branchId = null, roleId, workTimeId = null) {
                    try {
                        await selectedValue('selected-role', `/select2/selected-role/${roleId}`);
                        await selectedValue('selected-work-time', `/select2/selected-work-time/${workTimeId}`);
                        const resp = await axios.get(`/adms/work-time-settings/branch-role/show/${branchId}/${roleId}/${workTimeId}`);
                        this.editVal = resp.data;
                    } catch (error) {
                        console.log(error)
                    }
                },
                async resetWorkTime(branchId, roleId, workTimeId) {
                    showConfirmModal("Anda yakin?", "Data akan direset.", "Ya, Reset!", async () => {
                        try {
                            await axios.delete(`/adms/work-time-settings/branch-role/reset/${branchId}/${roleId}/${workTimeId}`);
                            await showAlert('success', 'Data sukses direset');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getBranchDefaultWorkTime() {
                    this.isLoading = true
                    try {
                        const resp = await axios.get(`/adms/work-time-settings/branch/show-default-work-time/${this.branchId}`);
                        this.branchDefaultWorkTime = resp.data;
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
