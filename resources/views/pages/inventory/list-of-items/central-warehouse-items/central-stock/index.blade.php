<div x-data="centralStockData()">
    @include('pages.inventory.list-of-items.central-warehouse-items.central-stock.modal.stock-detail')
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
                <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                    <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                        <th class="w-10px pe-2">No</th>
                        <th class="min-w-125px">Nama Barang</th>
                        <th class="min-w-125px">Total Stok (Terverifikasi)</th>
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
                    <template x-if="!isLoading && itemStock.data?.length === 0">
                        <tbody class="fw-bold">
                        <tr>
                            <td colspan="9">
                                <center>Data Tidak Ditemukan</center>
                            </td>
                        </tr>
                        </tbody>
                    </template>
                    <template x-for="(item, index) in itemStock?.data" :key="item.id">
                        <tbody class="fw-bold text-center">
                        <tr>
                            <td x-text="startIndex + index++"></td>
                            <td x-text="`${item.name}`"></td>
                            <td x-text="item.stock"></td>
                            <td>

                                    <button class="btn btn-light-info btn-sm" data-bs-target="#modal-stock-detail"
                                            data-bs-toggle="modal" @click="getItemStockDetail(item.id)">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                            </td>
                        </tr>
                        </tbody>
                    </template>
                </table>
            </div>
            <ul class="pagination float-end mb-4 mt-4">
                <template x-for="pagination in itemStock.links">
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
@push('script')
    <script>
        function centralStockData() {
            return {
                buttonLoading: false,
                isLoading: false,
                startIndex: null,
                itemStock: [],
                itemStockDetail: [],
                search: '',
                async init() {
                    await this.getStockData();
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.itemStock = resp.data
                    }
                },
                async itemStockDetailpaginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.itemStockDetail = resp.data
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/list-of-items/central-warehouse-items/search-item', {
                            params: {
                                search: this.search,
                            }
                        });
                        this.itemStock = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getStockData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/list-of-items/central-warehouse-items/stock-data')
                        this.itemStock = resp.data
                        this.startIndex = this.itemStock.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getItemStockDetail(itemId) {
                    this.isLoading = true
                    try {
                        const resp = await axios.get(`/inventory/list-of-items/central-warehouse-items/stock-data/detail/${itemId}`)
                        this.itemStockDetail = resp.data;
                        this.startIndex = this.itemStockDetail.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
            }
        }
    </script>
@endpush
