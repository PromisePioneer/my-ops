<div class="modal fade" tabindex="-1" id="modal-stock-withdrawal-detail">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail pemakaian barang</h5>
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
                <div class="d-flex flex-column flex-xl-row">
                    <div class="flex-column flex-lg-row-auto w-100 w-lg-300px">
                        <div class="card card-custom mb-4">
                            <div class="card-header">
                                <template
                                    x-for="employee in stockWithdrawalDetail?.stock_withdrawal_by_employee">
                                    <div class="card-title">
                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                            <a href="#">
                                                <div class="symbol-label">
                                                    <a href="#" @click="openImage(employee.profile_pic)">
                                                        <img :src="getImageURL(employee.profile_pic ?? null)"
                                                             alt="Image" class="w-100">
                                                    </a>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a href='#'
                                               class="text-gray-800 text-hover-primary mb-1">
                                                <span x-text="employee.name"></span>
                                            </a>
                                            <span class="badge badge-light-info fw-bolder fs-8"
                                                  x-text="employee.roles ?? ''">
                                                    </span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="flex-lg-row-fluid ms-lg-10">
                        <div class="card card-custom mb-4">
                            <div class="card-header p-0">
                                <div class="card-body">
                                    <table class="table table-bordered w-100">
                                        <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Barang</th>
                                            <th>Qty</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <template x-for="item in stockWithdrawalDetail?.stock_withdrawal_items">
                                            <tr>
                                                <td x-text="item.code ?? '-'"></td>
                                                <td x-text="item.item_name"></td>
                                                <td x-text="item.qty"></td>
                                                <td x-text="item.status"></td>
                                            </tr>
                                        </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end mt-4">
                    <table>
                        <tr>
                            <th style="text-align: center; padding: 8px;">
                                <p style="font-size: 12px; margin: 0;">Stocker: </p>
                            </th>
                            <th style="text-align: center; padding: 8px;"></th>
                        </tr>
                        <tr>
                            <th style="text-align: center; padding: 8px;">
                                <template
                                    x-if="stockWithdrawalDetail?.stock_withdrawal?.stocker_signature_after_withdraw !== null">
                                    <a href="#"
                                       @click="openImage(stockWithdrawalDetail?.stock_withdrawal?.stocker_signature_after_withdraw)">
                                        <img
                                            :src="getImageURL(stockWithdrawalDetail?.stock_withdrawal?.stocker_signature_after_withdraw ?? null)"
                                            class="img-fluid w-100px"
                                            alt="Image">
                                    </a>
                                </template>
                            </th>
                            <th style="text-align: center; padding: 8px;">
                        </tr>
                        <tr>
                            <th style="text-align: center; padding: 8px 8px 0 8px;">
                                <p style="font-size: 12px; margin: 0; text-decoration: underline"
                                   x-text="stockWithdrawalDetail?.stock_withdrawal?.stocker.name">
                                </p>
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
