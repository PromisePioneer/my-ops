@php use Carbon\Carbon; @endphp
@extends('layouts.template')
@section('page-title', 'Generate Payroll')
@section('content')

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    @endpush


    <div x-data="generatePayroll()">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Generate Payroll</h3>
            </div>
            <form action="" id="generate-payroll" @submit.prevent="saveSetup()">
                <div class="card-body">
                    <div class="row mb-4 justify-content-center">
                        <div class="col-md-6">
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Periode Payroll</span>
                            </label>
                            <select name="period" id="period" class="form-select form-select-solid"
                                    data-control="select2"
                                    data-placeholder="Select an option">
                                <option value="{{  $payrollSchedule->date }}">
                                    {{ $payrollSchedule->date }} {{ Carbon::now()->getTranslatedMonthName() }} {{ Carbon::now()->year }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-sm btn-light-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')

    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script>
        function generatePayroll() {
            return {
                formGenerate: document.getElementById('generate-payroll'),
                async saveSetup() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/payroll/generate', new FormData(this.formGenerate))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreate.reset();
                        this.modalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
            }
        }
    </script>

@endpush