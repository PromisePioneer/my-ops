@php use Carbon\Carbon;use function App\Helper\formatDate; @endphp
<div class="modal fade" tabindex="-1" id="modal-used-item">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pemakaian Barang per
                    tanggal {{ formatDate(Carbon::now()->format('d-m-Y')) }}</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2"
                     data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="ki-duotone ki-technology-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </div>

            <form id="form-used-item" @submit.prevent="save()">
                <div class="modal-body">
                    <div class="row mb-10">
                        @if(empty(Auth::user()->branch_id))
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Cabang</label>
                                <select name="branch_id" id="branch_id"
                                        class="form-select form-select-solid main-branches-select2"
                                        data-dropdown-parent="#modal-used-item">
                                    <option></option>
                                </select>
                            </div>
                        @endif
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Jumlah Pemakaian</label>
                            <input type="number" class="form-control form-control-solid" name="qty" id="qty"
                                   placeholder="Jumlah Pemakaian">
                        </div>
                    </div>


                    <input type="hidden" name="goods_id" id="goods_id" :value="stockDetail?.id">
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
