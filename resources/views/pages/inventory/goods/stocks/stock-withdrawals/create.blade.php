@extends('layouts.template')
@section('page-title', 'Invoice Manager')
@section('content')
    <style>
        .modal-open .select2-container--bootstrap5 .select2-dropdown {
            z-index: 1020 !important;
        }
    </style>

    <div class="d-flex flex-column flex-lg-row" x-data="generateInvoice">
        @include('pages.master.common.contacts.form')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="generateInvoice()">
                    <div class="card-body p-12">
                        <div class="row gx-10 mb-5">
                            <div class="col-lg-6">
                                <div class="form-group row mb-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Teknisi</label>
                                    <div class="col-lg-11 fv-row">
                                        <select name="user_id[]" id="user_id"
                                                class="form-select form-select-solid users-select2" multiple>
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mb-10">
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Barang Berkode</label>
                            <table class="table fw-bolder text-gray-700"
                                   data-kt-element="items">
                                <thead>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th class="min-w-100px w-200px">Barang</th>
                                    <th class="min-w-75px w-75px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(field,index) in fields " :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                            <select name="" :id="`stock-with-codes-select2-${index}`"
                                                    class="form-select form-select-solid stock-with-codes-select2">
                                                <option></option>
                                            </select>
                                        </td>
                                        <td class="pt-5">
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                                    @click="removeItemWithCode(index)">
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
                                        <button type="button" class="btn btn-link py-1" @click="addItemWithCode()">
                                            Tambah
                                        </button>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="table-responsive mb-20">
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                Barang Tidak Berkode
                            </label>
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                <thead>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th class="min-w-300px w-475px">Barang</th>
                                    <th class="min-w-150px w-150px">Jumlah</th>
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
                editVal: '',
                buttonLoading: false,
                form: document.getElementById('form'),
                contactForm: document.getElementById('contact-form'),
                contactModal: new bootstrap.Modal(document.getElementById('contact-modal')),
                itemWithCodes: [],
                fields: [{
                    code: '',
                }],
                async init() {
                    await this.getAccountData();
                    await this.getContactData();
                    for (const val of this.fields) {
                        const index = this.fields.indexOf(val);
                        await this.getStockWithCodesData(index);
                    }
                },
                async getAccountData() {
                    $(".users-select2").select2({
                        placeholder: "Pilih Teknisi",
                        allowClear: true,
                        ajax: {
                            url: '/select2/users-data',
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
                async getStockWithCodesData(index) {
                    const self = this;
                    $(`#stock-with-codes-select2-${index}`).select2({
                            allowClear: true,
                            placeholder: "Pilih Barang",
                            ajax: {
                                url: '/select2/stock-with-codes-data',
                                dataType: "json",
                                type: "GET",
                                data: (params) => ({
                                    search: params.term,
                                    ids: self.itemWithCodes,
                                }),
                                processResults: (data) => ({results: data}),
                                cache: true
                            }
                        }
                    ).on('select2:select', function (e) {
                        if (self.fields[index].code === '') {
                            self.itemWithCodes = [...self.itemWithCodes, e.params.data.item_catalog_id];
                        } else {
                            self.itemWithCodes[index] = e.params.data.item_catalog_id;
                        }
                        self.fields[index].code = e.params.data.code;
                        console.log(self.itemWithCodes);
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
                addItemWithCode() {
                    this.$nextTick(() => {
                        this.fields.forEach(async (val, index) => {
                            await this.getStockWithCodesData(index);
                        })
                    })
                    this.fields.push({
                        code: '',
                    });
                },
                removeItemWithCode(index) {
                    if (this.fields.length > 1) {
                        // Simply remove the field at this index
                        this.fields.splice(index, 1);


                        console.log(this.fields);

                        // Reinitialize select2 for all remaining fields
                        this.$nextTick(() => {
                            // Need to destroy the previous select2 instance first
                            $('select').select2();
                            $(`#stock-with-codes-select2-${index}`).select2('destroy');
                            this.getStockWithCodesData(index);
                        });
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
