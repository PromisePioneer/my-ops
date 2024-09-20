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
        @include('pages.master.contact.modal.create')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card">
                <div class="card-body p-12">
                    <form id="form" @submit.prevent="generateBAST()">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice date">
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Tgl. Transaksi:</div>
                                    <div class="position-relative d-flex align-items-center w-150px">
                                        <input type="date" class="form-control form-control-white fw-bolder pe-5"
                                               placeholder="Select date" name="date" id="date"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-end fw-row text-nowrap order-1 order-xxl-2 me-4 "
                                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Enter invoice number">
                                    <span class="fs-2x fw-bolder text-gray-800">NO #</span>
                                    <input type="text" name="bast_number"
                                           class="form-control form-control-flush fw-bolder text-muted fs-3 w-125px"
                                           value="" placeholder="Masukkan No disini."/>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Pihak Pertama
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               name="first_party_identity_name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Jabatan Pihak Pertama
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               name="first_party_position">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Pihak Kedua (Klien)
                                    </label>
                                    <div class="mb-5">
                                        <select name="contact_id" class="form-select form-select-solid contactSearch">
                                            <option value="0">Pilih Klien</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-20">
                                <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                    <thead>
                                    <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                        <th class="min-w-100px w-150px">Product</th>
                                        <th class="min-w-200px w-150px">Deskripsi</th>
                                        <th class="min-w-100px w-150px">Qty</th>
                                        <th class="min-w-100px w-150px">SN/ Kode</th>
                                        <th class="min-w-75px w-75px text-end">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template x-for="(field,index) in fields " :key="index">

                                        <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                            <td class="pe-3" style='text-align:center; vertical-align:middle'>
                                                <textarea :name="`data[${index}][product_name]`"
                                                          x-model="field.product_name" id="product_name"
                                                          class="form-control form-control-solid"
                                                          data-kt-autosize="true"
                                                ></textarea>
                                            </td>
                                            <td style='text-align:center; vertical-align:middle'>
                                                <textarea class="form-control form-control-solid"
                                                          x-model="field.description"
                                                          :name="`data[${index}][description]`" data-kt-autosize="true">
                                                </textarea>
                                            </td>
                                            <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                                <input class="form-control form-control-solid" type="number" min="1"
                                                       x-model="field.qty" :name="`data[${index}][qty]`"/>
                                            </td>
                                            <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                                <input class="form-control form-control-solid" type="text"
                                                       x-model="field.serial_number"
                                                       :name="`data[${index}][serial_number]`"/>
                                            </td>

                                            <td class="pt-5 text-end" style='text-align:center; vertical-align:middle'>
                                                <button type="button"
                                                        class="btn btn-sm btn-icon btn-active-color-primary"
                                                        @click="removeField(index)">
                                                    <span class="svg-icon svg-icon-3">
                                                        <i class="bi bi-trash"></i>
                                                    </span>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                    <tfoot>
                                    <tr class="border-top border-top-dashed align-top fs-6 fw-bolder text-gray-700">
                                        <th class="text-primary">
                                            <button type="button" class="btn btn-link py-1" @click="add()">Tambah
                                            </button>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row mb-10">
                                <div class="col-lg-6">
                                    <div class="mb-0">
                                        <label class="form-label fs-6 fw-bolder text-gray-700 required">Lampiran</label>
                                        <input class="form-control form-control-solid" type="file" name="file"
                                               accept="application/pdf"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-lg-6">
                                <div class="mb-0">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 required">Tujuan BAST</label>
                                    <textarea name="objective" class="form-control form-control-solid"
                                              data-kt-autosize="true"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="float-end">
                            <a href="{{ url('income-transactions/bast') }}" class="btn btn-sm btn-light">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary"
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
        $("#date").flatpickr();

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
                contactForm: document.getElementById('contactFormCreate'),
                contactModal: new bootstrap.Modal(document.getElementById('contact-create')),
                async init() {
                    await this.getContactData();
                },
                async getContactData() {
                    $(".contactSearch").select2({
                        escapeMarkup: function (markup) {
                            return markup;
                        },
                        language: {
                            noResults: function () {
                                return `Data Tidak Ditemukan.. <a class="" href=/'#' data-bs-toggle="modal" data-bs-target="#contact-create">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/income-transactions/bast/contact/data',
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
                async saveContact() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/contact`, new FormData(this.contactForm))
                        await showAlert('success', 'Data sukses disimpan');
                        this.contactForm.reset();
                        this.contactModal.hide();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                add() {
                    this.fields.push({
                        description: '',
                        qty: '',
                        unit_price: '',
                        total_price: '',
                    });
                },
                removeField(index) {
                    if (this.fields.length > 1) {
                        this.fields.splice(index, 1);
                    }
                },
            };
        }
    </script>
@endpush
