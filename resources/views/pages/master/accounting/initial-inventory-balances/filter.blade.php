<div
    id="initial-inventory-balances-filter-drawer"
    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#initial-inventory-balances-filter"
    data-kt-drawer-close="#kt_drawer_example_dismiss_close"
    data-kt-drawer-direction="end"
    data-kt-drawer-width="{default:'100px', 'md': '500px'}"
>
    <div class="card w-100 mb-4">
        <div class="card-header">
            <h3 class="card-title">Filter</h3>
            <div class="card-toolbar">
                <button id="kt_drawer_example_dismiss_close" class="btn btn-light-danger btn-sm">
                    <x-icons.close/>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="mb-4">
                    <x-select2.index
                        name="branch_id"
                        id="branch_id"
                        class="form-select form-select-solid"
                        elementSelector="branches-select2"
                        data-dropdown-parent="#initial-inventory-balances-filter"
                    />
                </div>
                <button type="button" class="btn btn-light-primary btn-sm" @click="filter()" :disabled="buttonLoading">
                    <span x-text="`${buttonLoading ? 'Loading...' : 'Filter'}`"></span>
                </button>
            </div>
        </div>
    </div>
</div>
