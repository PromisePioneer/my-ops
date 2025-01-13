@extends('layouts.template')
@section('page-title', 'Kirim Barang '. $centralWarehouseItem->item->name . ' Dari ' . $centralWarehouseItem->warehouse->name)
@section('content')
    <div x-data="distributeItem()">
        @if($centralWarehouseItem->item->need_sn === 0)
            <div class="card p-10">
                <div class="card-header border-0 pt-10">
                    <a class="btn btn-info btn-sm mb-6"
                       href="{{ url('inventory/list-of-items/central-warehouse-items/') }}">Kembali</a>
                </div>
                <div class="card-body py-3">
                    <form id="form" @submit.prevent="save()">
                        @csrf
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="col-form-label required fw-bold fs-6">Asal</label>
                                    <input type="text" class="form-control form-control-solid" placeholder=""
                                           value="{{ $centralWarehouseItem->warehouse->name }}"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label required fw-bold fs-6">Nama Barang</label>
                                    <input type="text" class="form-control form-control-solid"
                                           value="{{ $centralWarehouseItem->item->name }}" disabled/>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="col-form-label required fw-bold fs-6">Kuantitas</label>
                                    <input type="text" class="form-control form-control-solid"
                                           value="{{ $centralWarehouseItem->qty }}"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label required fw-bold fs-6">Tanggal Pengiriman</label>
                                    <input type="date" name="date" id="date" class="form-control form-control-solid"/>
                                </div>
                            </div>


                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="col-form-label required fw-bold fs-6">Tujuan</label>
                                    <select class="form-select form-select-solid" id="select-to" x-model="to"
                                            @change="selectTo()">
                                        <option>------ Pilih ------</option>
                                        <option value="Cabang">Cabang</option>
                                        <option value="Gudang">Gudang</option>
                                    </select>
                                </div>
                                <template x-if="to === 'Gudang'">
                                    <div class="col-md-6">
                                        <label class="col-form-label required fw-bold fs-6">Tujuan Barang</label>
                                        <select name="test" id="test"
                                                class="form-select form-select-solid warehouses-select2">
                                            <option></option>
                                        </select>
                                    </div>
                                </template>
                                <template x-if="to === 'Cabang'">
                                    <div class="col-md-6">
                                        <label class="col-form-label required fw-bold fs-6">Tujuan Barang</label>
                                        <select name="" id="" class="form-select form-select-solid branches-select2">
                                            <option></option>
                                        </select>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="separator py-2"></div>

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
        @else
            <h1>h3h3h33h</h1>
        @endif

    </div>
@endsection

@push('script')
    <script>
        function distributeItem() {
            return {
                to: null,
                buttonLoading: false,
                async init() {
                    await this.getBranchData();
                    await this.getWarehouseData();
                },
                async selectTo() {
                    if (await this.to === 'Gudang') {
                        await this.getWarehouseData();
                    } else {
                        await this.getBranchData();
                    }
                },
                async getWarehouseData() {
                    $(".warehouses-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Gudang",
                        ajax: {
                            url: '/inventory/list-of-items/central-warehouse-items/warehouses/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getBranchData() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/inventory/list-of-items/central-warehouse-items/branches/data',
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
