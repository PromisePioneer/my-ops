@extends('layouts.template')
@section('page-title', 'Pengaturan Shift')
@section('content')

    <div x-data="manageShiftData ()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.adms.work-time.modal.create')
            @include('pages.adms.work-time.modal.edit')
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
                        <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-create">
                            <i class="ki-duotone ki-message-add fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Tambah
                        </button>
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jam Masuk</th>
                                <th class="min-w-125px">Jam Pulang</th>
                                <th class="min-w-125px">Mulai Check In</th>
                                <th class="min-w-125px">Akhir Check In</th>
                                <th class="min-w-125px">Mulai Check Out</th>
                                <th class="min-w-125px">Akhir Check Out</th>

                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <tbody class=" fw-bold">
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
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="shift.id"
                                                   :id="'checkbox-' + shift.id"/>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#" x-text="shift.name"></a>
                                    </td>
                                    <td x-text="shift.clock_in"></td>
                                    <td x-text="shift.clock_out"></td>
                                    <td x-text="shift.time_to_checkin"></td>
                                    <td x-text="shift.end_time_to_checkin"></td>
                                    <td x-text="shift.time_to_checkout"></td>
                                    <td x-text="shift.end_time_to_checkout"></td>
                                    <template x-if="shift.id !== 1">
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-edit" @click="edit(shift.id)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </template>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="text-danger">
                           Note: Jika karyawan tidak dijadwalkan dalam jam kerja tertentu maka jam kerja akan diset secara otomatis ke default
                        </span>
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
    @include('components.toast')
@endsection
@push('script')
    <script>
        function manageShiftData() {
            return {
                buttonLoading: false,
                isLoading: false,
                startIndex: null,
                shifts: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                shiftId: '',
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formCreate: document.getElementById('form-create'),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formEdit: document.getElementById('form-edit'),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getShiftsData();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/adms/work-time/search', {
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
                        const resp = await axios.get('/adms/work-time/data');
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
                toggleAllCheckBox() {
                    this.selectAll = !this.selectAll;
                    this.singleChecked = false;
                    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    this.selectedCheckBox = [];
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = this.selectAll;
                        if (this.selectAll) {
                            this.selectedCheckBox.push(checkbox.value);
                        }
                    });
                    this.selectedCheckBox.shift();
                },
                selectCheckBox(event) {
                    const checkboxId = event.target.value;
                    if (event.target.checked) {
                        this.selectedCheckBox.push(checkboxId);
                    } else {
                        const index = this.selectedCheckBox.indexOf(checkboxId);
                        if (index !== -1) {
                            this.selectedCheckBox.splice(index, 1);
                        }
                    }
                },
                async nextPage() {
                    if (this.shifts.next_page_url) {
                        const resp = await axios.get(`${this.shifts.next_page_url}`);
                        this.startIndex = this.shifts.from
                        this.shifts = resp.data
                    }
                },
                async previousPage() {
                    if (this.shifts.prev_page_url) {
                        const resp = await axios.get(`${this.shifts.prev_page_url}`);
                        this.startIndex = this.shifts.from
                        this.shifts = resp.data
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/adms/work-time/', new FormData(this.formCreate))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreate.reset();
                        this.modalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/adms/work-time/${id}`);
                    this.editVal = resp.data;
                },
                async assignUserShift(id) {
                    this.shiftId = id;
                    await this.getUserData();
                    await this.selectedUserShift();
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/adms/work-time/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formEdit.reset();
                        this.modalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/adms/work-time/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },

                async selectedUserShift() {
                    const selectedUserShift = $('#selectedUserShift');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/adms/work-time/user/selected/${this.shiftId}`,
                    });

                    response.forEach(user => {
                        const option = new Option(user.name, user.id, true, true);
                        selectedUserShift.append(option).trigger('change').trigger({
                            type: 'select2:select',
                            params: {results: user}
                        });
                    });
                },
            }
        }
    </script>
@endpush
