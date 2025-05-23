<div class="modal fade" tabindex="-1" id="modal-initial-inventory-balance">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Saldo Awal Persediaan</h5>
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
                <form id="form-initial-inventory-balance" @submit.prevent="saveInitialInventoryBalance(editVal?.id)"
                      enctype="multipart/form-data">
                    <div class="row mb-10">
                        @if(empty(Auth::user()->branch_id))
                            <div class="col-md-6">
                                <label for="branch_id" class="required form-label">Cabang</label>
                                <select class="form-select form-select-solid branches-select2"
                                        name="branch_id"
                                        id="selected-main-branch"
                                        data-dropdown-parent="#modal-initial-inventory-balance">
                                    <option></option>
                                </select>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <label for="date" class="required form-label">Tanggal Pembelian</label>
                            <input type="date" id="date" name="date" class="form-control-solid form-control date"
                                   placeholder="Tanggal Pembelian"
                                   :value="editVal?.date">
                        </div>
                    </div>


                    <div class="row mb-10">

                        <div class="col-md-6">
                            <label for="date" class="required form-label">Supplier</label>
                            <select name="supplier_id" id="selected-supplier"
                                    class="form-select form-select-solid suppliers-select2"
                                    data-dropdown-parent="#modal-initial-inventory-balance">
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <label for="detail" class="required form-label">
                            Keterangan
                        </label>
                        <textarea class="form-control form-control-solid" name="detail" id="detail"
                                  placeholder="Ketarangan" :value="editVal?.detail"
                                  data-kt-autosize="true"></textarea>
                    </div>

                    <div class="row mb-10">
                        <div class="col-md-4">
                            <label for="name" class="required form-label">
                                Nama Barang
                            </label>
                            <select name="item_id" id="selected-item"
                                    class="form-select form-select-solid items-select2"
                                    data-dropdown-parent="#modal-initial-inventory-balance">
                                <option></option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="name" class="required form-label">
                                Harga Satuan
                            </label>
                            <input type="text" class="form-control form-control-solid" name="unit_price"
                                   id="unit_price" :value="parseFloat(editVal?.unit_price)" placeholder="Harga Satuan"/>
                        </div>
                        <div class="col-lg-4">
                            <label for="name" class="required form-label">Qty</label>
                            <input type="number" class="form-control form-control-solid" name="qty" id="qty"
                                   placeholder="Kuantitas" :value="editVal?.qty">
                        </div>
                    </div>


                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Dokumentasi</label>
                            <input type="file" class="form-control form-control-solid" @change="previewAttachmentFile()"
                                   accept=".png, .jpg, .jpeg" x-ref="attachmentFile" name="attachment" id="attachment">
                        </div>

                        <div class="col-lg-6">
                            <label
                                :class="`${attachmentImgSrc.length > 0 ? 'col-form-label required fw-bold fs-6' : 'd-none'}`">
                                Preview
                            </label>
                            <img :src="attachmentImgSrc" class="img-fluid w-100"
                                 @click="openAttachmentImage(attachmentImgSrc)">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Akun Persediaan</label>
                            <select name="stock_account_id" id="selected-stock-account"
                                    class="form-select form-select-solid stock-accounts-select2"
                                    data-dropdown-parent="#modal-initial-inventory-balance">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
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
                </form>
            </div>


        </div>
    </div>
</div>
