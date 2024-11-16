<div class="modal fade" tabindex="-1" id="spk-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form SPK</h5>
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

            <form id="form-spk-create" @submit.prevent="saveSPK({{ $baa->id }})">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal</label>
                        <input type="date" id="date" name="date" class="form-control form-control-solid date"
                               placeholder="Pilih Tanggal" :value="spkVal?.date"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama Kegiatan</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama Kegiatan" :value="spkVal?.name"/>
                    </div>


                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal Awal</label>
                        <input type="date" id="start_date" name="start_date"
                               class="form-control form-control-solid date"
                               placeholder="Pilih Tanggal Awal Kegiatan" :value="spkVal?.start_date"/>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal Akhir Kegiatan</label>
                        <input type="date" id="end_date" name="end_date" class="form-control form-control-solid date"
                               placeholder="Pilih Tanggal Akhir Kegiatan" :value="spkVal?.end_date"/>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Pilih Karyawan (Pemberi SPK)</label>
                        <select name="from" id="selectedFrom" class="form-select form-select-solid users-select2"
                                data-dropdown-parent="#spk-create">
                            <option></option>
                        </select>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Pilih Karyawan (PIC)</label>
                        <select name="to" id="selectedTo" class="form-select form-select-solid users-select2"
                                data-dropdown-parent="#spk-create">
                            <option></option>
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

