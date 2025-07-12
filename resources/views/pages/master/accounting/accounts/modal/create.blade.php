<div class="modal fade" tabindex="-1" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Akun</h5>
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

            <form id="form-create" @submit.prevent="save()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Kode</label>
                        <input type="text" id="code" name="code" class="form-control form-control-solid"
                               placeholder="Kode"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama akun"/>
                    </div>


                    <div class="mb-10">
                        <label for="name" class="required form-label">Perusahaan</label>
                        <select name="company_id" id="company_id"
                                class="form-select form-select-solid companies-select2"
                                data-dropdown-parent="#modal-create">
                            <option></option>
                        </select>
                    </div>


                    <div class="mb-10">
                        <label for="name" class="required form-label">Tipe Saldo Awal / Neraca Saldo</label>
                        <select name="trial_balance_type" id="" class="form-select form-select-solid">
                            <option value="" selected>Pilih</option>
                            <option value="debit">Debit</option>
                            <option value="credit">Kredit</option>
                        </select>
                    </div>


                    <div class="mb-10">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" x-model="toggleAccountCategory" value=""
                                   id="flexSwitchDefault"/>
                            <label class="form-check-label" for="flexSwitchDefault">
                                Kategori Akun (Akan Masuk di laporan keuangan) ?
                            </label>
                        </div>
                    </div>


                    <div class="mb-10" x-show="toggleAccountCategory" x-transition x-cloak>
                        <label for="" class="form-label required">Kategori Akun</label>
                        <select name="category_id" id=""
                                class="form-select form-select-solid account-categories-select2"
                                data-dropdown-parent="#modal-create">
                            <option value=""></option>
                        </select>
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
