<div class="px-lg-20">
    <form id="payroll-cutoff-form" @submit.prevent="savePayrollCutOff()">
        <div class="row justify-content-center align-items-center mb-14">
            <div class="col-md-3">
                <label for="name" class="required form-label">Periode awal Absen</label>
                <input type="number" name="attendance_period_start" class="form-control form-control-solid"
                       placeholder="Periode Awal Absensi"
                       :value="payrollCutOff?.attendance_period_start">
            </div>
            <div class="col-md-3">
                <label for="name" class="required form-label">Periode akhir absen</label>
                <input type="number" name="attendance_period_end" class="form-control form-control-solid"
                       placeholder="Periode Akhir Absensi"
                       :value="payrollCutOff?.attendance_period_end">
            </div>
        </div>
        <div class="row justify-content-center align-items-center">
            <div class="col-md-3">
                <label for="name" class="required form-label">Periode awal payroll</label>
                <input type="number" name="payroll_period_start" class="form-control form-control-solid"
                       placeholder="Periode Awal Gaji"
                       :value="payrollCutOff?.payroll_period_start">
            </div>
            <div class="col-md-3">
                <label for="name" class="required form-label">Periode akhir payroll</label>
                <input type="number" name="payroll_period_end" class="form-control form-control-solid"
                       placeholder="Periode Akhir Gaji"
                       :value="payrollCutOff?.payroll_period_end">
            </div>
        </div>
        <div class="text-end">
            <button class="btn btn-primary btn-sm" :disabled="buttonLoading"
                    x-text="buttonLoading ? 'Loading...' : 'Simpan'"></button>
        </div>
    </form>
</div>
