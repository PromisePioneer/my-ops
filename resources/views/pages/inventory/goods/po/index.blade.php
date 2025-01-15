@extends('layouts.template')
@section('page-title','Daftar Barang')
@section('content')
    <div x-data="goodsPurchaseOrder()">
        @include('pages.operational-master-data.goods.modal.form')
        @include('pages.inventory.goods.po.modal.detail')
        @include('pages.inventory.goods.po.modal.confirm')
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div x-model="placement" class="mb-2">
                            <select class="form-select form-select-solid"
                                    name="placement" id="placement">
                                <option value="0" selected>Pilih tujuan barang</option>
                                <option value="Cabang">Cabang</option>
                                <option value="Gudang">Gudang</option>
                            </select>
                        </div>
                        <div x-show="placement === 'Cabang'" x-transition x-cloak>
                            <div>
                                <select class="form-select form-select-solid branches-select2"
                                        name="branch_id" id="branch_id">
                                </select>
                            </div>
                        </div>
                        <div x-show="placement === 'Gudang'" x-transition x-cloak>
                            <select name="warehouse_id" id="warehouse_id"
                                    class="form-select form-select-solid warehouses-select2">
                                <option></option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center py-2">
                            <input type="number" name="year" id="year"
                                   class="form-control form-control-solid"
                                   placeholder="Filter Berdasarkan Tahun">
                        </div>
                        <div class="d-flex align-items-center py-2">
                            <select class="form-select form-select-solid"
                                    name="month" id="month" data-control="select2"
                                    data-placeholder="Pilih Bulan">
                                <option></option>
                                <template x-for="month in months" :key="index">
                                    <option :value="month.number" x-text="month.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-4 text-end">
                    <button type="button" class="btn btn-light btn-active-primary btn-sm" @click="filter()">
                        Filter
                    </button>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
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
                        <a class="btn btn-light-primary btn-sm" href="{{ url('inventory/goods/po/create') }}">
                            Tambah
                        </a>
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
                                <th class="min-w-125px">Tanggal Masuk</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Harga Satuan</th>
                                <th class="min-w-125px">Qty</th>
                                <th class="min-w-125px">Tujuan Barang</th>
                                <th class="min-w-125px">Action</th>
                            </thead>
                            <tbody class=" fw-bold">
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
                            <template x-if="!isLoading && goodsPurchaseOrder.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(item, index) in goodsPurchaseOrder?.data" :key="index">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="item.id"
                                                   :id="'checkbox-' + item.id"/>
                                        </div>
                                    </td>
                                    <td x-text="item.date"></td>
                                    <td x-text="item.name"></td>
                                    <td x-text="item.unit_price"></td>
                                    <td x-text="item.qty"></td>
                                    <td x-text="item.location"></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-detail" @click="detail(item.id)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <template x-if="item.status === 0">
                                            <a :href="`/inventory/goods/po/edit/${item.id}`"
                                               class="btn btn-light-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </template>
                                        <template x-if="item.status === 0">
                                            <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-confirm" @click="detail(item.id)">
                                                <i class="bi bi-check-square"></i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="(pagination, index) in goodsPurchaseOrder.links">
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
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        $('.date').flatpickr();

        function goodsPurchaseOrder() {
            return {
                buttonLoading: false,
                modalDetail: new bootstrap.Modal(document.getElementById('modal-detail')),
                modalConfirm: new bootstrap.Modal(document.getElementById('modal-confirm')),
                formConfirm: document.getElementById('form-confirm'),
                formDelete: document.getElementById('form-delete'),
                isLoading: false,
                goodsPurchaseOrder: [],
                detailVal: {},
                search: '',
                confirmVal: {},
                selectedCheckBox: [],
                placement: false,
                itemsCanBeUsed: 0,
                itemCannotBeUsed: 0,
                editVal: '',
                needSN: null,
                hasSNOnItem: false,
                months: [],
                async init() {
                    await this.getGoodsPurchaseOrder();
                    await this.getMonth();
                    await this.getWarehouses();
                    await this.getBranchData();
                },
                getMonth() {
                    this.months.push(
                        {name: "Januari", number: '01'},
                        {name: "Februari", number: '02'},
                        {name: "Maret", number: '3'},
                        {name: "April", number: '04'},
                        {name: "Mei", number: '05'},
                        {name: "Juni", number: '06'},
                        {name: "Juli", number: '07'},
                        {name: "Agustus", number: '08'},
                        {name: "September", number: '09'},
                        {name: "Oktober", number: '10'},
                        {name: "November", number: '11'},
                        {name: "Desember", number: '12'},
                    )
                },
                filter() {
                    this.isLoading = true;
                    try {
                        const branch_id = $('#branch_id').val();
                        const warehouse_id = $('#warehouse_id').val();
                        const month = $('#month').val();
                        const year = $('#year').val();
                        axios.get('/inventory/goods/po/filter', {
                            params: {
                                branch_id: branch_id,
                                warehouse_id: warehouse_id,
                                month: month,
                                year: year
                            }
                        }).then(resp => {
                            this.goodsPurchaseOrder = resp.data;
                            this.isLoading = false;
                        });
                    } catch (e) {
                        console.log(e)
                    } finally {

                    }
                },
                async changePlacement() {
                    if (this.placement === 'Cabang') {
                        await this.getBranchData();
                    }
                    if (this.placement === 'Pusat') {
                        await this.getWarehouses();
                    }
                },
                async getGoodsPurchaseOrder() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/goods/po/data');
                        this.goodsPurchaseOrder = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/goods/po/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        })
                        this.goodsPurchaseOrder = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
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
                async getWarehouses() {
                    $(".warehouses-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Gudang',
                        ajax: {
                            url: '/inventory/goods/po/warehouses/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getBranchData() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/inventory/goods/po/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async detail(id) {
                    try {
                        const resp = await axios(`/inventory/goods/po/detail/${id}`);
                        this.detailVal = resp.data;
                        this.itemsCanBeUsed = this.detailVal.qty;
                    } catch (e) {
                        console.log(e)
                    }
                },
                async confirm() {
                    this.buttonLoading = true;
                    try {
                        if (this.itemsCanBeUsed > this.detailVal.qty) {
                            throw new Error(showAlert('error', 'Kuantitas tidak sesuai PO barang yang diterima'));
                        }
                        await axios.post(`/inventory/goods/po/confirm/${this.detailVal.id}`, new FormData(this.formConfirm))
                        await showAlert('success', 'Data berhasil disimpan');
                        await this.modalConfirm.hide();
                        await this.formConfirm.reset();
                        await this.init();
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        this.isLoading = true;
                        try {
                            await axios.post(`/inventory/goods/po/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        } finally {
                            this.isLoading = false;
                        }
                    });
                },
            }
        }
    </script>
@endpush
