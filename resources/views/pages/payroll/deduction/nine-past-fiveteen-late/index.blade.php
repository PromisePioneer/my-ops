@extends('layouts.template')
@section('page-title', 'Denda 9.15')
@section('content')
    <div x-data="slaDeduction()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.payroll.deduction.nine-past-fiveteen-late.modal.create')
            @include('pages.payroll.deduction.nine-past-fiveteen-late.modal.edit')
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
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-create">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12 ">
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

                </div>
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
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">KCA</th>
                                <th class="min-w-125px">Teknisi</th>
                                <th class="min-w-125px">Total Terlambat</th>
                                <th class="min-w-125px">Total Denda</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && nineFiveteenLateness.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="late in nineFiveteenLateness?.data"
                                      :key="late.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox"
                                                   :value="late.id"
                                                   :id="'checkbox-' + late.id"/>
                                        </div>
                                    </td>
                                    <td x-text="late.date"></td>
                                    <td x-text="late.kca"></td>
                                    <td x-text="late.technician"></td>
                                    <td x-text="late.total_amount_of_late">
                                    <td x-text="`Rp. ${late.amount}`">
                                    </td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(late.id)">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ url('payroll/setting/') }}" class="btn btn-light-info btn-sm">
                            <i class="ki-duotone ki-black-left"></i>
                        </a>
                        <ul class="pagination float-end mb-4 mt-4">
                            <template x-for="pagination in nineFiveteenLateness.links">
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
@endsection
@push('script')
    <script>
        $('.date').flatpickr();

        function slaDeduction() {
            return {
                nineFiveteenLateness: [],
                isLoading: false,
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                startIndex: null,
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getSlaDeductionData();
                    await this.getUserData();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/payroll/setting/deduction/nine-past-fiveteen-late/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.nineFiveteenLateness = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
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
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.nineFiveteenLateness = resp.data
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/payroll/setting/deduction/nine-past-fiveteen-late/', new FormData(this.formCreate))
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
                    const resp = await axios.get(`/payroll/setting/deduction/nine-past-fiveteen-late/${id}`)
                    this.editVal = resp.data;
                    await this.selectedRole();
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/payroll/setting/deduction/nine-past-fiveteen-late/${id}`, new FormData(this.formEdit))
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
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post('/payroll/setting/deduction/nine-past-fiveteen-late/destroy', new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getSlaDeductionData() {
                    const resp = await axios.get('/payroll/setting/deduction/nine-past-fiveteen-late/data');
                    this.nineFiveteenLateness = resp.data
                    this.startIndex = this.nineFiveteenLateness.from
                },
                async getUserData() {
                    $(".users-select2").select2({
                        placeholder: "Pilih Karyawan",
                        ajax: {
                            url: '/payroll/setting/deduction/nine-past-fiveteen-late/user/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedRole() {
                    const selectedUser = $('#selectedUser');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/payroll/setting/deduction/nine-past-fiveteen-late/user/selected/${this.editVal.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedUser.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
            }
        }
    </script>
@endpush
