@extends('layouts.template')
@section('page-title', 'Jabatan')
@section('breadcrumbs', 'Master Umum - Jabatan - Edit')
@section('content')
    <div x-data="generateRole()">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-light-info btn-sm mb-6" href="{{ url('common-master-data/roles/') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex flex-column scroll-y me-n7 pe-7"
                             data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                             data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header"
                             data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px"
                             style="max-height: 627px;">
                            <div class="row mb-7">
                                <div class="col-md-6">
                                    <label class="fs-5 fw-bolder form-label mb-2">
                                        <span class="required">Nama Jabatan</span>
                                    </label>
                                    <input class="form-control form-control-solid" name="name"
                                           placeholder="Nama Jabatan" value="{{ $role->name }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="fs-5 fw-bolder form-label mb-2">
                                        <span class="required">Department</span>
                                    </label>
                                    <select class="form-select form-select-solid departments-select2"
                                            name="department_id"
                                            id="selectedDepartment">
                                        <option value="0">Pilih</option>
                                    </select>
                                </div>
                            </div>
                            <div class="fv-row">
                                <div class="d-flex align-items-center justify-content-between mb-10">
                                    <label class="fs-5 fw-bolder form-label">Hak Akses Menu</label>
                                    <input type="text" name="search" x-model="search"
                                           @input.debounce="searchPermissionData()"
                                           class="form-control form-control-solid w-250px"
                                           placeholder="Search...">

                                </div>
                                <div class="row justify-content-center align-items-center">
                                    <template x-if="permissions.length === 0">
                                        <div class="col-md-12">
                                            <div class="fv-row">
                                                <div class="fv-plugins-message-container invalid-feedback">
                                                    <div class="fv-help-block text-center fw-bold">
                                                        Data tidak ditemukan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-for="(permission, index) in permissions" :key="index">
                                        <div class="col-md-6">
                                            <div class="form-check mb-4">
                                                <label
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                    <input class="form-check-input" type="checkbox"
                                                           :value="permission.name"
                                                           :checked="selectedPermissions.includes(permission.name)"
                                                           name="permission[]"
                                                           multiple
                                                    >
                                                    <span class="form-check-label text-capitalize text-gray-600 fw-bold"
                                                          x-text="permission.name">
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="separator py-2"></div>

                    <div class="float-end d-flex py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2 btn-sm">Reset</button>
                        <button type="submit" class="btn btn-sm btn-light-primary"
                                :disabled="buttonLoading">
                            <i class="ki-duotone ki-click fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                            <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function generateRole() {
            return {
                id: "{{ $role->id }}",
                buttonLoading: false,
                selectedCheckBox: [],
                permissions: [],
                selectedPermissions: [],
                selectAll: false,
                singleChecked: false,
                form: document.getElementById('form'),
                search: '',
                async init() {
                    await this.getDepartments();
                    await this.selectedPermission();
                    await this.selectedDepartment();
                    await this.getPermissionsData();
                },
                async searchPermissionData() {
                    const resp = await axios.get('/common-master-data/roles/permissions/search', {
                        params: {
                            search: this.search
                        }
                    });
                    this.permissions = resp.data;
                },
                async getPermissionsData() {
                    try {
                        const resp = await axios.get('/common-master-data/roles/permissions/data');
                        this.permissions = resp.data;
                    } catch (e) {
                        console.log(e);
                    }
                },
                async selectedPermission() {
                    try {
                        const resp = await axios.get(`/common-master-data/roles/permissions/data/selected/${this.id}`);
                        this.selectedPermissions = resp.data;
                    } catch (e) {
                        console.log(e);
                    }
                },
                async save() {
                    this.buttonLoading = true
                    try {
                        await axios.post(`/common-master-data/roles/update/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan');
                        window.location.href = '/common-master-data/roles/';
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getDepartments() {
                    $(".departments-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Departement",
                        ajax: {
                            url: '/select2/departments-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedDepartment() {
                    const selectedDepartment = $('#selectedDepartment');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/common-master-data/roles/departments/data/selected/${this.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedDepartment.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
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
            }
        }
    </script>
@endpush

