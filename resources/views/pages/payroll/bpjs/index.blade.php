<table class="table table-row-dashed table-row-gray-300 gy-7">
    <thead>
    <tr class="fw-bolder fs-6 text-gray-800">
        <th>Program</th>
        <th>Company Rate</th>
        <th>Employee Rate</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <template x-for=" bpjs in bpjsKet" :key="bpjs.id">
        <tr>
            <td x-text="bpjs.name"></td>
            <td x-text="`${bpjs.company_rate ?? '0'}%`"></td>
            <td x-text="`${bpjs.employee_rate ?? '0'}%`"></td>
            <td>
                <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modal-bpjs-ket-edit" @click="editBpjsKetRate(bpjs.id)">
                    <i class="fas fa-edit"></i>
                </button>
            </td>
        </tr>
    </template>
    </tbody>
</table>

