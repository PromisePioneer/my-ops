@extends('layouts.template')
@section('page-title', 'Data Role')
@section('content')
    <div x-data="rolesData">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <a href="{{ url('master/roles/create') }}" class="btn btn-primary btn-sm">
                            Tambah
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">No</th>
                            <th class="min-w-125px">Nama</th>
                            <th class="min-w-125px">Actions</th>
                        </thead>
                        <tbody class="text-gray-600 fw-bold">
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
                        <template x-if="!isLoading && roles.data?.length === 0">
                            <tr>
                                <td colspan="9">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                        </template>
                        <template x-for="(role,index) in roles?.data" :key="role.id">
                            <tr>
                                <td x-text="startIndex + index++"></td>
                                <td x-text="role.name"></td>
                                <td>
                                    <a :href="`/master/roles/edit/${role.id}`" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm" @click="destroy(role.id)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function rolesData() {
            return {
                buttonLoading: false,
                roles: null,
                isLoading: true,
                startIndex: null,
                search: '',
                editVal: '',
                roleId: '',
                async init() {
                    await this.getRole();
                },
                async getRole() {
                    const roles = await axios.get('/master/roles/data');
                    this.roles = roles.data
                    this.startIndex = this.roles.from;
                    this.isLoading = false;
                },
                async searchData() {
                    this.roles = await axios.get('/master/roles/search', {
                        params: {
                            search: this.search
                        },
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });
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
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/master/roles/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                }
            }
        }
    </script>
@endpush
