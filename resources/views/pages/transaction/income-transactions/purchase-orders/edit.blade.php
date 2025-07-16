@extends('layouts.template')
@section('page-title', 'Pendapatan - Ubah PO')
@section('content')

    @push('styles')
        <style>
            .modal-open .select2-container--bootstrap5 .select2-dropdown {
                z-index: 1020 !important;
            }
        </style>
    @endpush
    <div>
        <div class="d-flex flex-column flex-lg-row" x-data="generatePO">
            @include('pages.master.common.contacts.form')
            @include('pages.master.common.unit-types.form')
            <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                <div class="card p-10">
                    <form id="form" @submit.prevent="generatePO()">
                        <div class="card-body p-12">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Tanggal
                                    </label>
                                    <div class="mb-5">
                                        <input type="date" name="date" class="form-control form-control-solid date"
                                               placeholder="Masukkan tanggal" value="{{ $purchaseOrder->date }}">
                                    </div>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-10"></div>
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Subject
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid" name="subject"
                                               id="subject" placeholder="Subject" value="{{ $purchaseOrder->subject }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        PIC
                                    </label>
                                    <div class="mb-5">
                                        <select name="pic" id="selected-user"
                                                class="form-select form-select-solid users-select2">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="row gx-10 mb-5">
                                    <div class="col-lg-6">
                                        <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                            Pelanggan
                                        </label>
                                        <div class="mb-5">
                                            <select name="contact_id"
                                                    class="form-select form-select-solid contact-select2"
                                                    id="selected-contact">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div :class="contactHasOfferingLetter === null ? 'col-lg-6 d-none' : 'col-lg-6'"
                                         x-transition x-cloak>
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
                            <div class="table-responsive mb-20">
                                <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                    <thead>
                                    <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                        <th class="min-w-300px w-475px required">Item</th>
                                        <th class="min-w-100px w-100px required">Qty</th>
                                        <th class="min-w-100px w-100px required">Satuan</th>
                                        <th class="min-w-100px w-100px required">Total Harga</th>
                                        <th class="min-w-75px w-75px text-end">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template x-for="(field,index) in poItem" :key="index">
                                        <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                            <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                                <input type="text" class="form-control form-control-solid"
                                                       x-model="field.item" :name="`data[${index}][item]`"
                                                       placeholder="Tambahkan item">
                                            </td>
                                            <td style='text-align:center; vertical-align:middle' class="w-50">
                                                <input class="form-control form-control-solid" type="number" min="1"
                                                       x-model="field.qty" :name="`data[${index}][qty]`"
                                                       placeholder="Kuantitas" value="0"
                                                       @change="calculateTotal(index)"/>
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
                                                       placeholder="Harga" value="0" @change="calculateTotal(index)"/>
                                            </td>
                                            <td class="text-end" style='text-align:center; vertical-align:middle'>
                                                <button type="button"
                                                        class="btn btn-sm btn-icon btn-active-color-primary"
                                                        @click="removePOItem(index)">
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
                                                    @click="addPOItem()">Tambah
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
                        </div>
                        <div class="float-end">
                            <a href="{{ url('/income-transactions/po/') }}"
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
    </div>
@endsection

@push('script')

    <script>
        $('.date').flatpickr();

        function generatePO() {
            return {
                id: "{{ $purchaseOrder->id }}",
                contactId: "{{ $purchaseOrder->contact_id }}",
                pic: "{{ $purchaseOrder->pic }}",
                editVal: "",
                buttonLoading: false,
                contactModal: new bootstrap.Modal(document.getElementById('contact-modal')),
                contactForm: document.getElementById('contact-form'),
                unitTypeModal: new bootstrap.Modal(document.getElementById('modal-unit-type')),
                unitTypeForm: document.getElementById('form-unit-types'),
                form: document.getElementById('form'),
                poItem: [],
                contactHasOfferingLetter: null,
                async init() {
                    this.$nextTick(() => {
                        this.getUnitTypes();
                    });
                    await this.getPicData();
                    await this.getContacts();

                    await this.selectedUser();
                    await this.selectedContact();

                    await this.getSelectedPurchaseOrder();
                },
                async getSelectedPurchaseOrder() {
                    const resp = await axios.get(`/income-transactions/po/purchase-order-item/selected/${this.id}`);
                    resp.data.map((resp, index) => {
                        this.poItem.push({
                            item: resp.item,
                            qty: resp.qty,
                            unit_type_id: resp.unit_type_id,
                            price: resp.price,
                        });
                        this.$nextTick(() => {
                            this.getUnitTypes(resp, index);
                            this.selectedUnitType(resp, index);
                        });
                    });
                },
                async selectedUnitType(resp, index) {
                    const selectedUnitType = $(`#selectedUnitType-${index}`);
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/master/common/unit-types/show/${resp.unit_type_id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedUnitType.append(option).trigger('change');
                        selectedUnitType.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                        resp.unit_type_id = response.id;
                    });
                },
                async generatePO() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/income-transactions/po/update/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan.').then(() => {
                            window.location.href = '/income-transactions/po';
                        })
                    } catch (error) {
                        this.buttonLoading = false;
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async addPOItem() {
                    this.$nextTick(() => {
                        this.getUnitTypes();
                    })
                    this.poItem.push({
                        item: '',
                        unit_type_id: '',
                        qty: '',
                        price: ''
                    });
                },
                removePOItem(index) {
                    if (this.poItem.length > 1) {
                        this.poItem.splice(index, 1);
                        this.poItem.forEach((resp, index) => {
                            this.$nextTick(() => {
                                this.selectedUnitType(resp, index);
                            })
                        })
                    }
                },
                calculateTotal(index) {
                    this.poItem[index].total_price = this.poItem[index].price;
                },
                calculateTotalAll() {
                    return this.poItem.reduce((total, field) => {
                        return Number(total) + Number(field.price)
                    }, 0);
                },
                async getContacts() {
                    const self = this
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
                    }).on('change', async function () {
                        const val = $(".contact-select2").val();
                        const resp = await axios.get(`/income-transactions/po/offering-letter/${val}`);
                        if (Object.keys(resp.data).length >= 1) {
                            self.contactHasOfferingLetter = resp.data;
                        } else {
                            self.contactHasOfferingLetter = null;
                        }
                    });
                },
                async getUnitTypes() {
                    $(".unit-types-select2").select2({
                        placeholder: "Pilih Tipe Unit.",
                        allowClear: true,
                        ajax: {
                            url: '/select2/unit-types-data',
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
                async getPicData() {
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
                async saveContact() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/common/contact`, new FormData(this.contactForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.contactForm.reset();
                        this.contactModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async selectedUser() {
                    const selectedUser = $('#selected-user');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-user/${this.pic}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedUser.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async selectedContact() {
                    const selectedContact = $('#selected-contact');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-contact/${this.contactId}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedContact.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
            }
        }
    </script>
@endpush
