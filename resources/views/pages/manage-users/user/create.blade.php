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
                            <div class="col-md-6" x-model="placement">
                                <label class="col-form-label required fw-bold fs-6">Penempatan</label>
                                <select name="placement" id="selectedPlacement"
                                        class="form-select form-select-solid user-placement-select2">
                                    <option value="0" selected>Pilih</option>
                                    <option value="Cabang">Cabang</option>
                                    <option value="Pusat">Pusat</option>
                                </select>
                            </div>
                            <div class="col-lg-6" x-show="placement === 'Cabang'" x-transition x-cloak>
                                <label class="col-form-label required fw-bold fs-6">Cabang</label>
                                <select :name="`${placement === 'Cabang' ? 'branch_id' : ''}`"
                                        class="form-select form-select-solid branchSelect2">
                                    <option value="0">Pilih Cabang</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">ID Absen</label>
                                <input type="number" class="form-control form-control-solid" name="absent_id"
                                       id="absent_id"
                                       placeholder="ID Absen" value="{{$randomAbsentId }}">
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
                                <label class="col-form-label required fw-bold fs-6">Tanggal Masuk</label>
                                <input type="date" name="join_date"
                                       class="form-control form-control-lg form-control-solid date"
                                       placeholder="Tanggal Masuk"/>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Perusahaan</label>
                                <select name="company_id" id="company_id"
                                        class="form-select form-select-solid companies-select2">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-6">
                            <label class="col-lg-1 col-form-label required fw-bold fs-6">Role</label>
                            <div class="col-lg-12 fv-row">
                                <div class="row">
                                    <template x-for="row in role" :key="row.id">
                                        <div class="col-md-4 mt-2 form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" :value="row.name" multiple
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


                <form id="form-absent-id" @submit.prevent="remoteEnroll()">
                    <input type="hidden" name="absent_id" value="12345">
                    <button type="submit">Daftarkan Absen ID</button>
                </form>


            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        $('.date').flatpickr();

        function generateUser() {
            return {
                role: null,
                buttonLoading: false,
                form: document.getElementById('form'),
                formAbsentIdCreate: document.getElementById('form-absent-id'),
                placement: false,
                async init() {
                    await this.getBranchData();
                    await this.getRoleData();
                    await this.getCompany();
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
                async remoteEnroll() {
                    const absentId = document.getElementById('absent_id')?.value ?? null;
                    this.isLoading = true;
                    try {
                        await axios.get('/iclock/getrequest', {
                            params: {
                                SN: "AEWD233960062",
                            },
                            // headers: {
                            //     'Custom-Data': JSON.stringify({
                            //         'absent_id': absentId
                            //     })
                            // }
                        })
                    }catch (e){
                        console.log(e)
                    }finally {
                        this.isLoading = false;
                    }
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
            }
        }
    </script>
@endpush
