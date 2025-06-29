@extends('layouts.template')
@section('page-title', 'Pengkodean ' . $itemCollection->name)
@section('content')
    <div x-data="generateStockCode()">
        @include('pages.inventory.draft-stocks.generate-code')
        <div class="row">
            <div class="col-lg-5">
                <div class="card shadow-sm mb-10">
                    <div class="card-header">
                        <h3 class="card-title">
                            Belum berkode
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-10">
                                <thead>
                                <tr>
                                    <th class="min-w-125px text-center">No. Transaksi</th>
                                    <th class="min-w-125px text-center">Qty</th>
                                    <th class="min-w-125px text-center">Action</th>
                                </tr>
                                </thead>
                                <template x-if="isLoading">
                                    <tbody class="fw-bolder">
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
                                <template x-for="(draftStock, index) in draftStocks.data">
                                    <tbody class="text-center">
                                    <tr>
                                        <td x-text="draftStock.transaction_number ?? 'Persediaan Awal'"></td>
                                        <td x-text="draftStock.qty"></td>
                                        <td>
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-generate-code"
                                                    @click="add(draftStock.id)">
                                                <x-icons.add-item/>
                                                Buat Kode
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card shadow-sm mb-10">
                    <div class="card-header">
                        <h3 class="card-title">
                            Stok
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-10">
                                <thead>
                                <tr>
                                    <th class="min-w-125px text-center">Cabang</th>
                                    <th class="min-w-125px text-center">No. Transaksi</th>
                                    <th class="min-w-125px text-center">Tersedia</th>
                                    <th class="min-w-125px text-center">Dibawa</th>
                                    <th class="min-w-125px text-center">Rusak</th>
                                </tr>
                                </thead>
                                <template x-if="isLoading">
                                    <tbody class="fw-bolder">
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
                                <template x-if="!isLoading && stocks.data?.length === 0">
                                    <tbody>
                                    <tr>
                                        <td colspan="6">
                                            <center>Data Tidak Ditemukan</center>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                                <template x-for="(stock, index) in stocks.data">
                                    <tbody class="text-center">
                                    <tr>
                                        <td x-text="stock.branch_name"></td>
                                        <td x-text="stock.transaction_number"></td>
                                        <td x-text="stock.available_qty"></td>
                                        <td x-text="stock.on_hold_qty"></td>
                                        <td x-text="stock.broken_qty"></td>
                                    </tr>
                                    </tbody>
                                </template>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Katalog Barang</h3>
                <div class="card-toolbar">
                    <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                               <i class="bi bi-search"></i>
                            </span>
                        <input type="text" name="search" x-model="itemCatalogQuery" @input.debounce="searchItemCatalog()"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="min-w-125px text-center">Informasi</th>
                            <th class="min-w-125px text-center">Kode</th>
                            <th class="min-w-125px text-center">Baik</th>
                            <th class="min-w-125px text-center">Rusak</th>
                            <th class="min-w-125px text-center">Status</th>
                            <th class="min-w-125px text-center">Actions</th>
                        </tr>
                        </thead>
                        <template x-if="isLoading">
                            <tbody class="fw-bolder">
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
                        <template x-if="!isLoading && itemCatalog.data?.length === 0">
                            <tbody>
                            <tr>
                                <td colspan="9">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                        <template x-for="(item, index) in itemCatalog?.data" :key="item.id">
                            <tbody class="p-0">
                            <tr>
                                <td class="text-center">
                                    <p x-text="`${item.branch_name}`"></p>
                                </td>
                                <td class="text-center" x-text="item.code"></td>
                                <td class="text-center" x-text="item.available_qty"></td>
                                <td class="text-center" x-text="item.broken_qty"></td>
                                <td>
                                    <p class="text-uppercase text-center">
                                        <template x-if="item.status === 'Tersedia'">
                                            <span class="badge bg-light-success text-success">Tersedia</span>
                                        </template>
                                        <template x-if="item.status === 'Dibawa'">
                                            <span class="badge bg-light-warning text-warning">Dibawa</span>
                                        </template>
                                        <template x-if="item.status === 'Proses Mutasi'">
                                            <span class="badge bg-light-info text-info">Proses Mutasi</span>
                                        </template>
                                    </p>
                                </td>
                                <td>
                                    <a :disabled="item.status !== 'Tersedia'"
                                       class="btn btn-light-info btn-sm mb-4"
                                    >
                                        <x-icons.info/>
                                    </a>
                                    <button :disabled="item.status !== 'Tersedia'"
                                            class="btn btn-light-danger btn-sm mb-4"
                                            @click="destroy(item.id)">
                                        <x-icons.trash/>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                    </table>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a class="btn btn-light-danger btn-sm" href="{{ url('/inventory/draft-stocks') }}">
                        <x-icons.back/>
                        Kembali
                    </a>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in itemCatalog.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="paginateItemCatalog(pagination.url)"
                                        x-html="pagination.label">
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
        @include('components.toast')
    </div>
