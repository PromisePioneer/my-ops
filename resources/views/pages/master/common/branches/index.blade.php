@extends('layouts.template')
@section('page-title', 'Cabang')
@section('breadcrumbs', 'Master Umum - Cabang')
@section('content')
    <div x-data="branchesData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.master.common.branches.modal.create')
            @include('pages.master.common.branches.modal.edit')
            @include('pages.master.common.branches.modal.create-children')
            @include('pages.master.common.branches.modal.children-detail')
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
                            <template x-if="Number(createPermission) === 1">
                                <button type="button" class="btn btn-light-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-create">
                                    <x-icons.add-item/>
                                    Tambah
                                </button>
                            </template>
                        </div>
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
                            <x-icons.trash/>
                            Hapus
                        </button>
                    </form>
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed table-bordered fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <template x-if="Number(deletePermission) === 1">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox"
                                                   @click="toggleAllCheckBox()">
                                        </div>
                                    </th>
                                </template>
                                <th class="min-w-125px">Kode</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Alamat</th>
                                <th class="min-w-125px">Sub Cabang</th>
                                <template x-if="Number(editPermission) === 1">
                                    <th class="min-w-125px">Actions</th>
                                </template>
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
                            <template x-if="!isLoading && branches.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="6">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="branch in branches?.data" :key="branch.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <template x-if="Number(deletePermission) === 1">
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                 @click="selectCheckBox($event)">
                                                <input class="form-check-input" type="checkbox" :value="branch.id"
                                                       :id="'checkbox-' + branch.id"/>
                                            </div>
                                        </td>
                                    </template>
                                    <td>
                                        <p class="text-center" x-text="`${branch.code} - ${branch.name}`"></p>
                                    </td>
                                    <td class="text-start">
                                        <p x-text="branch.address"></p>
                                    </td>
                                    <td>
                                        <ul>
                                            <template x-for="(children, index) in branch.children" :key="index">
                                                <li>
                                                    <button class="btn btn-link btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#modal-children-detail"
                                                            @click="edit(children.id)">
                                                        <i class="bi bi-geo-alt-fill"></i>
                                                        <span class="fw-bolder" x-text="children.name"></span>
                                                    </button>
                                                </li>
                                            </template>
                                        </ul>
                                    </td>
                                    <td>
                                        <template x-if="Number(editPermission) === 1">
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-edit" @click="edit(branch.id)">
                                                <x-icons.edit/>
                                            </button>
                                        </template>
                                        <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-create-children"
                                                @click="edit(branch.id)">
                                            <x-icons.add-folder/>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in branches.links">
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
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function branchesData() {
            return {
                createPermission: "{{ request()->user()->can('Tambah Data Cabang') }}",
                editPermission: "{{ request()->user()->can('Edit Data Cabang') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Cabang') }}",
                branches: [],
                isLoading: false,
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                subBranchVal: '',
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                modalCreateChildren: new bootstrap.Modal(document.getElementById('modal-create-children')),
                formDelete: document.getElementById('form-delete'),
                formCreateChildren: document.getElementById('form-create-children'),
                modalChildrenDetail: new bootstrap.Modal(document.getElementById('modal-children-detail')),
                formChildrenDetail: document.getElementById('form-children-detail'),
                showFormSubBranchDetail: false,
                async init() {
                    await this.getBranchData();
                },
                async subBranchDetail(id) {
                    const resp = await axios.get(`/master/common/branch/sub-branch/detail/${id}`);
                    this.subBranchVal = resp.data;
                },
                async getBranchData() {
                    this.isLoading = false;
                    try {
                        const branches = await axios.get('/master/common/branch/data');
                        this.branches = branches.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/master/common/branch/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });

                        this.branches = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        this.branches = [];
                        this.isLoading = true;
                        try {
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                }
                            });
                            this.branches = resp.data
                        } catch (e) {
                            console.log(e)
                        } finally {
                            this.isLoading = false
                        }
                    }
                },
                toggleAllCheckBox() {
                    this.selectAll = !this.selectAll;
                    this.singleChecked = false;
                    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    this.selectedCheckBox = [];
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = this.selectAll;
                        if (this.selectAll) {
                            this.selectedCheckBox.push(checkbox.value);
                        }
                    });
                    this.selectedCheckBox.shift();
                },
                selectCheckBox(event) {
                    const checkboxId = event.target.value;
                    if (event.target.checked) {
                        this.selectedCheckBox.push(checkboxId);
                    } else {
                        const index = this.selectedCheckBox.indexOf(checkboxId);
                        if (index !== -1) {
                            this.selectedCheckBox.splice(index, 1);
                        }
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/common/branch', new FormData(this.formCreate))
                            .then(async () => {
                                const resp = await axios.get(`${this.branches.path}?page=${this.branches.current_page}`, {
                                    params: {
                                        search: this.search
                                    }
                                });
                                this.branches = resp.data;
                            })

                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreate.reset();
                        this.modalCreate.hide();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/master/common/branch/show/${id}`);
                    this.editVal = resp.data;
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/common/branch/update/${id}`, new FormData(this.formEdit)).then(async () => {
                            const resp = await axios.get(`${this.branches.path}?page=${this.branches.current_page}`, {
                                params: {
                                    search: this.search
                                }
                            });
                            this.branches = resp.data;
                        })
                        await showAlert('success', 'Data berhasil disimpan')
                        this.modalEdit.hide();
                        this.formEdit.reset();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },

                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/common/branch/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async saveChildren() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/common/branch/sub-branch/store`,
                            new FormData(this.formCreateChildren))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.modalCreateChildren.hide();
                        this.formCreateChildren.reset();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async updateChildren(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/common/branch/sub-branch/update/${id}`, new FormData(this.formChildrenDetail))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.modalChildrenDetail.hide();
                        this.formChildrenDetail.reset();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroyChildren(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/master/common/branch/sub-branch/destroy/${id}`, new FormData(this.formDelete));
                            this.modalChildrenDetail.hide();
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
