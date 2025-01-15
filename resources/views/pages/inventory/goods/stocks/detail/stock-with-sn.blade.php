<div x-data="goodsStockDetail()">
    <div class="d-flex align-items-center">
        <form id="form-delete" @submit.prevent="destroy()" class="me-3">
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
        <form id="form-confirm" @submit.prevent="confirm()">
            <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
            <button type="submit" class="btn btn-light-info btn-sm mt-5"
                    x-show="selectedCheckBox.length > 0"
                    x-transition x-cloak>
                <i class="bi bi-check-circle-fill"></i>
                Konfirmasi
            </button>
        </form>
    </div>
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
        </div>
        <div class="card-body py-3">
            <div class="py-5">
                <div class="table-responsive">
                    <table class="table table-bordered fs-6 gy-5" id="kt_table_users">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                            <th class="w-10px pe-2">
                                No
                            </th>
                            <th class="min-w-125px">Tanggal PO</th>
                            <th class="min-w-125px">PO</th>
                            <th class="min-w-125px">Qty</th>
                            <th class="min-w-125px">Lokasi</th>
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
                        <template x-if="!isLoading && goodsPurchaseOrder.data?.length === 0">
                            <tbody class="fw-bold">
                            <tr>
                                <td colspan="9">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                        <template x-for="(po, index) in goodsPurchaseOrder?.data"
                                  :key="po.id">
                            <tbody class="fw-bold text-center">
                            <tr>
                                <td x-text="startIndex + index++"></td>
                                <td x-text="formatDate(po.date)"></td>
                                <td x-text="po.po_number"></td>
                                <td x-text="po.qty"></td>
                                <td x-text="po.warehouse?.name ?? po.branch?.name"></td>
                                <td>
                                    <a :href="`/inventory/goods/stock/detail/po/generate-sn/${po.id}`"
                                       class="btn btn-light-primary btn-sm">
                                        <i class="bi bi-box-arrow-in-right fw-bold"></i>
                                        Buat SN
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                    </table>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ url('inventory/goods/stock') }}"
                       class="btn btn-light-danger btn-sm">Kembali</a>
                    <ul class="pagination float-end">
                        <template x-for="pagination in goodsPurchaseOrder?.links">
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



@push('script')
    <script>
        function goodsStockDetail() {
            return {
                search: '',
                isLoading: false,
                id: "{{ $goods->id }}",
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                goodsPurchaseOrder: {},
                async init() {
                    await this.getGoodsPurchaseOrder();
                },
                async getGoodsPurchaseOrder() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/inventory/goods/stock/detail/po/data/${this.id}`);
                        this.goodsPurchaseOrder = resp.data;
                        this.startIndex = resp.data.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/inventory/goods/stock/detail/po/search/${this.id}`, {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.goodsPurchaseOrder = resp.data;
                        this.startIndex = resp.data.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
