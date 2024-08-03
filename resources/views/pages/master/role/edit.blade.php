@extends('layouts.template')
@section('page-title', 'Role Manager')
@section('content')
    <div x-data="generateRole">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6" href="{{ url('master/roles/') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group row mb-6">
                                    <label class="col-form-label required fw-bold fs-6">Nama</label>
                                    <input type="text" name="name"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="Nama" value="{{ $role->name }}"/>

                                </div>
                            </div>
                        </div>

                        <label class="col-lg-1 col-form-label required fw-bold fs-6">Permission</label>
                        <div class="row justify-content-center align-items-center mb-6">
                            <template x-for="row in permissions.data" :key="row.id">
                                <div class="col-md-4 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               :checked="associatedPermission.hasOwnProperty(row.id)" :value="row.name"
                                               multiple
                                               name="permission[]"/>
                                        <label class="form-check-label">
                                            <span class="text-capitalize fs-normal" x-text="row.name"></span>
                                        </label>
                                    </div>
                                </div>
                            </template>

                            <div class="d-flex justify-content-end align-items-end mb-4  mt-10">
                                <div class="page-item previous">
                                    <button type="button" class="btn btn-light btn-sm" @click="previousPage()">Previous
                                    </button>
                                </div>
                                <div class="page-item next me-lg-10">
                                    <button type="button" class="btn btn-light btn-sm" @click="nextPage()">Next</button>
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
    <script>
        function generateRole() {
            return {
                buttonLoading: false,
                id: "{{ $role->id }}",
                permissions: [],
                associatedPermission: {},
                form: document.getElementById('form'),
                async init() {
                    await this.getAllPermissions();
                    await this.getAssociatedPermissions();
                },
                async nextPage() {
                    if (this.permissions.next_page_url) {
                        const resp = await axios.get(`${this.permissions.next_page_url}`);
                        console.log(resp.data);
                        this.permissions = resp.data
                    }
                },
                async previousPage() {
                    if (this.permissions.prev_page_url) {
                        const resp = await axios.get(`${this.permissions.prev_page_url}`);
                        this.permissions = resp.data
                    }
                },
                async save() {
                    this.buttonLoading = true
                    try {
                        await axios.post(`/master/roles/update/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '/master/roles';
                        })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getAssociatedPermissions() {
                    const associatedPermission = await axios.get(`/master/roles/show/${this.id}`);
                    this.associatedPermission = associatedPermission.data
                },
                async getAllPermissions() {
                    const permission = await axios.get(`/master/roles/permissions/data`);
                    this.permissions = permission.data;
                },

            }
        }

    </script>
@endpush
