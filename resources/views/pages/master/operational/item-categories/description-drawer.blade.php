<div
    id="kt_drawer_example_basic"
    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#item_category_description_drawer"
    data-kt-drawer-close="#kt_drawer_example_permanent_close"
    data-kt-drawer-direction="end"
    data-kt-drawer-width="390px"
>
    <div class="card shadow-sm mb-4 w-100">
        <div class="card-header">
            <h3 class="card-title">Informasi</h3>
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
            <p class="fw-bolder mb-0">Deskripsi</p>
            <p x-text="editVal.description"></p>

            <p class="fw-bolder mb-0">Penjelasan</p>
            <p x-text="editVal.notes"></p>
        </div>
    </div>

</div>
