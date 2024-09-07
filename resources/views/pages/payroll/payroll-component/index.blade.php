<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
            <th class="text-center w-lg-300px">Tunjangan</th>
            <th class="text-center">Pengurangan</th>
            <th class="text-center">Benefit</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <template x-for="allowance in allowances" :key="allowance.id">
                    <div class="btn btn-light-info text-uppercase m-1" @click="destroyAllowance(allowance.id)"
                         x-text="allowance.name">
                    </div>
                </template>
            </td>
            <td class="text-center">Accountant</td>
            <td class="text-center">Tokyo</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-center">
                <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modal-allowance-create">
                    Tambah Tunjangan
                </button>
            </td>
            <td class="text-center">
                <button class="btn btn-light-primary btn-sm">
                    Tambah Pengurangan
                </button>
            </td>
            <td class="text-center">
                <button class="btn btn-light-primary btn-sm">
                    Tambah Benefit
                </button>
            </td>
        </tr>
        </tfoot>
    </table>
</div>


