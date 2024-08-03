@extends('layouts.template')
@section('page-title', 'Tambah Barang')
@section('content')

    @push('styles')
        <style>
            .modal-open .select2-container--bootstrap5 .select2-dropdown {
                z-index: 1020 !important;
            }
        </style>
    @endpush

    <div x-data="addGoods">
        <div class="d-flex flex-column flex-lg-row">
            @include('pages.inventory.unit-types.modal.create')
            <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                <div class="card">
                    <div class="card-body p-12">
                        <form id="goods-form" @submit.prevent="save()">
                            <a href="{{ url('inventory/goods') }}" class="btn btn-sm btn-info">Kembali</a>
                            <div class="separator separator-dashed my-10"></div>
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Kode / SN
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               name="serial_number">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Nama Barang
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid"
                                               name="name">
                                    </div>
                                </div>
                            </div>

                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Qty
                                    </label>
                                    <div class="mb-5">
                                        <input type="number" class="form-control form-control-solid"
                                               name="qty">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Satuan
                                    </label>
                                    <div class="mb-5">
                                        <select class="form-select form-select-solid unit-type-select2"
                                                name="unit_type_id">
                                            <option value="0">Pilih Satuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Jenis Persediaan
                                    </label>
                                    <select name="account_id" id=""
                                            class="form-select form-select-solid account-select2">
                                        <option value="0" selected>Pilih Jenis Persediaan</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        File
                                    </label>
                                    <div class="mb-5">
                                        <input type="file" class="form-control form-control-solid"
                                               name="file" accept=".jpg,.png,.jpeg">
                                    </div>
                                </div>
                            </div>

                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Tipe Barang
                                    </label>
                                    <select class="form-select form-select-solid" name="type">
                                        <option value="" selected>Pilih Tipe Barang</option>
                                        <option value="aset">Aset</option>
                                        <option value="bukan aset">Bukan Aset</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Harga Satuan
                                    </label>
                                    <div class="mb-5">
                                        <input type="number" class="form-control form-control-solid"
                                               name="unit_price">
                                    </div>
                                </div>
                            </div>


                            <div class="float-end">
                                <a href="{{ url('inventory/goods') }}" class="btn btn-sm btn-light">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary"
                                        :disabled="buttonLoading" x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection

@push('script')
    <script>
        function addGoods() {
            return {
                goodsForm: document.getElementById('goods-form'),
                unitTypeForm: document.getElementById('unit-types-store'),
                modalUnitType: new bootstrap.Modal(document.getElementById('modal-unit-type-create')),
                buttonLoading: false,
                async init() {
                    await this.getUnitTypesData();
                    await this.getRelatedAccounts();
                },
                async saveUnitTypes() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/unit-types`, new FormData(this.unitTypeForm))
                        await showAlert('success', 'Data sukses disimpan.');
                        this.unitTypeForm.reset();
                        this.modalUnitType.hide();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/goods`, new FormData(this.goodsForm))
                        await showAlert('success', 'Data sukses disimpan').then(() => {
                            window.location.href = '/inventory/goods';
                        });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getUnitTypesData() {
                    $(".unit-type-select2").select2({
                        escapeMarkup: function (markup) {
                            return markup;
                        },
                        language: {
                            noResults: function () {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-unit-type-create">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/inventory/goods/unit-types/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                }
                ,
                async getRelatedAccounts() {
                    $(".account-select2").select2({
                        ajax: {
                            url: '/inventory/goods/related-accounts/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
            }
        }
    </script>
@endpush
