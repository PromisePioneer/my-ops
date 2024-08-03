<div class="modal fade" tabindex="-1" id="identity-information-update-modal">
    <div class="modal-dialog modal-lg">
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
                        <div class="col-md-12">
                            <label for="nik" class="required form-label">No. Identitas</label>
                            <input type="text" id="nik" name="nik" class="form-control form-control-solid"
                                   placeholder="NIK" :value="identityInformation.nik ?? '-'"/>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="place_of_birth" class="required form-label">Tempat Lahir</label>
                            <input type="text" id="place_of_birth" name="place_of_birth"
                                   class="form-control form-control-solid"
                                   placeholder="Tempat Lahir" :value="identityInformation.place_of_birth ?? '-'"/>
                        </div>
                        <div class="col-md-6">
                            <label for="date_of_birth" class="required form-label">Tanggal Lahir</label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                   class="form-control form-control-solid"
                                   placeholder="Tanggal Lahir" :value="identityInformation.date_of_birth ?? '' "/>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="place_of_birth" class="required form-label">Jenis Kelamin</label>
                            <select name="gender" id="gender" class="form-select form-select-solid">
                                <option value="0" selected>Pilih Jenis Kelamin</option>
                                <option value="Laki Laki" :selected="identityInformation.gender === 'Laki Laki'">Laki Laki</option>
                                <option value="Perempuan" :selected="identityInformation.gender === 'Perempuan'">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="ktp_attachment" class="required form-label">KTP</label>
                            <input type="file" id="ktp_attachment" name="ktp_attachment"
                                   class="form-control form-control-solid" accept=".jpg,.png,.jpeg"/>
                        </div>
                    </div>
                    <div class="row my-10">
                        <div class="col-md-6">
                            <label for="married_status" class="required form-label">Status Perkawinan</label>
                            <select class="form-select form-select-solid" name="" id="" x-model="marriedStatus"
                                    x-on:change="openMarriedStatus">
                                <option value="0" selected>Pilih</option>
                                <option value="menikah">Menikah</option>
                                <option value="tidak menikah">Tidak Menikah</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div x-show="marriedStatus" x-transition>
                                <label for="married_status" class="required form-label">Status Perkawinan</label>
                                <template x-if="marriedStatus === 'menikah'">
                                    <select class="form-select form-select-solid" name="married_status"
                                            id="married_status">
                                        <template x-for="(married, index) in marriedData" :key="index">
                                            <option :value="married.name" x-text="married.name"></option>
                                        </template>
                                    </select>
                                </template>
                                <template x-if="marriedStatus === 'tidak menikah'">
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
                    <div class="mb-4">
                        <label for="home_address" class="required form-label">Alamat</label>
                        <textarea name="home_address" id="home_address" class="form-control form-control-solid"
                                  data-kt-autosize="true" x-text="identityInformation.home_address ?? '-'"></textarea>
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
