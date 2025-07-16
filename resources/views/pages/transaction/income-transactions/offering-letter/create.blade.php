@extends('layouts.template')
@section('page-title', 'Form Penawaran')
@section('content')

    <style>
        .modal-open .select2-container--bootstrap5 .select2-dropdown {
            z-index: 1020 !important;
        }
    </style>
    <div class="d-flex flex-column flex-lg-row" x-data="generateOfferingLetter">
        @include('pages.master.common.contacts.form')
        @include('pages.master.common.unit-types.form')
        @include('pages.master.common.skl.form')
        @include('pages.master.common.services-categories.form')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="save()">
                    <div class="card-body p-12">
                        <div class="row gx-10 mb-5">
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                    Tanggal
                                </label>
                                <div class="mb-5">
                                    <input type="date" name="date" class="form-control form-control-solid date"
                                           placeholder="Masukkan tanggal">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                    PIC
                                </label>
                                <div class="mb-5">
                                    <select name="pic" class="form-select form-select-solid users-select2">
                                        <option></option>
                                    </select>
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
                                        <select name="contact_id" class="form-select form-select-solid contact-select2">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Perihal
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" name="regarding" class="form-control form-control-solid"
                                               placeholder="masukkan perihal surat penawaran">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mb-20">
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                <thead>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th class="min-w-300px w-475px required">Jenis Layanan</th>
                                    <th class="min-w-100px w-100px required">Kapasitas</th>
                                    <th class="min-w-100px w-100px required">Satuan</th>
                                    <th class="min-w-100px w-100px required">Harga</th>
                                    <th class="min-w-75px w-75px text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(field,index) in offeringLetterProductService" :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                            <select
                                                    :class="`form-select form-select-solid service-categories-select2-${index}`"
                                                    :name="`data[${index}][service_category_id]`"
                                                    x-model="field.service_category_id"
                                                    :id="`selectedServices-${index}`">
                                                <option></option>
                                            </select>
                                        </td>
                                        <td style='text-align:center; vertical-align:middle' class="w-50">
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.capacity" :name="`data[${index}][capacity]`"
                                                   placeholder="Kapasitas" value="0" @change="calculateTotal(index)"/>
                                        </td>
                                        <td style='text-align:center; vertical-align:middle' class="w-20">
                                            <select :class="`form-select form-select-solid unit-types-select2-${index}`"
                                                    :name="`data[${index}][unit_type_id]`"
                                                    :id="`selectedUnitType-${index}`"
                                                    x-model="field.unit_type_id">
                                                <option></option>
                                            </select>
                                        </td>

                                        <td style='text-align:center; vertical-align:middle' class="w-50">
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.price" :name="`data[${index}][price]`"
                                                   placeholder="Harga" value="0" @change="calculateTotal(index)"/>
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
                                            <div class="mb-5">
                                                <select :name="`serviceDescription[${index}][skl_id]`"
                                                        class="form-select form-select-solid skl-select2">
                                                    <option></option>
                                                </select>
                                            </div>
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
@endsection
@push('script')
    <script>
        $(".date").flatpickr();

        function generateOfferingLetter() {
            return {
                modalServiceCategories: new bootstrap.Modal(document.getElementById('modal-service-category')),
                formServiceCategories: document.getElementById('form-service-category'),
                buttonLoading: false,
                editVal: '',
                offeringLetterProductService: [{
                    service_category_id: '',
                    unit_type_id: '',
                    capacity: '',
                    price: '',
                    total_price: '',
                }],
                offeringLettersServiceDescription: [{
                    skl_id: '',
                }],
                form: document.getElementById('form'),
                contactForm: document.getElementById('contact-form'),
                contactModal: new bootstrap.Modal(document.getElementById('contact-modal')),
                unitTypeForm: document.getElementById('form-unit-type'),
                unitTypeModal: new bootstrap.Modal(document.getElementById('modal-unit-type')),
                sklModal: new bootstrap.Modal(document.getElementById('modal-skl')),
                sklForm: document.getElementById('form-skl'),
                async init() {
                    await this.getContactData();
                    await this.getUserData();
                    await this.getSKL();
                    this.offeringLetterProductService.forEach((field, index) => {
                        this.getServiceCategories(field, index);
                        this.getUnitTypes(field, index);
                    });
                },
                async saveServiceCategories() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/common/service-categories/', new FormData(this.formServiceCategories))
                        await showAlert('success', 'Data berhasil disimpan');
                        await this.init();
                        await this.formServiceCategories.reset();
                        await this.modalServiceCategories.hide();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false
                    }
                },
                async saveContact() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/common/contact`, new FormData(this.contactForm))
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
                        this.offeringLetterProductService.forEach((field, index) => {
                            this.getServiceCategories(field, index);
                            this.getUnitTypes(field, index);
                        })


                    })
                    this.offeringLetterProductService.push({
                        service_category_id: '',
                        capacity: '',
                        unit_type_id: '',
                        price: '',
                        total_price: '',
                    });
                },
                async addOfferingLettersServiceDescription() {
                    this.$nextTick(() => {
                        this.getSKL();
                    })

                    this.offeringLettersServiceDescription.push({
                        skl_id: ''
                    });
                },
                calculateTotal(index) {
                    this.offeringLetterProductService[index].total_price = this.offeringLetterProductService[index].price;
                },
                calculateTotalAll() {
                    return this.offeringLetterProductService.reduce((total, field) => {
                        return Number(total) + Number(field.price)
                    }, 0);
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                },
                async removeOfferingLetterProductService(index) {
                    if (this.offeringLetterProductService.length > 1) {
                        this.offeringLetterProductService.splice(index, 1);
                        this.offeringLetterProductService.forEach((field, index) => {
                            this.selectedServiceCategories(field, index);
                            this.selectedUnitType(field, index);
                        });
                    }
                },
                removeOfferingLettersServiceDescription(index) {
                    if (this.offeringLettersServiceDescription.length > 1) {
                        this.offeringLettersServiceDescription.splice(index, 1);
                    }
                },
                async getServiceCategories(field, index) {
                    $(`.service-categories-select2-${index}`).select2({
                        placeholder: "Pilih kategori layanan",
                        allowClear: true,
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-service-category">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/select2/service-categories-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', function (e) {
                        field.service_category_id = e.params.data.id;
                    });
                },
                async selectedServiceCategories(field, index) {
                    if (field.service_category_id === '') return;
                    const selectedServices = $(`#selectedServices-${index}`);
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-service-category/${field.service_category_id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedServices.append(option).trigger('change');
                        selectedServices.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    });
                },
                async selectedUnitType(field, index) {
                    if (field.unit_type_id === '') return;
                    const selectedUnitType = $(`#selectedUnitType-${index}`);
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/master/common/unit-types/show/${field.unit_type_id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedUnitType.append(option).trigger('change');
                        selectedUnitType.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    });
                },
                async getSKL() {
                    $(".skl-select2").select2({
                        placeholder: "Pilih Syarat Ketentuan Layanan",
                        allowClear: true,
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-skl"">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/select2/skl-data',
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
                        placeholder: "Pilih Kontak",
                        allowClear: true,
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#contact-modal">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/select2/contacts-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getUnitTypes(field, index) {
                    $(`.unit-types-select2-${index}`).select2({
                        placeholder: "Pilih Satuan.",
                        allowClear: true,
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-unit-type">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/select2/unit-types-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', function (e) {
                        field.unit_type_id = e.params.data.id;
                    });
                },
                async saveUnitTypes() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/common/unit-types/', new FormData(this.unitTypeForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.unitTypeForm.reset();
                        this.unitTypeModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async saveSKL() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/common/skl', new FormData(this.sklForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.sklForm.reset();
                        this.sklModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async saveServiceCategory() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/common/service-categories/', new FormData(this.formServiceCategories))
                        await showAlert('success', 'Data berhasil disimpan');
                        await this.init();
                        await this.formServiceCategories.reset();
                        await this.modalServiceCategories.hide();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false
                    }
                },
                async getUserData() {
                    $(".users-select2").select2({
                        placeholder: "Pilih PIC.",
                        allowClear: true,
                        ajax: {
                            url: '/select2/users-data',
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
