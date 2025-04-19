@extends('layouts.template')
@section('page-title', 'Pengaturan Payroll')
@section('content')
    <div x-data="payrollSetting()">
        @include('pages.payroll.payroll-component.modal.allowances.create')
        @include('pages.payroll.payroll-component.modal.deduction.create')
        @include('pages.payroll.payroll-component.modal.benefit.create')
        @include('pages.payroll.bpjs.modal.edit')
        <div class="card ">
            <div class="card-header card-header-stretch">
                <h3 class="card-title">Pengaturan Payroll</h3>
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#payroll-schedule">
                                Jadwal Payroll
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#payroll-component">Payroll Component</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#cut-off">Cut Off</a>
                            <a class="nav-link" data-bs-toggle="tab" href="#bpjs">BPJS</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="payroll-schedule" role="tabpanel">
                        <form id="update-payroll-schedule" @submit.prevent="payrollScheduleSave()">
                            <div class="mb-4">Penggajian akan dijadwalkan pada tanggal ini</div>
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <input type="text" class="form-control form-control-solid" name="date"
                                           :value="payrollScheduleData?.date">
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                                            x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="payroll-component" role="tabpanel">
                        @include('pages.payroll.payroll-component.index')
                    </div>

                    <div class="tab-pane fade" id="bpjs" role="tabpanel">
                        @include('pages.payroll.bpjs.index')
                    </div>
                    <div class="tab-pane fade" id="cut-off" role="tabpanel">
                        @include('pages.payroll.cut-off-setting.index')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function payrollSetting() {
            return {
                payrollScheduleData: null,
                isLoading: true,
                buttonLoading: false,
                startIndex: null,
                allowances: [],
                bpjsKet: [],
                bpjsKetEditVal: {},
                search: '',
                attendancesPeriod: '',
                payrollCutOff: {},
                formPayrollSchedule: document.getElementById('update-payroll-schedule'),
                formBpjsKet: document.getElementById('form-bpjs-ket-edit'),
                modalBpjsKet: new bootstrap.Modal(document.getElementById('modal-bpjs-ket-edit')),
                payrollCutOffForm: document.getElementById('payroll-cutoff-form'),
                async init() {
                    await this.getPayrollScheduleData();
                    await this.getRolesData();
                    await this.getBPJSKetData();
                    await this.getPayrollCutOff();
                },
                async savePayrollCutOff() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/payroll/setting/cut-off/save', new FormData(this.payrollCutOffForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        await this.init();
                    } catch (error) {
                        const respError = error?.response?.data?.errors;
                        Object.keys(respError)?.map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getPayrollCutOff() {
                    const resp = await axios.get('/payroll/setting/cut-off/data');
                    this.payrollCutOff = resp.data;
                },
                async getAttendancesCutOff() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/payroll/setting/payroll-schedule', new FormData(this.payrollCutOffForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        await this.init();
                    } catch (error) {
                        const respError = error?.response?.data?.errors;
                        Object.keys(respError)?.map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async payrollScheduleSave() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/payroll/setting/payroll-schedule', new FormData(this.formPayrollSchedule))
                        await showAlert('success', 'Data berhasil disimpan')
                        await this.init();
                    } catch (error) {
                        const respError = error?.response?.data?.errors;
                        Object.keys(respError)?.map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async editBpjsKetRate(id) {
                    const resp = await axios.get(`/payroll/setting/bpjs-ket/${id}`);
                    this.bpjsKetEditVal = resp.data;
                },
                async saveBPJSKet(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/payroll/setting/bpjs-ket/${id}`, new FormData(this.formBpjsKet))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formBpjsKet.reset();
                        this.modalBpjsKet.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error?.response?.data?.errors;
                        Object.keys(respError)?.map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getBPJSKetData() {
                    this.isLoading = true
                    try {
                        const resp = await axios.get('/payroll/setting/bpjs-ket/data');
                        this.bpjsKet = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getPayrollScheduleData() {
                    try {
                        const resp = await axios.get('/payroll/setting/payroll-schedule/data');
                        this.payrollScheduleData = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getRolesData() {
                    $(".roles-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih',
                        ajax: {
                            url: '/payroll/setting/roles/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    }).on("select2:select2", (e) => {
                        const data = e.param.data.text;
                        console.log(data);
                    });
                },
            }
        }
    </script>
@endpush
