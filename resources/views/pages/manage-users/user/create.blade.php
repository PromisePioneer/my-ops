@extends('layouts.template')
@section('page-title', 'Tambah User')
@section('content')
    <div x-data="generateUser()">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6" href="{{ url('manage-users/users/') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                    <label class="col-form-label required fw-bold fs-6">Cabang</label>
                                    <select name="branch_id" class="form-select form-select-solid branchSelect2">
                                        <option value="0">Pilih Cabang</option>
                                    </select>
                            </div>
                            <div class="col-lg-6">
                                    <label class="col-form-label required fw-bold fs-6">Nama</label>
                                    <input type="text" name="name"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="Nama" value=""/>

                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                    <label class="col-form-label required fw-bold fs-6">Email</label>
                                    <input type="text" name="email"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="email" value=""/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">
                                    (NIK) Nomor Induk Pegawai</label>
                                <input type="text" name="nip"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="NIK"/>
                            </div>
                        </div>
                        <div class="form-group row mb-6">
                            <label class="col-lg-1 col-form-label required fw-bold fs-6">Role</label>
                            <div class="col-lg-12 fv-row">
                                <div class="row">
                                    <template x-for="row in role" :key="row.id">
                                        <div class="col-md-4 mt-2">
                                            <input class="form-check-input" type="checkbox" :value="row.name" multiple
                                                   name="role[]"/>
                                            <label class="form-check-label" for="flexCheckChecked">
                                                <span x-text="row.name"></span>
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
                        <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading..' : 'Simpan'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function generateUser() {
            return {
                role: null,
                buttonLoading: false,
                form: document.getElementById('form'),
                async init() {
                    await this.getBranchData();
                    await this.getRoleData();
                },
                async save() {
                    this.buttonLoading = true
                    try {
                        await axios.post("{{ url('/manage-users/users') }}", new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '/manage-users/users';
                        });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getRoleData() {
                    const resp = await axios.get('/manage-users/users/roles/data');
                    this.role = resp.data;
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
            }
        }
    </script>
@endpush
