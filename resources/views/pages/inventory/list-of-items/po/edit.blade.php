@extends('layouts.template')
@section('page-title', 'PO Barang - Ubah PO')
@section('content')
    <div x-data="generateListOfItem">
        @include('pages.operational-master-data.supplier.modal.create')
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6" href="{{ url('inventory/list-of-items/po') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                @csrf
                <div class="card-body">
                    <form id="form" @submit.prevent="save()">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="col-form-label required fw-bold fs-6">No. Invoice</label>
                                <input type="text" class="form-control form-control-solid" name="invoice_number"
                                       id="invoice_number"
                                       placeholder="No. Invoice" value="{{ $poListOfItem->invoice_number }}">
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">No. PO</label>
                                <input type="text" class="form-control form-control-solid" name="po_number"
                                       id="po_number" placeholder="No. PO" value="{{ $poListOfItem->po_number }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="col-form-label required fw-bold fs-6">Nama Barang</label>
                                <input type="text" class="form-control form-control-solid" name="name" id="name"
                                       placeholder="Nama Barang" value="{{ $poListOfItem->name }}">
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Harga Satuan</label>
                                <input type="number" class="form-control form-control-solid" name="unit_price"
                                       id="unit_price" placeholder="Harga Satuan"
                                       value="{{ $poListOfItem->unit_price }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Ongkos Kirim (Kalau ada)</label>
                                <input type="number" class="form-control form-control-solid" name="shipping_cost"
                                       id="shipping_cost"
                                       placeholder="Ongkos Kirim" value="{{ $poListOfItem->shipping_cost }}">
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Supplier</label>
                                <select name="supplier_id" id="selected-po-list-items"
                                        class="form-select form-select-solid supplier-select2">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Kuantitas</label>
                                <input type="number" class="form-control form-control-solid" name="qty"
                                       id="qty"
                                       placeholder="Kuantitas" value="{{ $poListOfItem->qty }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Resi Surat Jalan</label>
                                <input type="text"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Resi Surat Jalan" name="travel_letter_receipt"
                                       value="{{ $poListOfItem->travel_letter_receipt }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Tanggal Masuk</label>
                                <input type="date" name="date"
                                       class="form-control form-control-lg form-control-solid date"
                                       placeholder="Tanggal Masuk" value="{{ $poListOfItem->date }}"/>
                            </div>
                        </div>

                        <div class="separator py-2"></div>

                        <div class="d-flex mt-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="ppn"
                                       id="ppn" {{ $poListOfItem->ppn ? 'checked' : ''}}/>
                                <label class="form-check-label fw-bold" for="flexCheckChecked">
                                    Tambahkan PPN
                                </label>
                            </div>
                        </div>

                        {{--                        {{ dd($poListOfItem->ppn ? 'checked' : 'hehe') }}--}}

                        <div class="float-end d-flex py-6 px-9">
                            <button type="reset" class="btn btn-light btn-active-light-primary me-2 btn-sm">Reset
                            </button>
                            <button type="submit" class="btn btn-sm btn-light-primary"
                                    :disabled="buttonLoading">
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
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        $('.date').flatpickr();

        function generateListOfItem() {
            return {
                id: "{{ $poListOfItem->id }}",
                buttonLoading: false,
                supplierModal: new bootstrap.Modal(document.getElementById('modal-supplier-create')),
                supplierForm: document.getElementById('form-supplier-create'),
                form: document.getElementById('form'),
                async init() {
                    await this.getSupplierData();
                    await this.selectedSupplier();
                },
                async getSupplierData() {
                    $(".supplier-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Supplier",
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-supplier-create">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/inventory/list-of-items/po/supplier/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async saveSupplier() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/operational-master-data/suppliers', new FormData(this.supplierForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.supplierForm.reset();
                        this.supplierModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async selectedSupplier() {
                    const selectedBranch = $('#selected-po-list-items');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/inventory/list-of-items/po/supplier/selected/${this.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/list-of-items/po/update/${this.id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan')
                        window.location.href = `/inventory/list-of-items/po`;
                    } catch (error) {
                        console.log(error);
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
            }
        }
    </script>
@endpush
