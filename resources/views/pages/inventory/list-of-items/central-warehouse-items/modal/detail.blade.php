<div class="modal fade" tabindex="-1" id="modal-get-stocks">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" x-text="stock.central_warehouse_item?.item.name"></h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="ki-duotone ki-technology-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                        <tr>
                            <template x-if="stock.centralWarehouseItem.item?.need_sn === 1">
                                <th class="text-center">SN</th>
                            </template>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody class="fw-bold">
                        <template x-for="stock in stock?.central_warehouse_stock?.data">
                            <tr>
                                <template x-if="stock.centralWarehouseItem.item?.need_sn === 1">
                                    <td class="text-center" x-text="stock.sn"></td>
                                </template>
                                <td class="text-center" x-text="stock.code"></td>
                                <template x-if="stock.status === 0">
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-light-danger">
                                            <i class="bi bi-x-square-fill"></i>
                                            Pending
                                        </button>
                                    </td>
                                </template>
                                <template x-if="stock.status === 1">
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-light-success">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Terverifikasi
                                        </button>
                                    </td>
                                </template>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in stock?.central_warehouse_stock?.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="getPaginationEnpointForStock(pagination.url)"
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
