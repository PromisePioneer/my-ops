@extends('layouts.template')
@section('page-title', 'FAB Manager')
@section('content')

    <div class="d-flex flex-column flex-lg-row" x-data="generateFAB">
        @include('pages.general-master-data.contact.modal.create')
        @include('pages.general-master-data.skl.modal.create')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="save()">
                    <div class="card-body p-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice date">
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Tanggal:</div>
                                    <div class="position-relative d-flex align-items-center ms-4">
                                        <input type="date" class="form-control form-control-solid fw-bolder pe-5"
                                               placeholder="Pilih Tanggal" name="date" id="date"
                                               value="{{ $fab->date }}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Pelanggan
                                    </label>
                                    <div class="mb-5">
                                        <select name="contact_id" id="selectedContact"
                                                class="form-select form-select-solid contact-select2"
                                                data-placeholder="Select an option">
                                            <option>Pilih Pelanggan</option>
                                        </select>
                                    </div>
                                </div>
                                <div :class="contactHasOfferingLetter === null ? 'col-lg-6 d-none' : 'col-lg-6'"
                                     x-transition>
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Surat Penawaran
                                    </label>
                                    <div class="mb-5">
                                        <select name="offering_letter_id" id="offering_letter_id"
                                                class="form-select form-select-solid">
                                            <option :value="contactHasOfferingLetter?.id"
                                                    x-text="contactHasOfferingLetter?.offering_number"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-10"></div>
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
                                <template x-for="(field,index) in fabServices" :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                            <select :class="`form-select form-select-solid service-categories-select2`"
                                                    :name="`fabServices[${index}][service_category_id]`"
                                                    :id="`selectedServices-${index}`"
                                                    x-model="field.service_category_id">
                                                <option></option>
                                            </select>
                                        </td>
                                        <td style='text-align:center; vertical-align:middle' class="w-50">
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.capacity" :name="`fabServices[${index}][capacity]`"
                                                   placeholder="Kapasitas" value="0" @change="calculateTotal(index)"/>
                                        </td>
                                        <td style='text-align:center; vertical-align:middle' class="w-20">
                                            <select :class="`form-select form-select-solid unit-types-select2`"
                                                    :name="`fabServices[${index}][unit_type_id]`"
                                                    x-model="field.unit_type_id"
                                                    :id="`selectedUnitType-${index}`">
                                                <option></option>
                                            </select>
                                        </td>

                                        <td style='text-align:center; vertical-align:middle' class="w-50">
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.price" :name="`fabServices[${index}][price]`"
                                                   placeholder="Harga" value="0" @change="calculateTotal(index)"/>
                                        </td>
                                        <td class="text-end" style='text-align:center; vertical-align:middle'>
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                                    @click="removeFABService(index)">
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
                                                @click="addFABService()">Tambah
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
                                <template x-for="(field,index) in skl" :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td style='text-align:center; vertical-align:middle' class="w-50">
                                            <div class="mb-5">
                                                <select :name="`skl[${index}][skl_id]`" :id="`selectedSKL-${index}`"
                                                        class="form-select form-select-solid skl-select2"
                                                        x-model="field.skl_id">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                                    @click="removeFABSkl(index)">
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
                                                @click="addSKL()">Tambah
                                        </button>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                    <div class="float-end">
                        <a href="{{ url('income-transactions/fab/detail/' . $fab->id) }}"
                           class="btn btn-sm btn-light">Cancel</a>
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
        $('#date').flatpickr();

        function generateFAB() {
            return {
                form: document.getElementById('form'),
                contactForm: document.getElementById('contactFormCreate'),
                contactModal: new bootstrap.Modal(document.getElementById('contact-create')),
                sklModal: new bootstrap.Modal(document.getElementById('modal-skl-create')),
                sklForm: document.getElementById('form-skl-create'),
                buttonLoading: false,
                contactId: "{{ $fab->contact_id }}",
                id: "{{ $fab->id }}",
                fabServices: [],
                skl: [],
                contactHasOfferingLetter: null,
                async init() {
                    await this.selectedFABSkl();
                    await this.getContactData();
                    await this.selectedContact();
                    await this.getSelectedFabService();
                    await this.getSKL();
                },
                async getSelectedFabService() {
                    const resp = await axios.get(`/income-transactions/fab/get-selected-services/${this.id}`);
                    resp.data.map(async (resp, index) => {
                        this.fabServices.push({
                            id: resp.id,
                            service_category_id: resp.service_category_id,
                            unit_type_id: resp.unit_type_id,
                            price: resp.price,
                            capacity: resp.capacity
                        });

                        await this.getUnitTypeData();
                        await this.getServicesCategories();
                        await this.selectedServiceCategories(resp, index);
                        await this.selectedUnitTypes(resp, index);
                    });
                },
                async selectedFABSkl() {
                    const resp = await axios.get(`/income-transactions/fab/get-selected-skl/${this.id}`);
                    resp.data.map(async (resp, index) => {
                        this.skl.push({
                            skl_id: resp.skl_id
                        })

                        this.$nextTick(() => {
                            this.selectedSKL(resp, index);
                        })
                    });
                },
                async getUnitTypeData() {
                    $(".unit-types-select2").select2({
                        placeholder: "Pilih Satuan.",
                        allowClear: true,
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-unit-type-create"">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/income-transactions/fab/get-unit-type/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedServiceCategories(resp, index) {
                    const selectedServices = $(`#selectedServices-${index}`);
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/general-master-data/service-categories/show/${resp.service_category_id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedServices.append(option).trigger('change');
                        selectedServices.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    }).catch(() => {
                        const option = new Option("", "", true, true);
                        selectedServices.append(option).trigger('change');
                        selectedServices.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    });
                },
                async selectedSKL(resp, index) {
                    const selectedSKL = $(`#selectedSKL-${index}`);
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/general-master-data/skl/${resp.skl_id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedSKL.append(option).trigger('change');
                        selectedSKL.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    }).catch(() => {
                        const option = new Option("", "", true, true);
                        selectedSKL.append(option).trigger('change');
                        selectedSKL.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    });
                },
                async selectedUnitTypes(resp, index) {
                    const selectedUnitType = $(`#selectedUnitType-${index}`);
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/general-master-data/unit-types/${resp.unit_type_id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedUnitType.append(option).trigger('change');
                        selectedUnitType.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    }).catch(() => {
                        const option = new Option("", "", true, true);
                        selectedServices.append(option).trigger('change');
                        selectedServices.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    });
                },
                async addFABService() {
                    this.$nextTick(() => {
                        this.getUnitTypeData();
                        this.getServicesCategories();
                    })
                    this.fabServices.push({
                        service_category_id: '',
                        capacity: '',
                        unit_price: '',
                        total_price: '',
                    });
                },
                async removeFABService(index) {
                    this.fabServices.splice(index, 1);
                    this.fabServices.forEach((resp, index) => {
                        this.selectedServiceCategories(resp, index);
                        this.selectedUnitTypes(resp, index);
                    });
                },
                async addSKL() {
                    this.$nextTick(() => {
                        this.getSKL();
                    })

                    this.skl.push({
                        skl_id: '',
                    })
                },
                async removeFABSkl(index) {
                    if (this.skl.length > 1) {
                        this.skl.splice(index, 1)
                        this.skl.forEach((resp, index) => {
                            this.selectedSKL(resp, index);
                        })
                    }
                },
                async getSKL() {
                    $(".skl-select2").select2({
                        placeholder: "Pilih Syarat Ketentuan Layanan",
                        allowClear: true,
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-skl-create"">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/income-transactions/fab/get-skl/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                },
                calculateTotal(index) {
                    const quantity = this.fabServices[index].qty;
                    const unitPrice = this.fabServices[index].unit_price;
                    this.fabServices[index].total_price = (quantity * unitPrice).toFixed(2);
                },
                calculateTotalAll() {
                    return this.fabServices.reduce((total, field) => Number(total) + Number(field.price), 0);
                },
                async saveSKL() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/general-master-data/skl', new FormData(this.sklForm))
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
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/income-transactions/fab/update/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '/income-transactions/fab';
                        });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getServicesCategories() {
                    $(`.service-categories-select2`).select2({
                        allowClear: true,
                        placeholder: 'Pilih Kategori Layanan',
                        ajax: {
                            url: '/income-transactions/fab/services-categories/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });


                },
                async getContactData() {
                    const self = this;
                    $(".contact-select2").select2({
                        escapeMarkup: function (markup) {
                            return markup;
                        },
                        language: {
                            noResults: function () {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#contact-create">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/income-transactions/fab/contact/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('change', async function () {
                        const val = $(this).select2('data')[0];
                        const resp = await axios.get(`/income-transactions/fab/offering-letter/${val.id}`);
                        if (Object.keys(resp.data).length >= 1) {
                            self.contactHasOfferingLetter = resp.data;
                        }
                    });
                },
                async selectedContact() {
                    const selectedContact = $('#selectedContact');
                    const response = await axios.get(`/income-transactions/fab/get-selected-contact/${this.id}`);
                    const option = new Option(response.data.name, response.data.id, true, true);
                    selectedContact.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response.data}
                    });
                },
            }
        }
    </script>
@endpush