@endsection
@push('script')
    <script>
        function generateStockCode() {
            return {
                buttonLoading: false,
                draftStocks: [],
                isLoading: false,
                itemId: "{{ $itemCollection->id }}",
                itemCatalog: [],
                stocks: [],
                itemCatalogQuery: '',
                editVal: '',
                autoGenerateCode: null,
                generateCodeModal: new bootstrap.Modal(document.getElementById('modal-generate-code')),
                generateCodeForm: document.getElementById('form-generate-code'),
                async init() {
                    await this.getDraftStock();
                    await this.getItemCatalog();
                    await this.getStock();
                },
                async getStock() {
                    const resp = await axios.get(`/inventory/stocks/data/${this.itemId}`)
                    this.stocks = resp.data;
                },
                async add(id) {
                    const draftStockId = id;
                    await this.generateAutomaticCode(draftStockId);
                    const resp = await axios.get(`/inventory/draft-stocks/show/${draftStockId}`);
                    this.editVal = resp.data;
                },
                async getItemCatalog() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/inventory/item-catalog/data/${this.itemId}`);
                        this.itemCatalog = resp.data
                        this.startIndex = this.itemCatalog.from;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginateItemCatalog(url) {
                    if (url) {
                        this.itemCatalog = [];
                        this.isLoading = true;
                        try {
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                }
                            });
                            this.itemCatalog = resp.data
                        } catch (e) {
                            console.log(e)
                        } finally {
                            this.isLoading = false
                        }
                    }
                },
                async generateAutomaticCode(id) {
                    const resp = await axios.get(`/inventory/item-catalog/generate-code/${id}`);
                    this.autoGenerateCode = resp.data;
                },
                async generateItemCatalogCode(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/item-catalog/${id}`, new FormData(this.generateCodeForm))
                            .then(async () => {
                                await this.successResponseAfterSubmit(id);
                            });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    try {
                        const resp = await axios.get(`/inventory/item-catalog/${id}`);
                        this.editVal = resp.data;
                    } catch (e) {
                        console.log(e);
                    }
                },
                async getDraftStock() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/inventory/draft-stocks/detail/data/${this.itemId}`);
                        this.draftStocks = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data yang dihapus tidak akan dapat kembali.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/inventory/item-catalog/destroy/${id}`);
                            await showAlert('success', 'Data sukses dihapus')
                                .then(async () => {
                                    await this.init();
                                });
                        } catch (error) {
                            await showAlert('error', error.response.data.message);
                        }
                    });
                },
                async searchItemCatalog() {
                    const resp = await axios.get(`/inventory/item-catalog/search-by-item/${this.itemId}`, {
                        params: {
                            search: this.itemCatalogQuery
                        }
                    })

                    this.itemCatalog = resp.data;
                },
                async successResponseAfterSubmit(id) {
                    await this.init();
                    this.generateCodeForm.reset();
                    await this.generateAutomaticCode(id);
                    await showAlert('success', 'Data berhasil disimpan');
                    await this.getItemCatalog();
                }
            }
        }
    </script>
@endpush
