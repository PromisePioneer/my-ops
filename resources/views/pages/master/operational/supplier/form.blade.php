<div class="modal fade" id="modal-supplier">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Supplier</h5>
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
                <form id="form-supplier" @submit.prevent="saveSupplier(editVal?.id ?? null)">
                    <div class="card-title mb-4">
                        <h3 class="fw-bolder text-decoration-underline">A. Informasi Identitas</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Kode</label>
                                <input type="text" id="code" name="code" class="form-control form-control-solid"
                                       placeholder="Kode Supplier" :value="editVal?.code"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Nama</label>
                                <input type="text" id="name" name="name" class="form-control form-control-solid"
                                       placeholder="Nama" :value="editVal?.name"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Negara</label>
                                <input type="text" id="country" name="country" class="form-control form-control-solid"
                                       placeholder="Negara" :value="editVal?.country"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Provinsi</label>
                                <input type="text" id="province" name="province" class="form-control form-control-solid"
                                       placeholder="Provinsi" :value="editVal?.province"/>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Kota</label>
                                <input type="text" id="city" name="city" class="form-control form-control-solid"
                                       placeholder="Kota" :value="editVal?.city"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Kode Pos</label>
                                <input type="text" id="postal_code" name="postal_code"
                                       class="form-control form-control-solid"
                                       placeholder="Kode Pos" :value="editVal?.postal_code"/>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="mb-10">
                            <label for="name" class="required form-label">Alamat</label>
                            <textarea class="form-control form-control-solid"
                                      data-kt-autosize="true"
                                      id="address"
                                      name="address"
                                      placeholder="Alamat"
                                      x-text="editVal.address">
                            </textarea>
                        </div>
                    </div>

                    <div class="card-title mb-4">
                        <h3 class="fw-bolder text-decoration-underline">B. Informasi Kontak</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Email</label>
                                <input type="text" id="email" name="email" class="form-control form-control-solid"
                                       placeholder="Email" :value="editVal?.email"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Telepon</label>
                                <input type="text" id="phone_number" name="phone_number"
                                       class="form-control form-control-solid"
                                       placeholder="Telepon" :value="editVal?.phone_number"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="name" class="required form-label">Fax</label>
                                <input type="text" id="fax" name="fax"
                                       class="form-control form-control-solid"
                                       placeholder="FAX" :value="editVal?.fax"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="name" class="required form-label">NPWP</label>
                                <input type="text" id="npwp" name="npwp"
                                       class="form-control form-control-solid"
                                       placeholder="NPWP" :value="editVal?.npwp"/>
                            </div>
                        </div>
                    </div>


                    <div class="card-title mb-4">
                        <h3 class="fw-bolder text-decoration-underline">B. Informasi Pembayaran</h3>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Nomor Rekening</label>
                                <input type="text" id="bank_account_number" name="bank_account_number"
                                       class="form-control form-control-solid"
                                       placeholder="Nomor Rekening" :value="editVal?.bank_account_number"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">A/N Rekening</label>
                                <input type="text" id="bank_account_name" name="bank_account_name"
                                       class="form-control form-control-solid"
                                       placeholder="Atas Nama" :value="editVal?.bank_account_name"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Bank</label>
                                <input type="text" id="bank_name" name="bank_name"
                                       class="form-control form-control-solid"
                                       placeholder="Bank" :value="editVal?.bank_name"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="required form-label">Pajak</label>
                                <select name="tax_type" id="tax_type" class="form-select form-select-solid">
                                    <option value="PPN" :selected="editVal?.tax_type ==='PPN'">
                                        PPN
                                    </option>
                                    <option value="PPH 23" :selected="editVal?.tax_type ==='PPH 23'">
                                        PPH 23
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
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
