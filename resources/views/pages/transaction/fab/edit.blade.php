@extends('layouts.template')
@section('page-title', 'FAB Manager')
@section('content')

    <div class="d-flex flex-column flex-lg-row" x-data="generateFAB">
        @include('pages.master.contact.modal.create')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-10">
                <form id="form" @submit.prevent="generateFAB()">
                    <div class="card-body p-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice date">
                                    <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Tanggal:</div>
                                    <div class="position-relative d-flex align-items-center w-150px">
                                        <input type="date" class="form-control form-control-white fw-bolder pe-5"
                                               placeholder="Pilih Tanggal" name="date" id="date"
                                               value="{{ $fab->date }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-end fw-row text-nowrap order-1 order-xxl-2 me-4 "
                                     data-bs-toggle="tooltip" data-bs-trigger="hover" title="Masukkan Nomor Penawaran">
                                    <span class="fs-2x fw-bolder text-gray-800">NO #</span>
                                    <input type="text" name="fab_number"
                                           class="form-control form-control-flush fw-bolder text-muted fs-3 w-125px"
                                           placeholder="FAB" value="{{ $fab->fab_number }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Status Berlangganan
                                    </label>
                                    <div class="mb-5">
                                        <select name="subscription_status" class="form-select form-select-solid"
                                                data-placeholder="Select an option">
                                            <option
                                                value="baru" {{ $fab->subscription_status === 'baru' ? 'selected' : '' }}>
                                                Baru
                                            </option>
                                            <option
                                                value="perubahan jenis layanan" {{ $fab->subscription_status === 'perubahan jenis layanan' ? 'selected' : ""}}>
                                                Perubahan Jenis Layanan
                                            </option>
                                            <option
                                                value="daftar ulang" {{ $fab->subscription_status === 'daftar ulang' ? 'selected' : '' }}>
                                                Daftar Ulang
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Pelanggan
                                    </label>
                                    <div class="mb-5">
                                        <select name="contact_id" id="selectedContact"
                                                class="form-select form-select-solid contactSelect2"
                                                data-placeholder="Select an option">
                                            <option>Pilih Pelanggan</option>
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
                                    <th class="min-w-300px w-475px required">Deskripsi</th>
                                    <th class="min-w-100px w-100px required">Harga</th>
                                    <th class="min-w-150px w-150px required">Jumlah</th>
                                    <th class="min-w-100px w-150px text-end required">Total</th>
                                    <th class="min-w-75px w-75px text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-if="fields.length === 0">
                                    <tr>
                                        <td colspan="5" class="text-center">Data Kosong</td>
                                    </tr>
                                </template>
                                <template x-for="(field,index) in fields " :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                            <select :id="`selectedServices-${index}`"
                                                    :class="`form-select form-select-solid serviceSelect2`"
                                                    :name="`data[${index}][service_category_id]`"
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
                            <label class="form-label fs-6 fw-bolder text-gray-700 required">Alamat Penagihan</label>
                            <textarea name="billing_address" class="form-control form-control-solid" id="notes" rows="3"
                                      placeholder="Thanks for your business">{{ $fab->billing_address }}</textarea>
                        </div>
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-bolder text-gray-700 required">Alamat Pemasangan</label>
                            <textarea name="installation_address" class="form-control form-control-solid" id="notes"
                                      rows="3"
                                      placeholder="Thanks for your business">{{ $fab->installation_address }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 required">Kode Pos</label>
                                <input type="text" name="zip_code" class="form-control form-control-solid"
                                       placeholder="Masukkan kode pos" value="{{ $fab->zip_code }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 required">Soft File</label>
                                <input type="file" name="file" class="form-control form-control-solid"
                                       accept="application/pdf"/>
                            </div>
                        </div>
                    </div>
                    <div class="float-end">
                        <a href="{{ url('income-transactions/fab/detail/' . $fab->id) }}"
                           class="btn btn-sm btn-light">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-primary" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading...' : 'Generate Surat Penawaran'">
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
        const form = document.getElementById('form');

        function generateFAB() {
            return {
                buttonLoading: false,
                contactId: "{{ $fab->contact_id }}",
                id: "{{ $fab->id }}",
                fields: [{
                    service_category_id: '',
                    qty: '',
                    unit_price: '',
                    total_price: '',
                }],
                async init() {
                    await this.getContactData();
                    await this.getServicesCategories();
                    await this.selectedContact();
                    await this.selectedServicesCategories();
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
                removeField(index) {
                    if (this.fields.length > 1) {
                        this.fields.splice(index, 1);
                    }
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                },
                calculateTotal(index) {
                    const quantity = this.fields[index].qty;
                    const unitPrice = this.fields[index].unit_price;
                    this.fields[index].total_price = (quantity * unitPrice).toFixed(2);
                },
                calculateTotalAll() {
                    return this.fields.reduce((total, field) => total + (field.qty * field.unit_price), 0);
                },
                async generateFAB() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/income-transactions/fab/update/${this.id}`, new FormData(form))
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
                    this.fields.forEach((field) => {
                        this.$nextTick(() => {
                            $(`.serviceSelect2`).select2({
                                ajax: {
                                    url: '/income-transactions/fab/services-categories/data',
                                    dataType: "json",
                                    type: "GET",
                                    data: params => ({search: params.term}),
                                    processResults: data => ({results: data}),
                                    cache: true
                                }
                            }).on('change', function () {
                                const serviceCategories = $(this).select2('data');
                                serviceCategories.forEach((el) => {
                                    field.service_category_id = el.id;
                                    field.unit_price = el.price;
                                })
                            });
                        })
                    });
                },
                async getContactData() {
                    $(".contactSelect2").select2({
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
                    });
                },
                async selectedContact() {
                    const selectedBranch = $('#selectedContact');
                    const response = await axios.get(`/income-transactions/fab/get-selected-contact/${this.id}`);
                    const option = new Option(response.data.name, response.data.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response.data}
                    });
                },
                async selectedServicesCategories() {
                    const selectedServicesCategories = await axios.get(`/income-transactions/fab/get-selected-services/${this.id}`);
                    this.fields = selectedServicesCategories.data;
                    try {
                        this.$nextTick(() => {
                            this.fields.forEach((field, index) => {
                                const selectedServices = $(`#selectedServices-${index}`);
                                $.ajax({
                                    type: 'GET',
                                    dataType: "JSON",
                                    url: `/master/service-categories/show/${field.service_category_id}`,
                                }).then(function (response) {
                                    const option = new Option(response.name, response.id, true, true);
                                    selectedServices.append(option).trigger('change');
                                    selectedServices.trigger({
                                        type: 'select2:select',
                                        params: {results: response}
                                    });
                                    field.service_category_id = response.id;
                                });
                            });
                        })
                    } catch (e) {
                        console.log(e)
                    }
                },
            }
        }
    </script>
@endpush
