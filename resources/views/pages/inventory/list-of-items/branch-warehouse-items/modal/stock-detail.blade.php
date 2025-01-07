<div class="modal fade" tabindex="-1" id="modal-stock-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Kode Barang</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="fas fa-xmark-circle"></i>
                    </span>
                </div>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">SN</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template></template>
                    <tr>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
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
        </div>
    </div>
</div>
