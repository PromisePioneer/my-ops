<div class="modal fade" tabindex="-1" id="jurnal-entry">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $invoice->invoice_number }}</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
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
                    <template x-for="row in jurnalEntry" :key="row.id">
                        <tr>
                            <td>
                                <a :href="`/account-master/account-transaction/detail/${row.account_id}`" x-text="`${row.code} - ${row.name}`"></a>
                            </td>
                            <td x-text="formatNumber(row.debit)"></td>
                            <td x-text="formatNumber(row.credit)"></td>
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
