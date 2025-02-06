@extends('layouts.template')
@section('page-title', 'Ubah User')
@section('content')
    <div x-data="updateUser">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6" href="{{ url('/manage-users/users/') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6" x-model="userPlacement">
                                    <label class="col-form-label required fw-bold fs-6">Penempatan</label>
                                    <select name="placement" id="selectedPlacement"
                                            class="form-select form-select-solid user-placement-select2">
                                        <option value="0" selected>Pilih</option>
                                        <option value="Cabang" :selected="userPlacement === 'Cabang'">Cabang</option>
                                        <option value="Pusat" :selected="userPlacement === 'Pusat'">Pusat</option>
                                    </select>
                                </div>
                                <div class="col-lg-6" x-show="userPlacement === 'Cabang'" x-transition x-cloak>
                                    <label class="col-form-label required fw-bold fs-6">Cabang</label>
                                    <select :name="`${userPlacement === 'Cabang' ? 'branch_id' : ''}`"
                                            id="selectedBranch"
                                            class="form-select form-select-solid branchSelect2">
                                        <option value="0">Pilih Cabang</option>
                                    </select>
                                </div>
                            </div>
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">ID Absen</label>
                                <input type="text" name="absent_id"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Nama" value="{{ $user->absent_id }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Nama</label>
                                <input type="text" name="name"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Nama" value="{{ $user->name }}"/>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Email</label>
                                <input type="text" name="email"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="email" value="{{ $user->email }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Tanggal Masuk</label>
                                <input type="date" name="join_date"
                                       class="form-control form-control-lg form-control-solid"
                                       value="{{ $user->join_date }}"/>
                            </div>
                        </div>


                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Perusahaan</label>
                                <select name="company_id" id="selectedCompany"
                                        class="form-select form-select-solid companies-select2">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Password</label>
                                <input type="password" name="password" class="form-control form-control-solid"
                                       id="password" placeholder="password">
                            </div>
                        </div>

                        <div class="form-group row mb-6">
                            <label class="col-lg-1 col-form-label required fw-bold fs-6">Role</label>
                            <div class="col-lg-12 fv-row">
                                <div class="row">
                                    <template x-for="role in roles" :key="role.id">
                                        <div class="col-md-4 mt-2">
                                            <input class="form-check-input" type="radio"
                                                   :checked="users?.roles[0].id === role.id" :value="role.name" multiple
                                                   name="role[]"/>
                                            <label class="form-check-label" for="flexCheckChecked">
                                                <span x-text="role.name"></span>
                                            </label>
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
        function updateUser() {
            return {
                buttonLoading: false,
                roles: null,
                users: null,
                id: "{{ $user->id }}",
                form: document.getElementById('form'),
                userPlacement: "{{ $user->placement }}",
                async init() {
                    await this.getUserData();
                    await this.getRoleData();
                    await this.getBranchData();
                    await this.selectedBranch();
                    await this.getCompany();
                    await this.selectedCompany();
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/users/update/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data sukses disimpan').then(() => {
                            window.location.href = '/manage-users/users/';
                        });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getUserData() {
                    const users = await axios.get(`/manage-users/users/show/${this.id}`);
                    this.users = users.data;
                },
                async getRoleData() {
                    const roles = await axios.get('/manage-users/users/roles/data');
                    this.roles = roles.data;
                },
                async selectedBranch() {
                    const selectedBranch = $('#selectedBranch');
                    const response = await axios.get(`/manage-users/users/get-selected-branch/${this.id}`);
                    const option = new Option(response.data.name, response.data.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response.data}
                    });
                },
                async getBranchData() {
                    $(".branchSelect2").select2({
                        ajax: {
                            url: '/manage-users/users/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getCompany() {
                    $(".companies-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Perusahaan",
                        ajax: {
                            url: '/manage-users/users/companies/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    });
                },
                async selectedCompany() {
                    const selectedCompany = $('#selectedCompany');
                    const response = await axios.get(`/manage-users/users/companies/selected/${this.id}`);
                    const option = new Option(response.data.name, response.data.id, true, true);
                    selectedCompany.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response.data}
                    });
                },
            }
        }
    </script>
@endpush
