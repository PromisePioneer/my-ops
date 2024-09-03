<div class="modal fade" tabindex="-1" id="identity-information-update-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Identitas Karyawan</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-identity-information-update" @submit.prevent="identityInformationUpdate()">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="nik" class="required form-label">No. Identitas</label>
                            <input type="text" id="nik" name="nik" class="form-control form-control-solid"
                                   placeholder="NIK" :value="identityInformation.nik ?? ''"/>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="place_of_birth" class="required form-label">Jenis Kelamin</label>
                            <select name="gender" id="gender" class="form-select form-select-solid">
                                <option value="0" selected>Pilih Jenis Kelamin</option>
                                <option value="Laki Laki" :selected="identityInformation.gender === 'Laki Laki'">Laki
                                    Laki
                                </option>
                                <option value="Perempuan" :selected="identityInformation.gender === 'Perempuan'">
                                    Perempuan
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="place_of_birth" class="required form-label">Jenis Kelamin</label>
                            <select name="religion" id="religion" class="form-select form-select-solid">
                                <option value="0" selected>Pilih Agama</option>
                                <option value="Islam" :selected="identityInformation.religion === 'Islam'">
                                    Islam
                                </option>
                                <option value="Kristen" :selected="identityInformation.religion === 'Kristen'">
                                    Kristen
                                </option>
                                <option value="Hindu" :selected="identityInformation.religion === 'Hindu'">
                                    Hindu
                                </option>
                                <option value="Buddha" :selected="identityInformation.religion === 'Buddha'">
                                    Buddha
                                </option>
                                <option value="Katholik" :selected="identityInformation.religion === 'Katholik'">
                                    Katholik
                                </option>
                                <option value="Konghuchu" :selected="identityInformation.religion === 'Konghuchu'">
                                    Konghuchu
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="place_of_birth" class="required form-label">Tempat Lahir</label>
                            <input type="text" id="place_of_birth" name="place_of_birth"
                                   class="form-control form-control-solid"
                                   placeholder="Tempat Lahir" :value="identityInformation.place_of_birth ?? ''"/>
                        </div>
                        <div class="col-md-6">
                            <label for="date_of_birth" class="required form-label">Tanggal Lahir</label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                   class="form-control form-control-solid"
                                   placeholder="Tanggal Lahir" :value="identityInformation.date_of_birth ?? '' "/>
                        </div>
                    </div>
                    <div class="row my-10">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Perkawinan</label>
                            <div class="mb-2">
                                <input class="form-check-input" type="radio" id="married_status_menikah"
                                       name="marital_status"
                                       value="Menikah"
                                       x-model="identityInformation.marital_status">
                                <span class="form-check-label">Menikah</span>
                            </div>
                            <div>
                                <input class="form-check-input" type="radio" id="married_status_tidak_menikah"
                                       name="marital_status"
                                       value="Tidak Menikah"
                                       x-model="identityInformation.marital_status">
                                <label for="married_status_tidak_menikah">Tidak Menikah</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div x-show="identityInformation.marital_status" x-transition>
                                <label for="married_status" class="required form-label">Status Perkawinan</label>
                                <template x-if="identityInformation.marital_status === 'Menikah'">
                                    <select class="form-select form-select-solid" name="married_status"
                                            id="married_status">
                                        <template x-for="(married, index) in marriedData" :key="index">
                                            <option :value="married.name" x-text="married.name"></option>
                                        </template>
                                    </select>
                                </template>
                                <template x-if="identityInformation.marital_status === 'Tidak Menikah'">
                                    <select class="form-select form-select-solid" name="married_status"
                                            id="married_status">
                                        <template x-for="(married, index) in noMarriedData" :key="index">
                                            <option :value="married.name" x-text="married.name"></option>
                                        </template>
                                    </select>
                                </template>
                            </div>

                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="ktp_attachment" class="required form-label">No. Telepon</label>
                            <input type="number" id="phone_number" name="phone_number"
                                   class="form-control form-control-solid" placeholder="No. Telepon"
                                   :value="identityInformation.phone_number"/>
                        </div>
                        <div class="col-md-6">
                            <label for="ktp_attachment" class="required form-label">KTP</label>
                            <input type="file" id="ktp_attachment" name="ktp_attachment"
                                   class="form-control form-control-solid" accept=".jpg,.png,.jpeg"/>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="home_address" class="required form-label">Alamat</label>
                        <textarea name="home_address" id="home_address" class="form-control form-control-solid"
                                  data-kt-autosize="true" x-text="identityInformation.home_address ?? ''"></textarea>
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
