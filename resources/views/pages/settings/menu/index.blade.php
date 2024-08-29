@extends('layouts.template')
@section('page-title', 'Menu Management')
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
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <a href="#" type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                               data-bs-target="#modal-create">
                                Tambah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Nama</th>
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
                            <template x-if="!isLoading && menus.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(menu, index) in menus?.data" :key="menu.id">
                                <tbody class="fw-bold">
                                <tr :class="{'table-active': selected === index}" style="cursor:pointer;"
                                    @click="selected === index ? selected = null : selected = index">
                                    <td>
                                        <a href="#" x-text="`Section Menu ${menu.name}`"></a>
                                    </td>
                                </tr>

                                <tr class="text-gray-600" x-show="selected === index" x-cloak x-transition>
                                    <td colspan="5">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped">
                                            <thead>
                                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                <th>Nama Menu</th>
                                                <th>URL</th>
                                                <th>ICON</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <template x-for="(child, index) in menu.children" :key="index">
                                                <tbody :class="{'table-active': selectedSubMenu === index}"
                                                       style="cursor:pointer;"
                                                       @click="selectedSubMenu === index ? selectedSubMenu = null : selectedSubMenu = index">
                                                <tr>
                                                    <td x-text="child.name"></td>
                                                    <td x-text="child.url"></td>
                                                    <td>
                                                        <i :class="child.icon"></i>
                                                    </td>
                                                    <td>
                                                        <a href="#"
                                                           class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                            <span class="svg-icon svg-icon-2">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                        </a>
                                                        <a href="#"
                                                           class="btn btn-sm btn-icon btn-bg-light btn-active-color-danger">
                                                            <span class="svg-icon svg-icon-2">
                                                                <i class="fas fa-trash"></i>
                                                            </span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <template x-for="childSubMenu in child.children">
                                                    <tr x-show="selectedSubMenu === index" x-cloak x-transition>
                                                        <td></td>
                                                    </tr>
                                                </template>
                                                </tbody>
                                            </template>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
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
    <script>
        function menuData() {
            return {
                isLoading: false,
                buttonLoading: false,
                menus: [],
                startIndex: null,
                search: '',
                editVal: '',
                selected: null,
                selectedSubMenu: null,
                async init() {
                    await this.getMenuData();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        this.departments = await axios.get('/settings/menu-management/data', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }

                },
                async nextPage() {
                    if (this.departments.next_page_url) {
                        const resp = await axios.get(`${this.departments.next_page_url}`);
                        this.startIndex = this.resp.from
                        this.departments = resp.data
                    }
                },
                async previousPage() {
                    if (this.departments.prev_page_url) {
                        const resp = await axios.get(`${this.departments.prev_page_url}`);
                        this.startIndex = this.resp.from
                        this.departments = resp.data
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/department/', new FormData(this.formCreate));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.modalCreate.hide();
                        this.formCreate.reset();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/master/department/${id}`);
                    this.editVal = resp.data;
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/department/${id}`, new FormData(this.formEdit));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.modalEdit.hide();
                        this.formEdit.reset();
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
                            await axios.delete(`/master/department/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getMenuData() {
                    this.isLoading = true
                    try {
                        const resp = await axios.get('/settings/menu-management/data');
                        this.menus = resp.data;
                        this.startIndex = this.menus.from;
                    } catch (error) {
                        console.error(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
            }
        }
    </script>
@endpush
