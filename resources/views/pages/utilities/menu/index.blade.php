@extends('layouts.template')
@section('page-title', 'Menu Management')
@section('breadcrumbs', 'Utilitas - Menu Management')
@section('content')
    <div x-data="menuData()">
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
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-bordered">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Nama Menu</th>
                                <th class="min-w-125px">Link</th>
                                <th class="min-w-125px">Icon</th>
                                <th class="min-w-125px">Aksi</th>
                            </tr>
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
                            <template x-if="!isLoading && menus?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(menu, index) in menus" :key="menu.id">
                                <tbody x-sort class="fw-bold">
                                <tr x-sort:item>
                                    <td>
                                        <a href="" x-text="menu.name"></a>
                                    </td>
                                    <td x-text="menu.link"></td>
                                    <td x-html="menu.icon">

                                    </td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(account.account_id)">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                        <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-create-children"
                                                @click="edit(account.account_id)">
                                            <i class="ki-duotone ki-add-folder">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                <template x-for="childMenu in menu.children">
                                    <tr x-sort:item>
                                        <td placement="center"
                                            x-text="childMenu.name"></td>
                                        <td x-text="childMenu.link"></td>
                                        <td></td>
                                        <td>
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-edit-children"
                                                    @click="edit(childMenu.id)">
                                                <i class="ki-duotone ki-pencil fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </template>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function menuData() {
            return {
                menus: [],
                search: '',
                async init() {
                    await this.getMenuData();
                },
                async getMenuData() {
                    try {
                        const resp = await axios.get('/utility/menus/data');
                        this.menus = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false
                    }
                },
                async searchData() {

                }
            }
        }
    </script>
@endpush
