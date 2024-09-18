<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Bonus Sales</h5>
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

            <form id="form-edit" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="row mb-10">
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Tanggal Aktif</label>
                            <input type="date" id="date_active" name="date_active"
                                   class="form-control form-control-solid date"
                                   placeholder="Pilih Tanggal" :value="editVal.date_active"/>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control form-control-solid" name="customer_name"
                                   id="customer_name" placeholder="Nama Pelanggan" :value="editVal.customer_name">
                        </div>
                    </div>


                    <div class="row mb-10">
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Sales</label>
                            <select name="user_id" id="selectedUser"
                                    class="form-control form-control-solid users-select2"
                                    data-dropdown-parent="#modal-edit">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Paket Broadband</label>
                            <select name="packet_id" id="selectedBroadbandPacket"
                                    class="form-control form-control-solid packet-select2"
                                    data-dropdown-parent="#modal-edit">
                                <option></option>
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
