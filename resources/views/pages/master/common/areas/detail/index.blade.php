@extends('layouts.template')
@section('page-title', 'Detail Area')
@section('breadcrumbs', 'Master Umum - Area - Detail Area')
@section('content')
    <div x-data="userHasArea()">
        @include('pages./master.common.areas.detail.form')
        @include('pages./master.common.areas.detail.holiday')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
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
                            <template x-if="Number(createPermission) === 1">
                                <button type="button" class="btn btn-light-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-detail-area">
                                    <i class="ki-duotone ki-message-add fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i> Tambah
                                </button>
                            </template>
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
                        <table class="table table-bordered align-middle fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox"
                                               @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jabatan</th>
                                <th class="min-w-125px">Jadwal Libur</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">
                                                    Loading...
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && usersArea.data?.length === 0">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="area in usersArea?.data" :key="area.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td>
                                        <template x-if="Number(destroyPermission) === 1">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                 @click="selectCheckBox($event)">
                                                <input class="form-check-input" type="checkbox" :value="area.id"
                                                       :id="'checkbox-' + area.id"/>
                                            </div>
                                        </template>
                                    </td>
                                    <td x-text="area.user_name"></td>
                                    <td x-text="area.role_name ?? '-'"></td>
                                    <td>
                                        <button class="btn btn-light-info btn-sm" data-bs-target="#modal-pick-holiday"
                                                @click="show(area.id)"
                                                data-bs-toggle="modal"
                                                x-text="area.week_holiday ?? 'Pilih Jadwal Libur'">
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ url('/master/common/area') }}" class="btn btn-light-danger btn-sm">Kembali</a>
                        <ul class="pagination float-end mb-4 mt-4">
                            <template x-for="pagination in usersArea.links">
                                <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                    <button class="page-link" @click="paginate(pagination.url)"
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
        function userHasArea() {
            return {
                createPermission: "{{ request()->user()->can('Tambah Detail Data Area') }}",
                destroyPermission: "{{ request()->user()->can('Hapus Detail Data Area') }}",
                holidayModal: new bootstrap.Modal(document.getElementById('modal-pick-holiday')),
                holidayForm: document.getElementById('form-pick-holiday'),
                buttonLoading: false,
                isLoading: false,
                id: "{{ $area->id }}",
                search: '',
                usersArea: [],
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                modalForm: new bootstrap.Modal(document.getElementById('modal-detail-area')),
                form: document.getElementById('form-detail-area'),
                formDelete: document.getElementById('form-delete'),
                days: [],
                editVal: '',
                async init() {
                    await this.getAssociatedUsers();
                    await this.getUserData();
                    this.days.push(
                        {value: 'Minggu', label: 'Minggu'},
                        {value: 'Senin', label: 'Senin'},
                        {value: 'Selasa', label: 'Selasa'},
                        {value: 'Rabu', label: 'Rabu'},
                        {value: 'Kamis', label: 'Kamis'},
                        {value: 'Jumat', label: "Jum'at"},
                        {value: 'Sabtu', label: 'Sabtu'},
                    )
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/master/common/area-detail/search/${this.id}`, {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.usersArea = resp.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getAssociatedUsers() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/master/common/area-detail/data/${this.id}`);
                        this.usersArea = resp.data;
                    } catch (e) {
                        console.log(e)
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
                async getUserData() {
                    $(".users-select2").select2({
                        placeholder: 'Pilih KCA/WKCA/Teknisi',
                        allowClear: true,
                        ajax: {
                            url: `/master/common/area-detail/users/data/${this.id}`,
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async paginate(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.usersArea = resp.data
                    }
                },
                async show(id) {
                    const resp = await axios.get(`/master/common/area-detail/show/${id}`);
                    this.editVal = resp.data;
                    console.log(this.editVal);
                },

                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/common/area-detail/${this.id}`, new FormData(this.form))
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
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/common/area-detail/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            this.selectedCheckBox = [];
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async pickHoliday(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/common/area-detail/save-week-holiday/${id}`, new FormData(this.holidayForm));
                        await showAlert('success', 'Data berhasil disimpan');
                        await this.holidayModal.hide();
                        await this.holidayForm.reset();
                        await this.init();
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.buttonLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
