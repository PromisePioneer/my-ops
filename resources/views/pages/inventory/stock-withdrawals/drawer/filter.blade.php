<div
    id="stock_withdrawal_filter"
    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#stock_withdrawal_filter_button"
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
            @can('Filter Data Pemakaian Barang Berdasarkan Cabang')
                <div class="mb-4">
                    <select class="form-select form-select-solid form-select-sm main-branches-select2"
                            name="branch_id"
                            id="branch_id"
                    >
                        <option></option>
                    </select>
                </div>
                @endcan
                <div class="mb-4">
                    <input type="date" name="date" id="date" x-model="date"
                           class="form-control form-control-solid form-control-sm date-picker"
                           placeholder="Tgl awal - akhir"/>
                </div>
                <button class="btn btn-light-primary btn-sm" @click="filter()">Filter</button>
            </div>
        </div>
    </div>

</div>
