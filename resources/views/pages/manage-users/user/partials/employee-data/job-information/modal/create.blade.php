<div class="modal fade" tabindex="-1" id="job-information-update-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Form Data Pekerjaan Karyawan</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-job-information-update" @submit.prevent="jobInformationUpdate()">
                <div class="modal-body">
                    <div class="row mb-7">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">BPJS KES Status</label>
                            <div class="mb-2">
                                <input class="form-check-input" type="radio" id="bpjs_kes_ya" name="bpjs_kes" value="ya"
                                       x-model="bpjsKesStatus">
                                <span class="form-check-label">Ya</span>
                            </div>
                            <div>
                                <input class="form-check-input" type="radio" id="bpjs_kes_tidak" name="bpjs_kes"
                                       value="tidak"
                                       x-model="bpjsKesStatus">
                                <label for="bpjs_kes_tidak">Tidak</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">BPJS KET Status</label>

                            <div class="mb-2">
                                <input class="form-check-input" type="radio" id="bpjs_ket_ya" name="bpjs_ket" value="ya"
                                       x-model="bpjsKetStatus">
                                <span class="form-check-label">Ya</span>
                            </div>
                            <div>
                                <input class="form-check-input" type="radio" id="bpjs_ket_tidak" name="bpjs_ket"
                                       value="tidak"
                                       x-model="bpjsKetStatus">
                                <label for="bpjs_kes_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-md-6" x-show="bpjsKesStatus === 'ya'" x-transition>
                            <label for="bpjs_ket" class="required form-label">No. KIS</label>
                            <input type="text" class="form-control form-control-solid"
                                   :name="bpjsKesStatus === 'ya' ? 'no_kis' : ''" id="no_kis"
                                   :value="jobInformation.no_kis">
                        </div>
                        <div class="col-md-6" x-show="bpjsKetStatus === 'ya'" x-transition>
                            <label for="bpjs_ket" class="required form-label">No. KPJ</label>
                            <input type="text" class="form-control form-control-solid"
                                   :name="bpjsKetStatus === 'ya' ? 'no_kpj' : ''" id="no_kpj"
                                   :value="jobInformation.no_kpj">
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-md-6">
                            <label for="fixed_salary" class="required form-label">Gaji Pokok</label>
                            <input type="number" id="fixed_salary" name="fixed_salary"
                                   class="form-control form-control-solid"
                                   placeholder="Gaji Pokok" :value="jobInformation.fixed_salary ?? 0"/>
                        </div>
                        <div class="col-md-6">
                            <label for="position_allowance" class="required form-label">Tunjangan Jabatan</label>
                            <input type="number" id="position_allowance" name="position_allowance"
                                   class="form-control form-control-solid"
                                   placeholder="Tunjangan Jabatan" :value="jobInformation.position_allowance ?? 0"/>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-md-6">
                            <label for="meal_allowance" class="required form-label">Tunjangan Makan</label>
                            <input type="number" id="meal_allowance" name="meal_allowance"
                                   class="form-control form-control-solid"
                                   placeholder="Tunjangan Jabatan" :value="jobInformation.meal_allowance ?? 0"/>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-md-6">
                            <label for="contract_status" class="required form-label">Status Kontrak</label>
                            <select class="form-select form-select-solid" name="contract_status">
                                <option value="0">Pilih</option>
                                <option value="Tetap" :selected="jobInformation.contract_status === 'Tetap'">
                                    Tetap
                                </option>
                                <option value="Kontrak" :selected="jobInformation.contract_status === 'Kontrak'">
                                    Kontrak
                                </option>
                                <option value="Vendor" :selected="jobInformation.contract_status === 'Vendor'">
                                    Vendor
                                </option>
                                <option value="Training" :selected="jobInformation.contract_status === 'Training'">
                                    Training
                                </option>
                                <option value="Magang" :selected="jobInformation.contract_status === 'Magang'">
                                    Magang
                                </option>
                                <option value="Freelance" :selected="jobInformation.contract_status === 'Freelance'">
                                    Freelance
                                </option>
                                <option value="Non Karyawan"
                                        :selected="jobInformation.contract_status === 'Non Karyawan'">
                                    Non Karyawan
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="bank_account_number" class="required form-label">Nomor Rekening</label>
                            <input type="number" id="bank_account_number" name="bank_account_number"
                                   class="form-control form-control-solid"
                                   placeholder="Tempat Lahir" :value="jobInformation.bank_account_number ?? 0"/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
