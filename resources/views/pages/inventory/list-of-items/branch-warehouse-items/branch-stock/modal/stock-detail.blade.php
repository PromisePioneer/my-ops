<div class="modal fade" tabindex="-1" id="modal-stock-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Detail Stok</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="fas fa-xmark-circle"></i>
                    </span>
                </div>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Cabang</th>
                            <th>Total Stok</th>
                        </tr>
                        </thead>
                        <template x-for="(stock, index) in itemStockDetail.data" :key="index">
                            <tbody>
                            <tr class="text-center">
                                <td x-text="startIndex + index++"></td>
                                <td x-text="stock.name"></td>
                                <td x-text="`${stock.stock > 0 ? stock.stock : 0} ${stock.unitType ?? ''}`"></td>
                            </tr>
                            </tbody>
                        </template>
                    </table>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in itemStockDetail.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="itemStockDetailpaginationEndPoint(pagination.url)"
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
