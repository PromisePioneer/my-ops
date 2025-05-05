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
                <div class="mb-4">
                    @can('Filter Data Riwayat Absensi Berdasarkan Cabang')
                        <select class="form-select form-select-solid form-select-sm main-branches-select2"
                                name="branch_id"
                                id="branch_id">
                            <option></option>
                        </select>
                    @endcan
                </div>
                <div class="mb-4">
                    <select class="form-select form-select-solid form-select-sm roles-select2" name="role_id"
                            id="role_id"></select>
                </div>
                <div class="mb-4">
                    <input type="text" name="date" id="date" x-model="date"
                           class="form-control form-control-solid form-control-lg date-picker"
                           placeholder="Tgl awal - akhir"/>
                </div>
                <div class="mb-4">
                    <select name="sorting" class="form-select form-select-solid form-select-sm" id="sorting"
                            data-control="select2"
                            data-placeholder="Urutkan Berdasarkan" data-allow-clear="true">
                        <option></option>
                        <option class="Alfa">Alfa</option>
                        <option class="Izin">Izin</option>
                        <option class="Sakit">Sakit</option>
                    </select>
                </div>
                <button class="btn btn-light-primary btn-sm" @click="filter()">Filter</button>
            </div>
        </div>
    </div>

</div>
