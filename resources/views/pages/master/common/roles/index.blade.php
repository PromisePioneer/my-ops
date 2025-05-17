@extends('layouts.template')
@section('page-title', 'Jabatan')
@section('breadcrumbs', 'Master Umum - Jabatan')
@section('content')
    <div x-data="rolesData">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <div class="card-title">
                   <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                    <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                           class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                </div>
                <div class="card-toolbar">
                    <ul class="pagination float-end">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <template x-if="!isLoading && roles.data?.length === 0">
            <div class="card">
                <div class="card-body p-0">
                    <div class="card-px text-center py-20 my-10">
                        <h2 class="fs-2x fw-bolder mb-10">Data Tidak Ditemukan</h2>
                        <p class="text-gray-400 fs-4 fw-bold mb-10">Saat ini data yang anda cari tidak ditemukan.</p>
                        <a href="{{ url('master/common/roles/create') }}" class="btn btn-primary">Tambah Role</a>
                    </div>
                </div>
            </div>
        </template>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
            <template x-for="(role, index) in roles?.data" :key="role.id">
                <div class="col-md-4">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 x-text="role.role_name"></h2>
                            </div>
                        </div>
                        <div class="card-body pt-1">
                            <div class="fw-bolder text-gray-600 mb-5"
                                 x-text="`Total Karyawan: ${role.total_user}`"></div>
                            <div class="d-flex flex-column text-gray-600">
                                <template x-for="(permission, index) in role.permissions" :key="index">
                                    <div class="d-flex align-items-center py-2">
                                        <span class="bullet bg-primary me-3"></span>
                                        <p class="text-capitalize" x-text="permission.name"></p>
                                    </div>
                                </template>
                                <template x-if="role.total_permission_in_this_role > 0">
                                    <div class="d-flex align-items-center py-2">
                                        <span class="bullet bg-primary me-3"></span>
                                        <em x-text="`dan ${role.total_permission_in_this_role} lainnya`"></em>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="card-footer flex-wrap pt-0">
                            <template x-if="Number(editPermission) === 1">
                                <a :href="`/master/common/roles/edit/${role.id}`"
                                   class="btn btn-light btn-active-primary my-1 me-2">
                                    <x-icons.edit/>
                                </a>
                            </template>
                            <template x-if="Number(deletePermission) === 1">
                                <button type="button" class="btn btn-light btn-active-danger my-1"
                                        @click="destroy(role.id)">
                                    <x-icons.trash/>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="search === ''">
                <div class="ol-md-4">
                    <div class="card h-md-100">
                        <div class="card-body d-flex flex-center">
                            @can('Tambah Data Jabatan')
                                <a href="{{ url('master/common/roles/create')  }}"
                                   class="btn btn-clear d-flex flex-column flex-center">
                                    <div class="fw-bolder fs-3 text-gray-600 text-hover-primary">Tambah Role Baru</div>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </template>

        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function rolesData() {
            return {
                editPermission: "{{ request()->user()->can('Edit Data Jabatan') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Jabatan')  }}",
                buttonLoading: false,
                roles: null,
                isLoading: true,
                startIndex: null,
                permissionData: null,
                search: '',
                editVal: '',
                roleId: '',
                async init() {
                    await this.getRole();
                },
                async add() {
                    await this.getAllPermissions();
                },
                async edit(id) {
                    const resp = await axios.get(`/master/common/roles/edit/${id}`);
                    await this.getAllPermissions();
                    this.editVal = resp.data;
                },
                async getRole() {
                    const roles = await axios.get('/master/common/roles/data');
                    this.roles = roles.data
                    this.startIndex = this.roles.from;
                    this.isLoading = false;
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/master/common/roles/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.roles = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async nextPage() {
                    if (this.roles.next_page_url) {
                        const resp = await axios.get(`${this.roles.next_page_url}`);
                        this.roles = resp.data
                        this.startIndex = this.roles.from
                    }
                },
                async previousPage() {
                    if (this.roles.prev_page_url) {
                        const resp = await axios.get(`${this.roles.prev_page_url}`);
                        this.roles = resp.data
                        this.startIndex = this.roles.from
                    }
                },
                async save() {
                    this.buttonLoading = true
                    try {
                        await axios.post(`/master/common/roles/`, new FormData(this.formCreate))
                        await showAlert('success', 'Data berhasil disimpan');
                        this.modalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async update(id) {
                    this.buttonLoading = true
                    try {
                        await axios.post(`/master/common/roles/update/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil disimpan');
                        this.modalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/master/common/roles/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getAllPermissions() {
                    const resp = await axios.get('/master/common/roles/permissions/data');
                    this.permissionData = resp.data;
                },

            }
        }
    </script>
@endpush
