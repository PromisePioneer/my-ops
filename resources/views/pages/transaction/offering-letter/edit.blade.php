@extends('layouts.template')
@section('page-title', 'Ubah Form Penawaran')
@section('content')

    @push('styles')
        <script src="{{ asset('assets/plugins/custom/ckeditor/ckeditor5-build-classic/ckeditor.js') }}"></script>
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
        @include('pages.general-master-data.contact.modal.create')

        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-5">
                <form id="form" @submit.prevent="save()">
                    <div class="card-body p-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice date">
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Tanggal:</div>
                                    <div class="position-relative d-flex align-items-center ms-4">
                                        <input type="date" class="form-control form-control-solid fw-bolder pe-5"
                                               placeholder="Select date" name="date" id="date"
                                               value="{{ $offeringLetter->date }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-end fw-row text-nowrap order-1 order-xxl-2 me-4 "
                                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Masukkan Nomor Penawaran">
                                    <span class="fs-2x fw-bolder text-gray-800">NO #</span>
                                    <input type="text" name="offering_number"
                                           class="form-control form-control-flush fw-bolder text-muted fs-3 w-125px"
                                           value="{{ $offeringLetter->offering_number }}"/>
                                </div>
                            </div>
                        </div>


                        <div class="separator separator-dashed my-10"></div>
                        <div class="mb-4">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Calon Klien</label>
                                    <div class="mb-5">
                                        <select name="contact_id" id="selectedContact"
                                                class="form-select form-select-solid contact-select2"
                                                data-placeholder="Select an option">
                                            <option selected>Pilih Calon Klien</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Lampiran</label>
                                    <div class="mb-5">
                                        <input type="text" name="attachment" class="form-control form-control-solid"
                                               placeholder="masukkan lampiran surat penawaran"
                                               value="{{ $offeringLetter->attachment }}">
                                    </div>
                                </div>
                            </div>
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Kata Pengantar</label>
                            <textarea name="foreword" id="foreword"
                                      class="form-control form-control-solid">{{ $offeringLetter->foreword }}</textarea>
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
                                            <select :class="`form-select form-select-solid servicesCategories-${index}`"
                                                    :name="`data[${index}][service_category_id]`"
                                                    :id="`selectedServices-${index}`"
                                                    x-model="field.service_category_id">
                                                <option value="0">Pilih Layanan</option>
                                            </select>
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
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-bolder text-gray-700">Catatan</label>
                            <textarea name="notes" class="form-control form-control-solid" id="notes" rows="3"
                                      placeholder="Thanks for your business">{{ $offeringLetter->notes }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="form-label fs-6 fw-bolder text-gray-700">Agen Marketing</label>
                                <input type="text" name="marketing_agent_name" class="form-control form-control-solid"
                                       placeholder="Masukkan nama agen pemasaran"
                                       value="{{ $offeringLetter->marketing_agent_name }}"/>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fs-6 fw-bolder text-gray-700">CP. Agent Marketing</label>
                                <input type="text" name="marketing_agent_contact"
                                       class="form-control form-control-solid" placeholder="Masukkan no.telepon agen"
                                       value="{{ $offeringLetter->marketing_agent_contact }}"/>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fs-6 fw-bolder text-gray-700">Soft File</label>
                                <input type="file" name="file" class="form-control form-control-solid"
                                       accept="application/pdf"/>
                            </div>
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
                id: "{{ $offeringLetter->id }}",
                servicesCategories: [],
                fields: [],
                form: document.getElementById('form'),
                contactForm: document.getElementById('contactFormCreate'),
                contactModal: new bootstrap.Modal(document.getElementById('contact-create')),
                async init() {
                    await this.getContactData();
                    await this.getServicesCategories();
                    await this.selectedContact();
                    await this.getSelectedServicesCategories();
                },
                async getSelectedServicesCategories() {
                    const selectedServicesCategories = await axios.get(`/income-transactions/offering-letters/get-selected-services/${this.id}`);
                    this.fields = selectedServicesCategories.data;
                    this.servicesCategories.forEach((field, index) => {
                        this.fields.push({
                            service_category_id: field.service_category_id,
                            qty: field.qty,
                            unit_price: field.unit_price,
                            total_price: field.total_price,
                        });
                    })

                    try {
                        this.$nextTick(() => {
                            this.fields.forEach((field, index) => {
                                const selectedServices = $(`#selectedServices-${index}`);
                                $.ajax({
                                    type: 'GET',
                                    dataType: "JSON",
                                    url: `/master/service-categories/show/${field.service_category_id}`,
                                }).then(function (response) {
                                    var option = new Option(response.name, response.id, true, true);
                                    selectedServices.append(option).trigger('change');
                                    selectedServices.trigger({
                                        type: 'select2:select',
                                        params: {
                                            results: response
                                        }
                                    });
                                    field.service_category_id = response.id;
                                });

                            });

                        })
                    } catch (e) {
                        console.log(e)
                    }
                },
                async selectedContact() {
                    const selectedContact = $('#selectedContact');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/income-transactions/offering-letters/get-selected-contact/${this.id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedContact.append(option).trigger('change');

                        selectedContact.trigger({
                            type: 'select2:select',
                            params: {
                                results: response
                            }
                        });
                    });
                },
                async getServicesCategories() {
                    this.fields.forEach((field, index) => {
                        this.$nextTick(() => {
                            $(`.servicesCategories-${index}`).select2({
                                ajax: {
                                    url: '/income-transactions/offering-letters/service-categories/data',
                                    dataType: "json",
                                    type: "GET",
                                    data: function (params) {
                                        return {
                                            search: params.term
                                        };
                                    },
                                    processResults: function (data) {
                                        return {
                                            results: data
                                        };
                                    },
                                    cache: true
                                }
                            }).on('change', function (e) {
                                const selectedProduct = $(this).select2('data');
                                selectedProduct.forEach((el, i) => {
                                    field.service_category_id = el.id;
                                    field.unit_price = el.price;
                                })
                            });
                        })
                    });
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/income-transactions/offering-letters/update/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = `/income-transactions/offering-letters/detail/${this.id}`
                        })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false
                    }
                },
                async saveContact() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/contact`, new FormData(this.contactForm))
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
                async add() {
                    this.$nextTick(() => {
                        this.getServicesCategories();
                    })
                    this.fields.push({
                        service_category_id: '',
                        qty: '',
                        unit_price: '',
                        total_price: '',
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
                removeField(index) {
                    if (this.fields.length > 1) {
                        this.fields.splice(index, 1);
                    }
                },
            }
        }
    </script>
@endpush
