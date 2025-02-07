@extends('layouts.template')
@section('page-title', 'BAST Manager')
@section('content')
    @push('styles')
        <style>
            .modal-open .select2-container--bootstrap5 .select2-dropdown {
                z-index: 1020 !important;
            }
        </style>
    @endpush
    <div class="d-flex flex-column flex-lg-row" x-data="generateBAST()">
        @include('pages.general-master-data.contact.form')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card">
                <div class="card-body p-12">
                    <form id="form" @submit.prevent="generateBAST()">
                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        BAA
                                    </label>
                                    <div class="mb-5">
                                        <select name="baa_id" id="baa_id"
                                                class="form-select form-select-solid baa-select2">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Tanggal
                                    </label>
                                    <div class="mb-5">
                                        <input type="date" class="form-control form-control-solid date"
                                               name="date" placeholder="Pilih Tanggal">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-10">
                                <div class="mb-0">
                                    <div>
                                        <label class="form-label fs-6 fw-bolder text-gray-700 required">
                                            Alamat Invoice
                                        </label>
                                        <textarea name="invoice_address" class="form-control form-control-solid"
                                                  data-kt-autosize="true" placeholder="Alamat Invoice"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="float-end">
                            <a href="{{ url('income-transactions/bast') }}" class="btn btn-sm btn-light">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-light-primary"
                                    :disabled="buttonLoading" x-text="buttonLoading ? 'Loading...' : 'Generate BAST'">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        $(".date").flatpickr();

        function generateBAST() {
            return {
                buttonLoading: false,
                fields: [{
                    description: '',
                    qty: '',
                    unit_price: '',
                    total_price: '',
                }],
                form: document.getElementById('form'),
                contactForm: document.getElementById('contact-form'),
                contactModal: new bootstrap.Modal(document.getElementById('contact-modal')),
                async init() {
                    await this.getBAAData();
                },
                async getBAAData() {
                    $(".baa-select2").select2({
                        placeholder: "Pilih BAA",
                        allowClear: true,
                        ajax: {
                            url: '/income-transactions/bast/baa/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        },
                    });
                },
                async generateBAST() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/income-transactions/bast/`, new FormData(this.form))
                        await showAlert('success', 'Data sukses disimpan')
                            .then(() => window.location.href = '/income-transactions/bast/');
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
            };
        }
    </script>
@endpush
