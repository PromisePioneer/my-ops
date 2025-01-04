<div class="modal fade" tabindex="-1" id="modal-sub-branch-detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <button type="button" class="btn btn-light-primary btn-sm" @click="showFormSubDetail()">
                        <i class="ki-duotone ki-click fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        <span x-text="showFormSubBranchDetail ? 'Kembali ke detail' : 'Ubah Sub Cabang'"></span>
                    </button>
                </h5>
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

            <form id="form-edit" @submit.prevent="update(subBranchVal.id)">
                <div class="modal-body">
                    <div x-show="showFormSubBranchDetail === false" x-transition>
                        <table class="table align-middle fs-6 gy-5 table-bordered fs-7">
                            <tr class="w-100">
                                <th>Cabang</th>
                                <th class="w-1px">:</th>
                                <th x-text="subBranchVal?.parent.name"></th>
                            </tr>
                            <tr class="w-100">
                                <th>Nama</th>
                                <th class="w-1px">:</th>
                                <th x-text="subBranchVal?.name"></th>
                            </tr>
                            <tr class="w-100">
                                <th>Lokasi</th>
                                <th class="w-1px">:</th>
                                <th x-text="subBranchVal?.address"></th>
                            </tr>
                        </table>
                    </div>

                    <div x-show="showFormSubBranchDetail === true" x-transition>
                        <div class="mb-10">
                            <label for="name" class="required form-label">Nama</label>
                            <input type="text" id="name" name="name" class="form-control form-control-solid"
                                   placeholder="Nama Cabang" :value="subBranchVal?.name"/>
                        </div>

                        <div class="mb-10">
                            <label for="name" class="required form-label">Alamat</label>
                            <textarea class="form-control form-control-solid" name="address" id="address"
                                      x-text="subBranchVal?.address"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" x-show="showFormSubBranchDetail === true">
                    <button type="submit" class="btn btn-light-primary btn-sm">
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
