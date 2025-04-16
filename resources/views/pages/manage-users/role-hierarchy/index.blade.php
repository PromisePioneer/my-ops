@extends('layouts.template')
@section('page-title', 'Hirarki Jabatan')
@section('breadcrumbs', 'Manajemen Karyawan - Hirarki Jabatan')
@section('content')
    <div x-data="roleHierarchyData()">
        @include('pages.manage-users.role-hierarchy.form.parent')
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
                        <button type="button" class="btn btn-light-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-role-hierarchy">
                            <i class="ki-duotone ki-message-add fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i> Tambah
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12">
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
                        <table class="table align-middle table-row-dashed table-bordered fs-6 gy-5"
                               id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px text-center">PIC</th>
                                <th class="min-w-125px">Anggota</th>
                                <th class="min-w-125px">Action</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="6">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && roleHierarchy?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="6">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="role in roleHierarchy" :key="role.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td class="text-center">
                                        <a href="#" x-text="role.name"></a>
                                    </td>
                                    <td>
                                        <ul>
                                            <template x-for="children in role.children" :key="children.id">
                                                <li x-text="children.name"></li>
                                            </template>
                                        </ul>
                                    </td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(branch.id)">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                        <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-create-children"
                                                @click="edit(branch.id)">
                                            <i class="ki-duotone ki-add-folder">
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
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in roleHierarchy.links">
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

@endsection
@push('script')
    <script>
        function roleHierarchyData() {
            return {
                buttonLoading: false,
                isLoading: false,
                parentModal: new bootstrap.Modal(document.getElementById('modal-role-hierarchy')),
                parentForm: document.getElementById('form-role-hierarchy'),
                roleHierarchy: [],
                selectedCheckBox: [],
                data: {},
                search: '',
                async init() {
                    await this.getRoleHierarchyData();
                },
                async getRoleHierarchyData() {
                    try {
                        const resp = await axios.get('/manage-users/role-hierarchy/data');
                        this.roleHierarchy = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
