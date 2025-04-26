@extends('layouts.template')
@section('content')
    <div x-data="itemStockData()">
        <div class="row gy-5 gx-xl-10">
            <div class="col-xl-4">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Informasi</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ url('inventory/goods/draft-stocks') }}" class="btn btn-sm btn-light"
                               data-bs-toggle="tooltip" data-bs-dismiss="click"
                               data-bs-custom-class="tooltip-inverse"
                               data-bs-original-title="Logistics App is coming soon" data-kt-initialized="1">Lihat
                                Selengkapnya</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills nav-pills-custom row position-relative mx-0 mb-9" role="tablist">
                            <li class="nav-item col-6 mx-0 p-0" role="presentation">
                                <a class="nav-link active d-flex justify-content-center w-100 border-0 h-100"
                                   data-bs-toggle="pill" href="#kt_list_widget_10_tab_1" aria-selected="true"
                                   role="tab">
                                    <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Belum Diberi kode</span>
                                    <span
                                        class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                </a>
                            </li>
                            <li class="nav-item col-6 mx-0 px-0" role="presentation">
                                <a class="nav-link d-flex justify-content-center w-100 border-0 h-100"
                                   data-bs-toggle="pill" href="#kt_list_widget_10_tab_2" aria-selected="false"
                                   tabindex="-1" role="tab">
                                        <span
                                            class="nav-text text-gray-800 fw-bold fs-6 mb-3">Harus Reorder</span>
                                    <span
                                        class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                </a>
                            </li>
                            <span class="position-absolute z-index-1 bottom-0 w-100 h-4px bg-light rounded"></span>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="kt_list_widget_10_tab_1" role="tabpanel">
                                <template x-if="!isLoading && draftStock.length === 0">
                                    <div class="m-0 border border-dashed border-gray-400 p-5 text-center">
                                        <span class="text-gray-800 fw-bold d-block fs-4">Data tidak ditemukan</span>
                                    </div>
                                </template>
                                <template x-for="stock in draftStock"
                                          :key="stock.id">
                                    <div class="m-0 border border-dashed border-gray-400 p-5">
                                        <div
                                            class="d-flex align-items-center flex-row-fluid justify-content-between">
                                            <a href="#" class="fs-6 fw-bolder text-black" x-text="stock.name"></a>
                                            <span class="text-gray-800 fw-bold d-block fs-4"
                                                  x-text="stock.total"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="tab-pane fade" id="kt_list_widget_10_tab_2" role="tabpanel">
                                <template x-if="!isLoading && mustReorderStock.length === 0">
                                    <div class="m-0 border border-dashed border-gray-400 p-5 text-center">
                                        <span class="text-gray-800 fw-bold d-block fs-4">Data tidak ditemukan</span>
                                    </div>
                                </template>
                                <template x-for="stock in mustReorderStock"
                                          :key="stock.id">
                                    <div class="m-0 border border-dashed border-gray-400 p-5">
                                        <div
                                            class="d-flex align-items-center flex-row-fluid justify-content-between">
                                            <a href="#" class="fs-6 fw-bolder text-black" x-text="stock.name"></a>
                                            <span class="text-gray-800 fw-bold d-block fs-4"
                                                  x-text="stock.stock"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row col-xl-8">
                <div class="col-md-6 mb-md-5 mb-xl-10">
                    <div class="card overflow-hidden mb-xl-10">
                            <div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
                                <div class="mb-4 px-9">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2"
                                              x-text="incomingItemTransactionQtyInThisMonth"></span>
                                    </div>
                                    <span class="fs-6 fw-semibold text-gray-500">Barang Masuk</span>
                                </div>
                            </div>
                        </div>
                    <div class="card card-flush mb-lg-10">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">69,700</span>
                                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Barang Kembali</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-end pt-0">
                            </div>
                        </div>
                </div>
                <div class="col-md-6 mb-md-5 mb-xl-10">
                    <div class="card overflow-hidden  mb-5 mb-xl-10">
                        <div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
                            <div class="mb-4 px-9">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">47,769,700</span>
                                    <span
                                        class="d-flex align-items-end text-gray-500 fs-6 fw-semibold">Tons</span>
                                    </div>
                                    <span class="fs-6 fw-semibold text-gray-500">Barang Keluar</span>
                                </div>
                            </div>
                        </div>
                        <div class="card card-flush mb-lg-10">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">69,700</span>
                                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Stok</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-end pt-0"></div>
                        </div>
                </div>
            </div>
        </div>

        <div class="row gy-5 gx-xl-10">
            <div class="col-xl-4"></div>
            <div class="col-xl-8 mb-xl-10">
                <div class="card card-flush">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <h3>Stok Tersedia</h3>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                   <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                                       class="form-control form-control-solid w-250px ps-14"
                                       placeholder="Search...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="py-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            #
                                        </th>
                                        <th class="min-w-125px text-center">Nama</th>
                                        <th class="min-w-125px text-center">Kategori</th>
                                        <th class="min-w-125px text-center">Stok</th>
                                        <th class="min-w-125px text-center">Actions</th>
                                    </thead>
                                    <tbody class="fw-bold">
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
                                    <template x-if="!isLoading && goodsStock?.data?.length === 0">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(stock, index) in goodsStock?.data" :key="stock.id">
                                        <tr>
                                            <td x-text="startIndex + index++"></td>
                                            <td class="text-center">
                                                <span x-text="stock.name"></span>
                                                <span
                                                    :class="stock.type === 'ASET' ? 'badge badge top-100 start-0 badge-warning ms-2' : 'badge badge top-100 start-0 badge-danger ms-2'"
                                                    x-text="stock.type"></span>
                                            </td>
                                            <td class="text-center" x-text="stock.category_name"></td>
                                            <td class="text-center" x-text="stock.total_stock"></td>
                                            <td>
                                                <a href="#"
                                                   class="btn btn-light-info btn-sm">
                                                    <i class="ki-duotone ki-information fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                            <ul class="pagination float-end mb-4 mt-4">
                                <template x-for="pagination in goodsStock.links">
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
        @include('components.toast')
    </div>
