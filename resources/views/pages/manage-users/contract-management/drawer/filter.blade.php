<div
    id="kt_drawer_example_basic"
    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_example_basic_button"
    data-kt-drawer-close="#kt_drawer_example_permanent_close"
    data-kt-drawer-direction="end"
    data-kt-drawer-width="390px"
>
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title">Filter</h3>
            <div class="card-toolbar">
                <div class="btn btn-sm btn-icon btn-active-light-danger"
                     id="kt_drawer_example_permanent_close">
                    <span class="svg-icon fs-1">
                     <i class="ki-duotone ki-cross fs-2">
                         <span class="path1"></span>
                         <span class="path2"></span>
                     </i>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="d-flex align-items-center py-2">
                    <select class="form-select form-select-solid main-branches-select2"
                            name="branch_id" id="branch_id">
                    </select>
                </div>
                <div class="d-flex align-items-center py-2">
                    <input type="number" name="year" id="year" class="form-control form-control-solid"
                           placeholder="Filter Berdasarkan Tahun">
                </div>
                <div class="d-flex align-items-center py-2">
                    <select class="form-select form-select-solid"
                            name="month" id="month" data-control="select2"
                            data-placeholder="Pilih Bulan">
                        <option></option>
                        <template x-for="month in months" :key="index">
                            <option :value="month.number" x-text="month.name"></option>
                        </template>
                    </select>
                </div>
                <button class="btn btn-light-primary btn-sm" @click="filter()">Filter</button>
            </div>
        </div>
    </div>

</div>
