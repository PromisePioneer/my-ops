@extends('layouts.template')
@section('page-title', 'Kirim Barang')
@section('content')

    <div x-data="sendItem()">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-light-danger btn-sm mb-6"
                   href="{{ url('inventory/list-of-items/central-warehouse-items') }}">
                    Kembali
                </a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6" x-model="placement">
                                <label class="col-form-label required fw-bold fs-6">Tujuan Pengiriman</label>
                                <select name="placement" id="selectedPlacement"
                                        class="form-select form-select-solid user-placement-select2">
                                    <option value="0" selected>Pilih</option>
                                    <option value="Cabang">Cabang</option>
                                    <option value="Gudang">Gudang</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label required fw-bold fs-6">Barang</label>
                                <select name="item_id" id="item_id" class="form-select form-select-solid goods-select2">
                                    <option></option>
                                </select>
                            </div>
                        </div>


                        <div class="row mb-4">
                            <div class="col-lg-6" x-show="placement === 'Cabang'" x-transition x-cloak>
                                <label class="col-form-label required fw-bold fs-6">Cabang</label>
                                <select :name="`${placement === 'Cabang' ? 'branch_id' : ''}`"
                                        class="form-select form-select-solid branches-select2">
                                </select>
                            </div>
                            <div class="col-lg-6" x-show="placement === 'Gudang'" x-transition x-cloak>
                                <label class="col-form-label required fw-bold fs-6">Gudang</label>
                                <select :name="`${placement === 'Gudang' ? 'warehouse_id' : ''}`"
                                        class="form-select form-select-solid warehouses-select2">
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Keterangan</label>
                                <textarea name="notes" id="notes" data-kt-autosize="true"
                                          class="form-control form-control-solid"></textarea>
                            </div>
                        </div>

                        <div class="row mb-4 mt-10" x-show="stocks.length !== 0" x-transition x-cloak>
                            <div>
                                <h1 class="text-center mb-4 text-uppercase text-decoration-underline">Data Stok
                                    Barang</h1>
                            </div>
                            {{--                            <div class="col-12">--}}
                            {{--                                <input type="hidden" :name="`selectedStock[]`" :value="selectedCheckBox">--}}
                            {{--                            </div>--}}
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed table-bordered fs-6 gy-5"
                                       id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox"
                                                       @click="toggleAllCheckBox()" id="toggleAllCheckBox">
                                            </div>
                                        </th>
                                        <th>No. PO</th>
                                        <th>SN</th>
                                        <th>Lokasi Sekarang</th>
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
                                    <template x-if="!isLoading && stocks.data?.length === 0 || stocks.data === null">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="stock in stocks.data" :key="stock.id">
                                        <tbody>
                                        <tr class="text-center">
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <input :class="`form-check-input checkbox-${stock.id}`"
                                                           type="checkbox" :value="stock.id"
                                                           :id="'checkbox-' + stock.id"/>
                                                </div>
                                            </td>
                                            <td x-text="stock.po.po_number"></td>
                                            <td x-text="stock.sn"></td>
                                            <td x-text="stock.warehouse.name"></td>
                                        </tr>
                                        </tbody>
                                    </template>
                                </table>
                                <ul class="pagination float-end mb-4 mt-4">
                                    <template x-for="pagination in stocks.links">
                                        <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                            <button type="button" class="page-link"
                                                    @click="paginationEndPoint(pagination.url)"
                                                    x-html="pagination.label">
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>


                        <div class="row mb-4 mt-10" x-show="selectedStocks.length !== 0" x-transition x-cloak>
                            <div class="text-center">
                                <h1 class=" mb-4 text-uppercase text-decoration-underline">
                                    Data Barang Dipilih
                                </h1>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed table-bordered fs-6 gy-5"
                                       id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                        <th class="w-10px pe-2">
                                            No
                                        </th>
                                        <th>No. PO</th>
                                        <th>SN</th>
                                        <th>Lokasi Tujuan</th>
                                        <th>Action</th>
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
                                    <template
                                        x-if="!isLoading && selectedStocks.data?.length === 0 || selectedStocks.data === null">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="(stock, index) in selectedStocks.data" :key="stock.id">
                                        <tbody>
                                        <tr class="text-center">
                                            <td x-text="startIndex + index++"></td>
                                            <td x-text="stock.po.po_number"></td>
                                            <td x-text="stock.sn"></td>
                                            <td x-text="branch ?? warehouse ?? 'Lokasi belum diset'"></td>
                                            <td>
                                                <button @click="deleteSelectedStock(stock.id)"></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                </table>
                                <ul class="pagination float-end mb-4 mt-4">
                                    <template x-for="pagination in selectedStocks.links">
                                        <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                            <button type="button" class="page-link"
                                                    @click="paginationEndPointSelectedStock(pagination.url)"
                                                    x-html="pagination.label">
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <div class="separator py-2"></div>

                        <div class="float-end d-flex py-6 px-9">
                            <button type="reset" class="btn btn-light btn-active-light-primary me-2 btn-sm">Reset
                            </button>
                            <button type="submit" class="btn btn-sm btn-light-primary"
                                    :disabled="buttonLoading">
                                <i class="ki-duotone ki-click fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                                <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                            </button>
                        </div>
                    </div>
                </form>
                @include('components.toast')
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function sendItem() {
            return {
                isLoading: false,
                placement: false,
                buttonLoading: false,
                selectAll: false,
                singleChecked: false,
                stocks: [],
                selectedCheckBox: [],
                selectedStocks: [],
                form: document.getElementById('form'),
                startIndex: null,
                warehouse: null,
                branch: null,
                async init() {
                    await this.getBranchData();
                    await this.getWarehouseData();
                    await this.getGoodsData();
                },
                async paginationEndPoint(url) {
                    if (url) {
                        this.selectAll = !this.selectAll;
                        const resp = await axios.get(`${url}`);
                        document.getElementById('toggleAllCheckBox').checked = false;
                        this.stocks = resp.data;

                        await this.selectedStocks.data.forEach((stock) => {
                            console.log(document.querySelector(`.checkbox-${stock.id}`).checked = true);
                            // document.getElementById(`checkbox-${stock.id}`).checked = true;
                        });
                    }
                },
                async toggleAllCheckBox() {
                    this.selectAll = !this.selectAll;
                    this.singleChecked = false;
                    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = this.selectAll;
                        if (this.selectAll) {
                            this.selectedCheckBox.push(checkbox.value);
                        }
                    });
                    this.selectedCheckBox.shift();
                    await this.selectedStock();
                },
                async selectCheckBox(event) {
                    const checkboxId = event.target.value;
                    if (event.target.checked) {
                        this.selectedCheckBox.push(checkboxId);
                    } else {
                        const index = this.selectedCheckBox.indexOf(checkboxId);
                        if (index !== -1) {
                            this.selectedCheckBox.splice(index, 1);
                        }
                    }
                    await this.selectedStock();
                },
                async getBranchData() {
                    let self = this;
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/inventory/goods/goods-transaction/branches/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', (e) => {
                        self.branch = e.params.data.text;
                        self.warehouse = null;
                    });
                },
                async deleteSelectedStock(stockId) {

                },
                async getWarehouseData() {
                    let self = this;
                    $(".warehouses-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Gudang",
                        ajax: {
                            url: '/inventory/goods/goods-transaction/warehouses/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', (e) => {
                        self.warehouse = e.params.data.text;
                        self.branch = null;
                    });
                },
                async getGoodsData() {
                    $(".goods-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Barang",
                        ajax: {
                            url: '/inventory/goods/goods-transaction/goods/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', async (e) => {
                        await this.getStock(e.params.data.id)
                    });
                },
                async getStock(id) {
                    this.buttonLoading = true;
                    try {
                        const resp = await axios.get(`/inventory/goods/goods-transaction/stock/data/${id}`);
                        this.stocks = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async selectedStock() {
                    console.log(this.branch);

                    const selected = this.selectedCheckBox.join(",");
                    try {
                        const resp = await axios.get('/inventory/goods/goods-transaction/stock/selected', {
                            params: {
                                selected_stock: selected
                            }
                        });
                        this.selectedStocks = resp.data;
                        this.startIndex = this.selectedStocks.from
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPointSelectedStock(url) {
                    if (url) {
                        const selected = this.selectedCheckBox.join(",");
                        const resp = await axios.get(`${url}`, {
                            params: {
                                selected_stock: selected
                            }
                        });
                        this.selectedStocks = resp.data;
                        this.startIndex = this.selectedStocks.from;
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/list-of-items/central-warehouse-stocks/send-item/save/${this.id}`, new FormData(this.form));
                        await this.form.reset();
                        await showAlert('success', 'Data berhasil disimpan');
                    } catch (e) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
