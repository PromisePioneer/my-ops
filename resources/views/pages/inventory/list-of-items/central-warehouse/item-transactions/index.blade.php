<div x-data="itemTransactionData()">
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
                <table class="table align-middle table-bordered table-row-dashed fs-6 gy-5" id="kt_table_users">
                    <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                        <th class="w-10px pe-2">
                            No
                        </th>
                        <th class="min-w-125px">Tanggal Masuk</th>
                        <th class="min-w-125px">No.PO</th>
                        <th class="min-w-125px">Barang</th>
                        <th class="min-w-125px">Kuantitas</th>
                        <th class="min-w-125px">Lokasi</th>
                        <th class="min-w-125px">Status Barang</th>
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
                    <template x-if="!isLoading && warehouseStocks.data?.length === 0">
                        <tbody class="fw-bold">
                        <tr>
                            <td colspan="9">
                                <center>Data Tidak Ditemukan</center>
                            </td>
                        </tr>
                        </tbody>
                    </template>
                    <template x-for="(item, index) in itemTransactions?.data" :key="item.id">
                        <tbody class="fw-bold text-center">
                        <tr>
                            <td x-text="startIndex + index++"></td>
                            <td x-text="formatDate(item.date)"></td>
                            <td x-text="item.po.po_number"></td>
                            <td x-text="item.item.name"></td>
                            <td x-text="`${item.qty} ${item.item.unit_type?.name}`"></td>
                            <td x-text="`${item.warehouse.name}`"></td>
                            <td>
                                <span
                                    :class="`${item.type === 'in' ? 'badge bg-success text-white' : 'badge bg-danger text-white'}`"
                                    x-text="item.type === 'in' ? 'Barang Masuk' : 'Barang Keluar'"></span>
                            </td>
                            <td>
                                <template x-if="item.item.need_sn === 1 && item.status === 1">
                                    <a :href="`/inventory/list-of-items/central-warehouse-items/detail/${item.id}`"
                                       class="btn btn-sm btn-light-primary">
                                        <i class="bi bi-box-arrow-in-right fw-bold"></i>
                                    </a>
                                </template>
                                <template x-if="item.status === 1">
                                    <a :href="`/inventory/list-of-items/central-warehouse-items/distribute-item/${item.id}`"
                                       class="btn btn-sm btn-light-primary">
                                        <i class="bi bi-send"></i>
                                        Kirim Barang
                                    </a>
                                </template>
                                <template x-if="item.status === 0">
                                    <button
                                        @click="confirm(item.id)"
                                        class="btn btn-sm btn-light-primary">
                                        <i class="bi bi-send"></i>
                                        Terima Barang
                                    </button>
                                </template>
                            </td>
                        </tr>
                        </tbody>
                    </template>
                </table>
            </div>
            <ul class="pagination float-end mb-4 mt-4">
                <template x-for="pagination in itemTransactions.links">
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
        function itemTransactionData() {
            return {
                isLoading: false,
                itemTransactions: [],
                startIndex: null,
                search: '',
                async init() {
                    await this.getItemTransactions();
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.itemTransactions = resp.data
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/list-of-items/item-transactions/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.itemTransactions = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getItemTransactions() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/list-of-items/item-transactions/data');
                        this.startIndex = resp.data.from
                        this.itemTransactions = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async confirm(id) {
                    await showConfirmModal('Anda yakin?', 'Barang sudah diterima ?', 'Ya, Konfirmasi!', async () => {
                        try {
                            await axios.post(`/inventory/list-of-items/item-transactions/confirm/${id}`);
                            await showAlert('success', 'Data sukses diterima');
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
