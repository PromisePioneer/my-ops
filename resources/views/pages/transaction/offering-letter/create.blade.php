@extends('layouts.template')
@section('page-title', 'Form Penawaran')
@section('content')

    <style>
        .modal-open .select2-container--bootstrap5 .select2-dropdown {
            z-index: 1020 !important;
        }
    </style>
    <div class="d-flex flex-column flex-lg-row" x-data="generateOfferingLetter">
        @include('pages.general-master-data.contact.modal.create')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="save()">
                    <div class="card-body p-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice date">
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap">
                                        <span class="required">Tanggal:</span>
                                    </div>
                                    <div class="position-relative d-flex align-items-center ms-4">
                                        <input type="date" class="form-control form-control-solid fw-bolder pe-5"
                                               placeholder="Tanggal" name="date" id="date"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Calon Klien
                                    </label>
                                    <div class="mb-5">
                                        <select name="contact_id" class="form-select form-select-solid contact-select2"
                                                data-placeholder="Select an option">
                                            <option selected>Pilih Calon Klien</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Lampiran
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" name="regarding" class="form-control form-control-solid"
                                               placeholder="masukkan lampiran surat penawaran">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mb-20">
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                <thead>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th class="min-w-300px w-475px required">Jenis Layanan</th>
                                    <th class="min-w-100px w-100px required">Harga</th>
                                    <th class="min-w-75px w-75px text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(field,index) in offeringLetterProductService" :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                            <select :class="`form-select form-select-solid service-categories-select2`"
                                                    :name="`data[${index}][service_category_id]`"
                                                    x-model="field.service_category_id">
                                                <option value="0">Pilih Layanan</option>
                                            </select>
                                        </td>
                                        <td style='text-align:center; vertical-align:middle' class="w-50">
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.price" :name="`data[${index}][price]`"
                                                   placeholder="0" value="0" @change="calculateTotal(index)"/>
                                        </td>
                                        <td class="text-end" style='text-align:center; vertical-align:middle'>
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                                    @click="removeOfferingLetterProductService(index)">
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
                                        <button type="button" class="btn btn-link py-1"
                                                @click="addOfferingLetterProductService()">Tambah
                                        </button>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>


                            <table class="float-end">
                                <thead>
                                <tr class="align-top fw-bolder text-gray-700">
                                    <th></th>
                                    <th colspan="2" class="fs-4 ps-0">Grand Total</th>
                                    <th class="w-70"></th>
                                    <th colspan="2" class="text-end fs-4 text-nowrap">
                                        <span x-text="formatNumber(calculateTotalAll())">0.00</span>
                                        <input type="hidden" name="grand_total" x-model="calculateTotalAll()"/>
                                    </th>

                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table-responsive mb-20">
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                <thead>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th>Syarat Ketentuan Layanan</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(field,index) in offeringLettersServiceDescription " :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td style='text-align:center; vertical-align:middle' class="w-50">
                                            <input class="form-control form-control-solid" type="text"
                                                   x-model="field.text" :name="`serviceDescription[${index}][text]`"
                                                   placeholder="0" @change="calculateTotal(index)"/>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                                    @click="removeOfferingLettersServiceDescription(index)">
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
                                        <button type="button" class="btn btn-link py-1"
                                                @click="addOfferingLettersServiceDescription()">Tambah
                                        </button>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="float-end">
                        <a href="{{ url('/income-transactions/offering-letters/') }}"
                           class="btn btn-light-danger btn-sm">
                            <i class="ki-duotone ki-technology-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
                            <i class="ki-duotone ki-click fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                            <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        $("#date").flatpickr();

        function generateOfferingLetter() {
            return {
                buttonLoading: false,
                offeringLetterProductService: [{
                    service_category_id: '',
                    price: '',
                    total_price: '',
                }],
                offeringLettersServiceDescription: [{
                    text: '',
                }],
                form: document.getElementById('form'),
                contactForm: document.getElementById('contactFormCreate'),
                contactModal: new bootstrap.Modal(document.getElementById('contact-create')),
                async init() {
                    await this.getContactData();
                    await this.getServicesCategories();
                },
                async saveContact() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/general-master-data/contact`, new FormData(this.contactForm))
                        await showAlert('success', 'Contact berhasil disimpan');
                        this.contactForm.reset();
                        this.contactModal.hide();
                        this.buttonLoading = false;
                    } catch (error) {
                        this.buttonLoading = false;
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/income-transactions/offering-letters/`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '{{ url('/income-transactions/offering-letters/') }}'
                        })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async addOfferingLetterProductService() {
                    this.$nextTick(() => {
                        this.getServicesCategories();
                    })
                    this.offeringLetterProductService.push({
                        service_category_id: '',
                        price: '',
                        total_price: '',
                    });
                },
                async addOfferingLettersServiceDescription() {
                    this.offeringLettersServiceDescription.push({
                        text: ''
                    });
                },
                calculateTotal(index) {
                    this.offeringLetterProductService[index].total_price = this.offeringLetterProductService[index].price;
                },
                calculateTotalAll() {
                    return this.offeringLetterProductService.reduce((total, field) => total + field.price, 0);
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                },
                removeOfferingLetterProductService(index) {
                    if (this.offeringLetterProductService.length > 1) {
                        this.offeringLetterProductService.splice(index, 1);
                    }
                },
                removeOfferingLettersServiceDescription(index) {
                    if (this.offeringLettersServiceDescription.length > 1) {
                        this.offeringLettersServiceDescription.splice(index, 1);
                    }
                },
                async getServicesCategories() {
                    $(".service-categories-select2").select2({
                        ajax: {
                            url: '/income-transactions/offering-letters/service-categories/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getContactData() {
                    $(".contact-select2").select2({
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#contact-create">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/income-transactions/offering-letters/contact/data',
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
