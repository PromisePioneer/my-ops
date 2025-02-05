@extends('layouts.template')
@section('page-title', 'Master Operasional - Barang')
@section('content')
    <div x-data="itemData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.operational-master-data.goods.modal.form')
            @include('pages.operational-master-data.category-of-goods.modal.form')
            @include('pages.general-master-data.unit-types.form')
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-goods-center position-relative my-1">
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
                            <button type="button" class="btn btn-light-primary btn-sm" @click="add()"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-item">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Tambah
                            </button>
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
                        <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox"
                                               @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Kategori</th>
                                <th class="min-w-125px">SN/Kode Sudah tertera di Barang</th>
                                <th class="min-w-125px">Memerlukan SN/Kode</th>
                                <th class="min-w-125px">Actions</th>
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
                            <template x-if="!isLoading && goods.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="item in goods?.data" :key="item.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="item.id"
                                                   :id="'checkbox-' + item.id"/>
                                        </div>
                                    </td>
                                    <td x-text="item.name"></td>
                                    <td x-text="item.category?.name"></td>
                                    <td>
                                        <template x-if="item.already_has_sn_on_item === 1">
                                            <span class="badge bg-success text-white fw-bold text-uppercase">Ya</span>
                                        </template>
                                        <template x-if="item.already_has_sn_on_item === 0">
                                            <span class="badge bg-danger text-white fw-bold text-uppercase">Tidak</span>
                                        </template>
                                    </td>
                                    <td>
                                        <template x-if="item.need_sn === 1">
                                            <span class="badge bg-success text-white fw-bold text-uppercase">Ya</span>
                                        </template>
                                        <template x-if="item.need_sn === 0">
                                            <span class="badge bg-danger text-white fw-bold text-uppercase">Tidak</span>
                                        </template>
                                    </td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-item" @click="edit(item.id)">
                                            <i class="ki-duotone ki-pencil fs-2">
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
                        <template x-for="pagination in goods.links">
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
        function itemData() {
            return {
                goods: [],
                isLoading: false,
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                needSN: false,
                hasSNOnItem: false,
                modalForm: new bootstrap.Modal(document.getElementById('modal-item')),
                form: document.getElementById('form-item'),
                goodsCategoyModal: new bootstrap.Modal(document.getElementById('modal-item-category')),
                goodsCategoryForm: document.getElementById('form-item-category'),
                unitTypeModal: new bootstrap.Modal(document.getElementById('modal-unit-type')),
                unitTypeForm: document.getElementById('form-unit-type'),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getGoods();
                    await this.getGoodsCategory();
                    await this.getUnitType();
                },
                add() {
                    this.editVal = '';
                    this.form.reset();
                    $('#selectedCategory').val('').trigger('change');
                    $('#selectedUnitType').val('').trigger('change');
                },
                async getGoods() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/operational-master-data/goods/data');
                        this.goods = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/operational-master-data/goods/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });

                        this.goods = resp.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.goods = resp.data
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
                async saveGoods(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/operational-master-data/goods', new FormData(this.form))
                        } else {
                            await axios.post(`/operational-master-data/goods/update/${id}`, new FormData(this.form))
                        }

                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        this.modalForm.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getGoodsCategory() {
                    $(".category-of-goods-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Kategori',
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-item-category">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/operational-master-data/goods/goods-category/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedUnitType() {
                    const selectedUnitType = $('#selectedUnitType');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/operational-master-data/goods/unit-types/selected/${this.editVal.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedUnitType.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async saveUnitTypes() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/general-master-data/unit-types/', new FormData(this.unitTypeForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.unitTypeForm.reset();
                        this.unitTypeModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async selectedGoodsCategory() {
                    const selectedGoodsCategory = $('#selectedCategory');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/operational-master-data/goods/goods-category/selected/${this.editVal.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedGoodsCategory.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async saveGoodsCategory() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/operational-master-data/category-of-goods', new FormData(this.goodsCategoryForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.goodsCategoryForm.reset();
                        this.goodsCategoyModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getUnitType() {
                    $(".unit-types-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Satuan',
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-unit-type">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/operational-master-data/goods/unit-types/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async edit(id) {
                    const resp = await axios.get(`/operational-master-data/goods/${id}`);
                    this.editVal = resp.data;
                    await this.selectedGoodsCategory();
                    await this.selectedUnitType();
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/operational-master-data/goods/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
            }
        }
    </script>
@endpush