@endsection
@push('script')
    <script>
        function itemStockData() {
            return {
                buttonLoading: false,
                goodsStock: [],
                consumedStock: [],
                ifMutated: null,
                search: '',
                placement: null,
                isLoading: false,
                startIndex: null,
                stockDetail: null,
                incomingItemTransactionQtyInThisMonth: null,
                draftStock: [],
                mustReorderStock: [],
                async init() {
                    await this.getGoodsStock();
                    await this.debitAccounts();
                    await this.creditAccounts();
                    await this.getAllBranch();
                    await this.getDraftStockQty();
                    await this.getMustReorderStock();
                    await this.getIncomingItemsQty();
                    this.modal.addEventListener('hidden.bs.modal', () => {
                        this.form.reset();
                        this.ifMutated = null;
                    });
                },
                async getIncomingItemsQty() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/transactions/item-transaction-qty-in-this-month');
                        this.incomingItemTransactionQtyInThisMonth = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getMustReorderStock() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/goods/stock/must-reorder');
                        this.mustReorderStock = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getGoodsStock() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/goods/stock/data');
                        this.goodsStock = resp.data;
                        this.startIndex = resp.data.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getAllBranch() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: `/select2/branches-data`,
                            dataType: "JSON",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async paginationEndPoint(url) {
                    if (url) {
                        this.goodsStock = [];
                        this.isLoading = true;
                        try {
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                }
                            });
                            this.goodsStock = resp.data
                        } catch (e) {
                            console.log(e)
                        } finally {
                            this.isLoading = false
                        }
                    }
                },
                async getDraftStockQty() {
                    try {
                        const resp = await axios.get('/inventory/goods/draft-stocks/get-qty');
                        this.draftStock = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getMainBranchesWithStock(id) {
                    console.log(id);
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: `/inventory/goods/stock/branch/data/${id}`,
                            dataType: "JSON",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: false
                        }
                    });
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/goods/stock/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.goodsStock = resp.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/goods/stock/filter', {
                            params: {
                                branch_id: $('#branch_id_filter').val(),
                            }
                        });
                        this.goodsStock = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async showStockDetail(id) {
                    await this.getMainBranchesWithStock(id);
                    const resp = await axios.get(`/inventory/goods/stock/show/${id}`);
                    this.stockDetail = resp.data;
                },
                async debitAccounts() {
                    $(".debit-accounts-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Akun",
                        ajax: {
                            url: '/select2/asset-accounts-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async creditAccounts() {
                    $(".credit-accounts-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Akun",
                        ajax: {
                            url: '/select2/kas-accounts-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async save() {
                    try {
                        this.buttonLoading = true;
                        await axios.post('/inventory/goods/consumed-stocks', new FormData(this.form));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.form.reset();
                        this.ifMutated = null;
                        this.modal.hide();
                        await this.init();
                        $(".main-branches-select2").val(null).trigger("change");
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
