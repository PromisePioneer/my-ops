@extends('layouts.template')
@section('page-title', 'Invoice Manager')
@section('content')
    <style>
        .modal-open .select2-container--bootstrap5 .select2-dropdown {
            z-index: 1020 !important;
        }
    </style>

    <div class="d-flex flex-column flex-lg-row" x-data="generateInvoice">
        @include('pages.general-master-data.contacts.form')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="generateInvoice()">
                    <div class="card-body p-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover">
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Tanggal Jatuh Tempo:</div>
                                    <div class="position-relative d-flex align-items-center ms-4">
                                        <input type="date" class="form-control form-control-solid fw-bolder pe-5"
                                               placeholder="Jatuh Tempo" name="due_date" id="dueDate"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="row gx-10 mb-5">
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Pelanggan</label>
                                <div class="mb-5">
                                    <select name="contact_id" class="form-select form-select-solid contact-select2">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group row mb-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Kategori
                                        Layanan</label>
                                    <div class="col-lg-11 fv-row">
                                        <select name="account_id" class="form-select form-select-solid account-select2">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mb-20">
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                <thead>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th class="min-w-300px w-475px">Deskripsi</th>
                                    <th class="min-w-100px w-100px">Harga</th>
                                    <th class="min-w-150px w-150px">Jumlah</th>
                                    <th class="min-w-100px w-150px text-end">Total</th>
                                    <th class="min-w-75px w-75px text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(field,index) in fields " :key="index">

                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                            <textarea type="text" class="form-control form-control-solid mb-2"
                                                      x-model="field.description" :name="`data[${index}][description]`"
                                                      placeholder="Deskripsi" data-kt-autosize="true"></textarea>
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.qty" :name="`data[${index}][qty]`" placeholder="1"
                                                   value="1" @change="calculateTotal(index)"/>
                                        </td>
                                        <td style='text-align:center; vertical-align:middle'>
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.unit_price" :name="`data[${index}][unit_price]`"
                                                   placeholder="0" value="0" @change="calculateTotal(index)"/>
                                        </td>
                                        <td class="pt-8 text-end text-nowrap"
                                            style='text-align:center; vertical-align:middle'>
                                            <span x-model="field.total_price"
                                                  x-text="formatNumber(field.unit_price * field.qty)">
                                            </span>
                                            <input type="hidden" :name="`data[${index}][total_price]`"
                                                   x-model="Number(field.unit_price * field.qty)">
                                        </td>
                                        <td class="pt-5 text-end" style='text-align:center; vertical-align:middle'>
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
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
                                        <button type="button" class="btn btn-link py-1" @click="add()">Tambah</button>
                                    </th>
                                    <th colspan="2" class="border-bottom border-bottom-dashed ps-0">
                                    </th>
                                    <th colspan="2" class="border-bottom border-bottom-dashed text-end">
                                        <span data-kt-element="sub-total" x-text="formatNumber(calculateTotalAll())">0.00</span>
                                    </th>
                                </tr>
                                <tr class="align-top fw-bolder text-gray-700">
                                    <th></th>
                                    <th colspan="2" class="fs-4 ps-0">Grand Total</th>
                                    <th colspan="2" class="text-end fs-4 text-nowrap">
                                        <span x-text="formatNumber(calculateTotalAll())">0.00</span>
                                        <input type="hidden" name="grand_total" x-model="calculateTotalAll()"/>
                                    </th>

                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <div class="mb-0">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 required">BAA</label>
                                    <input class="form-control form-control-solid" type="file" name="baa_file"
                                           accept="application/pdf"/>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-0">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 required">Kontrak
                                        Kerjasama</label>
                                    <input class="form-control form-control-solid" type="file"
                                           name="cooperative_contract_file" accept="application/pdf"/>
                                </div>
                            </div>
                        </div>
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-bolder text-gray-700">Catatan</label>
                            <textarea name="description" class="form-control form-control-solid" rows="3"
                                      placeholder="Thanks for your business"></textarea>
                        </div>
                    </div>

                    <div class="float-end">
                        <a href="{{ url('/income-transactions/invoice') }}" class="btn btn-sm btn-light">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-primary" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading...' : 'Generate Invoice'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        $("#invoiceDate").flatpickr();
        $("#dueDate").flatpickr();

        function generateInvoice() {
            return {
                buttonLoading: false,
                form: document.getElementById('form'),
                contactForm: document.getElementById('contact-form'),
                contactModal: new bootstrap.Modal(document.getElementById('contact-modal')),
                fields: [{
                    description: '',
                    qty: '',
                    unit_price: '',
                    total_price: '',
                }],
                async init() {
                    await this.getAccountData();
                    await this.getContactData();
                },
                async getAccountData() {
                    $(".account-select2").select2({
                        placeholder: "Pilih Akun",
                        allowClear: true,
                        ajax: {
                            url: '/income-transactions/invoice/account/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getContactData() {
                    $(".contact-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Kontak",
                        escapeMarkup: function (markup) {
                            return markup;
                        },
                        language: {
                            noResults: function () {
                                return `Data Tidak Ditemukan.. <a class="" href=/'#' data-bs-toggle="modal" data-bs-target="#contact-create">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/income-transactions/invoice/contact/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async saveContact() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/general-master-data/contact`, new FormData(this.contactForm))
                        this.contactForm.reset();
                        this.contactModal.hide();
                    } catch (error) {
                        this.buttonLoading = false;
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async generateInvoice() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/income-transactions/invoice/generate-invoice/`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '/income-transactions/invoice';
                        })
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
                calculateTotal(index) {
                    const quantity = this.fields[index].qty;
                    const unitPrice = this.fields[index].unit_price;
                    this.fields[index].total_price = (quantity * unitPrice).toFixed(2);
                },
                calculateTotalAll() {
                    return this.fields.reduce((total, field) => total + (field.qty * field.unit_price), 0);
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });
                    return IDR.format(curr);
                },
            }
        }
    </script>
@endpush
