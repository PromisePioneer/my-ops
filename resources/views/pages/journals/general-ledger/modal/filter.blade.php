<div class="modal fade" tabindex="-1" id="modal-filter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Cabang</h5>
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

            <form id="form-filter" @submit.prevent="filter()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Bulan</label>
                        <select class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Pilih Bulan" name="month" id="month">
                            <option></option>
                            <template x-for="(period,index) in periods" :key="index">
                                <option :value="period.value" x-text="period.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tahun</label>
                        <input type="text" class="form-control form-control-solid" name="year" id="year"
                               placeholder="Tahun">
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
                        <span x-text="buttonLoading ? 'Loading...' : 'Filter'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
