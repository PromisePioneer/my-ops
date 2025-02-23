@extends('layouts.template')
@section('page-title', 'Ubah Form Penawaran')
@section('content')

    @push('styles')
        <style>
            .ck-editor__editable_inline {
                min-height: 100px;
            }

            .modal-open .select2-container--bootstrap5 .select2-dropdown {
                z-index: 1020 !important;
            }
        </style>
    @endpush
    <div class="d-flex flex-column flex-lg-row" x-data="generateOfferingLetter">
        @include('pages.general-master-data.contacts.form')
        @include('pages.general-master-data.unit-types.form')
        @include('pages.general-master-data.skl.form')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-5">
                <form id="form" @submit.prevent="save()">
                    <div class="card-body p-12">
                        <div class="row gx-10 mb-5">
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                    Tanggal
                                </label>
                                <div class="mb-5">
                                    <input type="date" name="date" class="form-control form-control-solid date"
                                           placeholder="Masukkan tanggal" value="{{ $offeringLetter->date }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                    Penanggung Jawab
                                </label>
                                <div class="mb-5">
                                    <select name="pic" id="selectedUser"
                                            class="form-select form-select-solid users-select2">
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
                                        <select name="contact_id" id="selectedContact"
                                                class="form-select form-select-solid contact-select2"
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
                                               placeholder="masukkan lampiran surat penawaran"
                                               value="{{ $offeringLetter->regarding }}">
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
                                                    :class="`form-select form-select-solid service-categories-select2`"
                                                    :name="`data[${index}][service_category_id]`"
                                                    :id="`selectedServices-${index}`"
                                                    x-model="field.service_category_id">
                                                <option></option>
                                            </select>
                                        </td>
                                        <td style='text-align:center; vertical-align:middle' class="w-50">
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.capacity" :name="`data[${index}][capacity]`"
                                                   placeholder="Kapasitas" value="0"/>
                                        </td>
                                        <td style='text-align:center; vertical-align:middle' class="w-20">
                                            <select :class="`form-select form-select-solid unit-types-select2`"
                                                    :name="`data[${index}][unit_type_id]`"
                                                    :id="`selectedUnitType-${index}`"
                                                    x-model="field.unit_type_id">
                                                <option></option>
                                            </select>
                                        </td>
                                        <td style='text-align:center; vertical-align:middle' class="w-50">
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.price" :name="`data[${index}][price]`"
                                                   placeholder="Harga" value="0"/>
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
                                            <select :name="`serviceDescription[${index}][skl_id]`"
                                                    :id="`selectedSKL-${index}`"
                                                    class="form-select form-select-solid skl-select2">
                                                <option></option>
                                            </select>
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
        $(".date").flatpickr();

        function generateOfferingLetter() {
            return {
                id: "{{ $offeringLetter->id }}",
                buttonLoading: false,
                offeringLetterProductService: [],
                offeringLettersServiceDescription: [],
                offeringLetterProducts: [],
                form: document.getElementById('form'),
                contactForm: document.getElementById('contact-form'),
                contactModal: new bootstrap.Modal(document.getElementById('contact-modal')),
                unitTypeForm: document.getElementById('form-unit-type'),
                unitTypeModal: new bootstrap.Modal(document.getElementById('modal-unit-type')),
                sklModal: new bootstrap.Modal(document.getElementById('modal-skl')),
                sklForm: document.getElementById('form-skl'),
                editVal: '',
                async init() {

                    await this.getSelectedContact();
                    await this.getSelectedUser();
                    await this.getUserData();
                    await this.getSKL();
                    await this.getContactData();
                    await this.getSelectedOfferingLetterProductService();
                    await this.getSelectedOfferingLettersServiceDescription();

                },
                async getSelectedOfferingLetterProductService() {
                    const resp = await axios.get(`/income-transactions/offering-letters/get-selected-products/${this.id}`);
                    resp.data.map((resp, index) => {
                        this.offeringLetterProductService.push({
                            service_category_id: resp.service_category_id,
                            capacity: resp.capacity,
                            unit_type_id: resp.unit_type_id,
                            price: resp.price,
                        });
                        this.$nextTick(() => {
                            this.selectedServiceCategories(resp, index);
                            this.selectedUnitTypes(resp, index);
                            this.getServicesCategories();
                            this.getUnitTypeData();
                        });
                    });
                },
                async getSelectedServicesDescription() {
                    const resp = await axios.get(`/income-transactions/offering-letters/get-selected-offering-letters-service-description/${this.id}`);
                    resp.data.map((resp, index) => {
                        this.offeringLettersServiceDescription.push({
                            skl_id: resp.skl_id
                        });
                        this.$nextTick(() => {
                            this.selectedSKL(resp, index);
                        });
                    });
                },
                async selectedUnitTypes(field, index) {
                    const selectedUnitType = $(`#selectedUnitType-${index}`);
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/general-master-data/unit-types/show/${field.unit_type_id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedUnitType.append(option).trigger('change');
                        selectedUnitType.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                        field.unit_type_id = response.id;
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
                        resp.service_category_id = response.id;
                    });
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
                        resp.skl_id = response.id;
                    });
                },
                async getSKL() {
                    $(".skl-select2").select2({
                        placeholder: "Pilih Syarat Ketentuan Layanan",
                        allowClear: true,
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-skl">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/income-transactions/offering-letters/skl/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getUnitTypeData() {
                    $(`.unit-types-select2`).select2({
                        placeholder: "Pilih Satuan.",
                        allowClear: true,
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-unit-type-create"">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/income-transactions/offering-letters/unit-types/data',
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
                async getUserData() {
                    $(".users-select2").select2({
                        placeholder: "Pilih Penanggung jawab.",
                        allowClear: true,
                        ajax: {
                            url: '/income-transactions/offering-letters/users/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getSelectedUser() {
                    const selectedUser = $('#selectedUser');
                    const response = await axios.get(`/income-transactions/offering-letters/users/selected/${this.id}`);
                    const option = new Option(response.data.name, response.data.id, true, true);
                    selectedUser.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response.data}
                    });
                },
                async getSelectedContact() {
                    const selectedContact = $('#selectedContact');
                    const response = await axios.get(`/income-transactions/offering-letters/get-selected-contact/${this.id}`);
                    const option = new Option(response.data.name, response.data.id, true, true);
                    selectedContact.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response.data}
                    });
                },
                async getSelectedOfferingLettersServiceDescription() {
                    const resp = await axios.get(`/income-transactions/offering-letters/get-selected-offering-letters-service-description/${this.id}`);
                    resp.data.map((resp, index) => {
                        this.offeringLettersServiceDescription.push({
                            skl_id: resp.skl_id
                        });
                        this.$nextTick(() => {
                            this.selectedSKL(resp, index);
                        });
                    });
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
                        await axios.post(`/income-transactions/offering-letters/update/${this.id}`, new FormData(this.form))
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
                        this.getUnitTypeData();
                    })
                    this.offeringLetterProductService.push({
                        service_category_id: '',
                        price: '',
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
                calculateTotalAll() {
                    return this.offeringLetterProductService.reduce((total, field) => Number(total) + Number(field.price), 0);
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
                        this.offeringLetterProductService.forEach((resp, index) => {
                            this.selectedServiceCategories(resp, index);
                            this.selectedUnitTypes(resp, index);
                        });
                    }
                },
                removeOfferingLettersServiceDescription(index) {
                    if (this.offeringLettersServiceDescription.length > 1) {
                        this.offeringLettersServiceDescription.splice(index, 1);

                        this.offeringLettersServiceDescription.forEach((resp, index) => {
                            this.$nextTick(() => {
                                this.selectedSKL(resp, index);
                            })
                        })
                    }
                },
                async getServicesCategories(index) {
                    $(`.service-categories-select2`).select2({
                        allowClear: true,
                        placeholder: "Pilih Kategori Layanan",
                        ajax: {
                            url: '/income-transactions/offering-letters/service-categories/data',
                            dataType: "JSON",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true,
                        }
                    });
                },
                async getContactData() {
                    $(".contact-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Calon Pelanggan",
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
