@extends('layouts.template')
@section('page-title', 'Pengaturan Jam Kerja')
@section('breadcrumbs', 'Data Absensi - Pengaturan Jam Kerja')
@section('content')

    <div x-data="manageShiftData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.adms.work-time.form')
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
                        <a href="{{ url('adms/work-time/branch/detail') }}" class="btn btn-light-info btn-sm me-2">
                            <x-icons.electronic-clock/>
                            Jam Kerja Cabang
                        </a>
                        <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-work-time">
                            <x-icons.add-item/>
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
                        <table class="table align-middle table-bordered fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jam Kerja</th>
                                <th class="min-w-125px">Batas Checkin</th>
                                <th class="min-w-125px">Batas Checkout</th>
                                <th class="min-w-125px">Jam Kerja Default</th>
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
                                    <td x-text="`${shift.clock_in} - ${shift.clock_out}`"></td>
                                    <td x-text="`${shift.time_to_checkin} - ${shift.end_time_to_checkin}`"></td>
                                    <td x-text="`${shift.time_to_checkout} - ${shift.end_time_to_checkout}`"></td>
                                    <td>
                                        <input type="checkbox" class="form-check-input"
                                               :checked="shift.is_default === 1"
                                               @click="setGlobalDefaultWorkTime(shift.id)">
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="text-danger">
                           Note: Jam Kerja Default adalah jam kerja yang akan digunakan untuk pengguna yang belum memiliki jam kerja yang diatur sendiri.
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
                modalForm: new bootstrap.Modal(document.getElementById('modal-work-time')),
                form: document.getElementById('form-work-time'),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getShiftsData();
                },
                add() {
                    this.editVal = '';
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
                async setGlobalDefaultWorkTime(id) {
                    this.isLoading = true;
                    try {
                        await axios.post(`/adms/work-time/set-global-default-work-time/${id}`);
                        await showAlert('success', 'Data berhasil disimpan');
                        await this.init();
                    } catch (e) {
                        console.log(e)
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
                async save(id) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/adms/work-time/', new FormData(this.form))
                        } else {
                            await axios.post(`/adms/work-time/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
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
                async edit(id) {
                    const resp = await axios.get(`/adms/work-time/${id}`);
                    this.editVal = resp.data;
                },
                async destroy() {
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
            }
        }
    </script>
@endpush
