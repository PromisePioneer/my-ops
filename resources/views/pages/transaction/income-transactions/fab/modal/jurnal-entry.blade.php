<div class="modal fade" tabindex="-1" id="jurnal_entry">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Laporan Jurnal PO #{{ $fab->fab_number }}</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <div class="modal-body">
                <table class="table table-row-bordered gy-7">
                    <thead>
                    <tr class="fw-bolder fs-6 text-gray-800">
                        <th>Akun</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="row in jurnalEntry.original" :key="row.id">
                        <tr>
                            <td>
                                <a href="#" x-text="row.account_name"></a>
                            </td>
                            <td x-text="row.type === 'debit' ? row.amount : '-'"></td>
                            <td x-text="row.type === 'credit' ? row.amount : '-'"></td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
