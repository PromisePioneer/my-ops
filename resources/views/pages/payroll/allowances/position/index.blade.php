@extends('layouts.template')
@section('page-title', 'Tunjangan Jabatan')
@section('content')
    <div x-data="positionAllowancesData()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column text-gray-600">
                            <div class="d-flex align-items-center py-2">
                                <select class="form-select form-select-solid main-branches-select2" name="branch_id"
                                        id="branch_id">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pt-4 text-end">
                        <button type="button" @click="filter()"
                                class="btn btn-light btn-active-primary btn-sm">
                            Filter
                        </button>
                    </div>

                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
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
                        <div class="py-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered " id="kt_table_users">
                                    <thead>
                                    <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-100px pe-2 text-center">#</th>
                                        <th class="min-w-125px">Nama</th>
                                        <th class="min-w-250px">Tunjangan Jabatan</th>
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
                                    <template x-if="!isLoading && positionAllowances.data?.length === 0">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="(positionAllowance, index) in positionAllowances?.data"
                                              :key="positionAllowance.id">
                                        <tbody class="fw-bold ">
                                        <tr>
                                            <td class="text-center" x-text="startIndex + index++">
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <a :href="`/manage-users/users/detail/${positionAllowance.id}`"
                                                       x-text="`(${positionAllowance.nip}) ${positionAllowance.name}`"></a>
                                                    <div class="d-flex align-items-center mb-2">
                                                 <span class="badge bg-info text-white"
                                                       x-text="`NIK : ${positionAllowance.nip}`"></span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                 <span class="badge bg-info text-white"
                                                       x-text="`Jabatan : ${positionAllowance.role_name}`"></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" @click="edit(positionAllowance.id)"
                                                   x-text="positionAllowance.position_allowance ? positionAllowance.position_allowance : 'Tambah'"
                                                   :class="`${ !positionAllowance.position_allowance ? 'text-decoration-underline fs-4' : 'fs-4'}`"
                                                   data-bs-target="#modal-position-allowance"
                                                   data-bs-toggle="modal"></a>
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
                                    <template x-for="pagination in positionAllowances.links">
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
                @include('pages.payroll.allowances.position.form')
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function positionAllowancesData() {
            return {
                positionAllowances: [],
                isLoading: false,
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                startIndex: null,
                modalForm: new bootstrap.Modal(document.getElementById('modal-position-allowance')),
                form: document.getElementById('form-position-allowance'),
                async init() {
                    await this.getPositionAllowancesData();
                    await this.getUserData();
                    await this.getMainBranches();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/payroll/setting/allowances/position/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.positionAllowances = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    const branchId = $('#branch_id').val();
                    console.log(branchId);
                    const resp = await axios.get('/payroll/setting/allowances/position/filter', {
                        params: {
                            branch_id: branchId
                        }
                    });
                    this.positionAllowances = resp.data
                },
                async getMainBranches() {
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/select2/main-branches-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    });
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.positionAllowances = resp.data
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/payroll/setting/allowances/position/${id}`)
                    this.editVal = resp.data;
                },
                async save(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/payroll/setting/allowances/position/${id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        this.modalForm.hide();
                        await this.init();
                    } catch (error) {
                        console.log(error)
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post('/payroll/setting/allowances/position/destroy', new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getPositionAllowancesData() {
                    const resp = await axios.get('/payroll/setting/allowances/position/data');
                    this.positionAllowances = resp.data
                    this.startIndex = this.positionAllowances.from
                },
                async getUserData() {
                    $(".users-select2").select2({
                        placeholder: "Pilih Karyawan",
                        ajax: {
                            url: '/payroll/setting/allowances/position/user/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedUser() {
                    const selectedUser = $('#selectedUser');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/payroll/setting/allowances/position/user/selected/${this.editVal.id}`,
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
