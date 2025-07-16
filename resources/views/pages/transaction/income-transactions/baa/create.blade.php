@extends('layouts.template')
@section('page-title', 'Tambah Berita Acara Aktivasi')
@section('content')

    <div class="d-flex flex-column flex-lg-row" x-data="generateBAA()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card">
                <div class="card-body p-12">
                    <form id="form" @submit.prevent="generateBAA()">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice date">
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Tanggal:</div>
                                    <div class="position-relative d-flex align-items-center ms-4">
                                        <input type="date" class="form-control form-control-solid fw-bolder pe-5 date"
                                               placeholder="Pilih Tanggal" name="date" id="date"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="mb-4">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Pilih FAB
                                    </label>
                                    <div class="mb-5">
                                        <select name="fab_id" id="fab_id"
                                                class="form-select form-select-solid fab-select2">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="mb-10">
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                Lokasi Pekerjaan
                            </label>
                            <textarea class="form-control form-control-solid" name="work_location"
                                      placeholder="Lokasi Pekerjaan"
                                      data-kt-autosize></textarea>
                        </div>


                        <div class="float-end">
                            <a href="{{ url('income-transactions/baa') }}" class="btn btn-sm btn-light">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-light-primary"
                                    :disabled="buttonLoading" x-text="buttonLoading ? 'Loading...' : 'Generate BAA'">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('.date').flatpickr();

        function generateBAA() {
            return {
                buttonLoading: false,
                form: document.getElementById('form'),
                async init() {
                    await this.getFabData();
                },
                async getFabData() {
                    $('.fab-select2').select2({
                        placeholder: "Pilih FAB",
                        allowClear: true,
                        ajax: {
                            url: '/income-transactions/baa/fab/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    })
                },
                async generateBAA() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/income-transactions/baa/`, new FormData(this.form))
                        await showAlert('success', 'Data sukses disimpan')
                            .then(() => window.location.href = '/income-transactions/baa/');
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
            }
        }
    </script>
@endpush
