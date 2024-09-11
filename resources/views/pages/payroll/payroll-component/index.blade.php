<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
            <th class="text-center">Tunjangan</th>
            <th class="text-center">Pengurangan</th>
            <th class="text-center">Benefit</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <ol class="lh-xxl">
                    <li>
                        <a href="{{ url('payroll/allowances/position') }}" class="fw-bold">
                            Tunjangan Jabatan
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payroll/allowances/meal') }}" class="fw-bold">
                            Tunjangan Makan
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payroll/allowances/transportation') }}" class="fw-bold">
                            Tunjangan Transportasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payroll/allowances/overtime') }}" class="fw-bold">
                            Tunjangan Lembur
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payroll/allowances/thr') }}" class="fw-bold">
                            Tunjangan Hari Raya
                        </a>
                    </li>
                </ol>
            </td>
            <td>
                <ol class="lh-xxl">
                    <li>
                        <a href="{{ url('payroll/deduction/sla') }}"
                           class="fw-bold">
                            Denda SLA
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payroll/deduction/nine-past-fiveteen-late/') }}"
                           class="fw-bold">
                            Denda 9.15
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payroll/deduction/additional-deduction') }}"
                           class="fw-bold">
                            Denda Lainnya
                        </a>
                    </li>
                </ol>
            </td>
            <td>
                <ol class="lh-xxl">
                    <li>
                        <a href="{{ url('payroll/benefit/sales-bonus') }}"
                           class="fw-bold">
                            Bonus Sales
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payroll/deduction/nine-past-fiveteen-late/') }}"
                           class="fw-bold">
                            Bonus Penjualan
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payroll/deduction/additional-deduction') }}"
                           class="fw-bold">
                            Bonus Project
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payroll/deduction/additional-deduction') }}"
                           class="fw-bold">
                            Bonus Lainnya
                        </a>
                    </li>
                </ol>
            </td>
        </tr>
        </tbody>
    </table>
</div>


