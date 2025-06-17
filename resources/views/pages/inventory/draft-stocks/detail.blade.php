@extends('layouts.template')
@section('page-title', 'Pengkodean ' . $itemCollection->name)
@section('content')
    <div x-data="generateStockCode()">
        @include('pages.inventory.draft-stocks.generate-code')
        <div class="row">
            <div class="col-lg-5">
                <div class="card shadow-sm mb-10">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="card-title mb-10">
                                {{ $itemCollection->name }}
                                tidak ada kode</h3>
                        </div>
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
                                        <td x-text="draftStock.transaction_number"></td>
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
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="card-title mb-10">
                                Stok
                            </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-10">
                                <thead>
                                <tr>
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
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="w-10px pe-2">No</th>
                            <th class="min-w-125px text-center">Kode</th>
                            <th class="min-w-125px text-center"> Tersedia</th>
                            <th class="min-w-125px text-center"> Rusak</th>
                            <th class="min-w-125px text-center">Kondisi</th>
                            <th class="min-w-125px text-center">Status</th>
                            <th class="min-w-125px text-center">Diinput Oleh</th>
                            <th class="min-w-125px text-center">Action</th>
                        </tr>
                        </thead>
                        <template x-if="isLoading">
                            <tbody class="fw-bolder">
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
                        <template x-if="!isLoading && itemCatalog.data?.length === 0">
                            <tbody>
                            <tr>
                                <td colspan="6">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                        <template x-for="(item, index) in itemCatalog?.data" :key="item.id">
                            <tbody class="text-center">
                            <tr>
                                <td x-text="startIndex + index++"></td>
                                <td x-text="item.code"></td>
                                <td x-text="item.available_qty"></td>
                                <td x-text="item.broken_qty"></td>
                                <td class="text-uppercase">
                                    <template x-if="item.condition === 'Rusak'">
                                        <span class="badge bg-light-danger text-danger">Rusak</span>
                                    </template>
                                    <template x-if="item.condition === 'Baik'">
                                        <span class="badge bg-light-success text-success">Baik</span>
                                    </template>
                                    <template x-if="item.condition === 'Diperbaiki'">
                                        <span class="badge bg-light-warning text-warning">Sdg Diperbaiki</span>
                                    </template>
                                </td>
                                <td x-text="item.status"></td>
                                <td x-text="item.created_by"></td>
                                <td>
                                    <template x-if="item.status === 'Tersedia'">
                                        <button class="btn btn-light-danger btn-sm" @click="destroy(item.id)">
                                            <i class="ki-duotone ki-trash fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </button>
                                    </template>
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
                startIndex: null,
                stocks: [],
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
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
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
