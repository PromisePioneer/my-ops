@extends('layouts.template')
@section('page-title', 'Produk')
@section('breadcrumbs', 'Master Umum - Produk')
@section('content')
    <div x-data="productData()">
        @include('pages.general-master-data.products.modal.create')
        @include('pages.general-master-data.products.modal.edit')
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
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        @can('Tambah Data Produk')
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-create">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Tambah
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12 ">
                    <form id="deleteForm" @submit.prevent="destroy()">
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_products">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3"
                                         @click="toggleAllCheckBox()">
                                        <input class="form-check-input" type="checkbox"
                                               :disabled="Number(deletePermission) !== 1"/>
                                    </div>
                                </th>
                                <th class="min-w-125px">Kode</th>
                                <th class="min-w-125px">Nama Produk</th>
                                <th class="min-w-125px">Kategori</th>
                                <th class="min-w-125px">Harga Satuan</th>
                                <template x-if="Number(deletePermission) === 1">
                                    <th class="min-w-125px">Actions</th>
                                </template>
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
                            <template x-if="!isLoading && products.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(product,index) in products?.data" :key="product.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="product.id"
                                                   :id="'checkbox-' + product.id"
                                                   :disabled="Number(deletePermission) !== 1"/>
                                        </div>
                                    </td>
                                    <td x-text="product.code"></td>
                                    <td x-text="product.name"></td>
                                    <td x-text="product.category"></td>
                                    <td x-text="`Rp. ${product.unit_price}`"></td>
                                    <template x-if="Number(editPermission) === 1">
                                        <td>
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-edit" @click="edit(product.id)">
                                                <i class="ki-duotone ki-pencil fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </td>
                                    </template>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in products.links">
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
    <script>
        function productData() {
            return {
                editPermission: "{{ request()->user()->can('Edit Data Produk') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Produk') }}",
                buttonLoading: false,
                products: [],
                isLoading: true,
                search: '',
                selectAll: false,
                selectedCheckBox: [],
                singleChecked: false,
                editVal: '',
                formCreate: document.getElementById('form-create'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formEdit: document.getElementById('form-edit'),
                deleteForm: document.getElementById('deleteForm'),
                async init() {
                    await this.productData();
                },
                async searchData() {
                    try {
                        const response = await axios.get('/general-master-data/product/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.products = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.contacts = resp.data
                    }
                },
                toggleAllCheckBox() {
                    if (Number(this.deletePermission)) {
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
                    }
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
                        await axios.post(`/general-master-data/product`, new FormData(this.formCreate))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreate.reset();
                        this.modalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/general-master-data/product/show/${id}`);
                    this.editVal = resp.data;
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/general-master-data/product/update/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil diubah');
                        this.formEdit.reset();
                        this.modalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/general-master-data/product/destroy`, new FormData(this.deleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async productData() {
                    const products = await axios.get('/general-master-data/product/data');
                    this.products = products.data;
                    this.startIndex = this.products.from;
                    this.isLoading = false;
                },
            }
        }
    </script>
@endpush
