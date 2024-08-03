@extends('layouts.template')
@section('page-title', 'Tambah Gaji Karyawan')
@section('content')
    <div x-data="generatePayroll()">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6" href="{{ url('manage-users/payroll/') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Pilih Karyawan</label>
                                <select name="user_id" class="form-select form-select-solid users-select2">
                                    <option value="0">Pilih Karyawan</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Jatuh Tempo Gaji</label>
                                <input type="date" name="salary_date"
                                       class="form-control form-control-lg form-control-solid"/>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Periode Awal</label>
                                <input type="date" name="period_start"
                                       class="form-control form-control-lg form-control-solid"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">
                                    Periode Akhir</label>
                                <input type="date" name="period_end"
                                       class="form-control form-control-lg form-control-solid"/>
                            </div>
                        </div>

                        <div class="separator separator-dashed my-10"></div>
                        <div class="d-flex justify-content-center align-items-center mb-12">
                            <h1>
                                <u>Tunjangan</u>
                            </h1>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Jabatan</label>
                                <input type="number" name="positional_allowance"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Tunjangan Jabatan"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Makan</label>
                                <input type="number" name="meal_allowance"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Tunjangan Makan"/>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Transportasi</label>
                                <input type="number" name="transportation_allowance"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Tunjangan Transportasi"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6"> Lembur</label>
                                <input type="number" name="overtime_allowance"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Tunjangan Lembur"/>
                            </div>
                        </div>


                        <div class="separator separator-dashed my-10"></div>
                        <div class="d-flex justify-content-center align-items-center mb-12">
                            <h1>
                                <u>Bonus</u>
                            </h1>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Project</label>
                                <input type="number" name="positional_allowance"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Bonus Project"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Sales</label>
                                <input type="number" name="sales_bonus"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Bonus Sales"/>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Lainnya</label>
                                <input type="number" name="other_bonus"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Bonus Lainnya"/>
                            </div>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-10"></div>
                    <div class="d-flex justify-content-center align-items-center mb-12">
                        <h1>
                            <u>BPJS</u>
                        </h1>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label class="col-form-label required fw-bold fs-6">BPJS Tek</label>
                            <input type="number" name="bpjs_tek_dues"
                                   class="form-control form-control-lg form-control-solid"
                                   placeholder="BPJS Tenaga Kerja"/>
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label required fw-bold fs-6">BPJS Kes</label>
                            <input type="number" name="bpjs_kes_dues"
                                   class="form-control form-control-lg form-control-solid"
                                   placeholder="BPJS Kesehatan"/>
                        </div>
                    </div>


                    <div class="separator py-2"></div>

                    <div class="float-end d-flex py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2 btn-sm">Reset</button>
                        <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading..' : 'Simpan'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function generatePayroll() {
            return {
                buttonLoading: false,
                form: document.getElementById('form'),
                async init() {
                    await this.getUserData();
                },
                async save() {
                    this.buttonLoading = true
                    try {
                        await axios.post("{{ url('/manage-users/payroll') }}", new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '/manage-users/payroll';
                        });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getUserData() {
                    $(".users-select2").select2({
                        ajax: {
                            url: '/manage-users/payroll/user/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
            }
        }
    </script>
@endpush
