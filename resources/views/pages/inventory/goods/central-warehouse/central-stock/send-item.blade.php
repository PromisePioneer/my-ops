@extends('layouts.template')
@section('page-title', 'Kirim Barang')
@section('content')

    <div x-data="sendItem()">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-light-danger btn-sm mb-6" href="{{ url('inventory/list-of-items/central-warehouse-items') }}">
                    Kembali
                </a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4 justify-content-center">
                            <div class="col-md-6 ">
                                <label class="col-form-label required fw-bold fs-6">Pilih Gudang Barang Asal</label>
                                <select id="find-warehouses"
                                        class="form-select form-select-solid warehouses-select2">
                                </select>
                            </div>
                            <div class="col-md-6 align-self-end mb-1 ms-2">
                                <button type="button" class="btn btn-light-info btn-sm" :disabled="buttonLoading"
                                        @click="getStock()" x-text="buttonLoading ? 'Loading...' : 'Cari Barang'">
                                </button>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6" x-model="placement">
                                <label class="col-form-label required fw-bold fs-6">Dikirim Ke</label>
                                <select name="placement" id="selectedPlacement"
                                        class="form-select form-select-solid user-placement-select2">
                                    <option value="0" selected>Pilih</option>
                                    <option value="Cabang">Cabang</option>
                                    <option value="Gudang">Gudang</option>
                                </select>
                            </div>
                            <div class="col-lg-6" x-show="placement === 'Cabang'" x-transition x-cloak>
                                <label class="col-form-label required fw-bold fs-6">Cabang</label>
                                <select :name="`${placement === 'Cabang' ? 'branch_id' : ''}`"
                                        class="form-select form-select-solid branches-select2">
                                </select>
                            </div>
                            <div class="col-lg-6" x-show="placement === 'Gudang'" x-transition x-cloak>
                                <label class="col-form-label required fw-bold fs-6">Gudang</label>
                                <select :name="`${placement === 'Gudang' ? 'warehouse_id' : ''}`"
                                        class="form-select form-select-solid warehouses-select2">
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="col-form-label required fw-bold fs-6">Barang</label>
                                <input type="text" class="form-control form-control-solid" value="{{ $item->name }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <input type="hidden" :name="`selectedStock[]`" :value="selectedCheckBox">
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed table-bordered fs-6 gy-5"
                                       id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox"
                                                       @click="toggleAllCheckBox()">
                                            </div>
                                        </th>
                                        <th>No. PO</th>
                                        <th>SN</th>
                                        <th>Lokasi</th>
                                    </tr>
                                    </thead>
                                    <template x-if="isLoading">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <div style="text-align: center;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-if="!isLoading && stocks.data?.length === 0 || stocks.data === null">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="stock in stocks.data" :key="stock.id">
                                        <tbody>

                                        <tr class="text-center">
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <input class="form-check-input" type="checkbox" :value="stock.id"
                                                           :id="'checkbox-' + stock.id"/>
                                                </div>
                                            </td>
                                            <td x-text="stock.po.po_number"></td>
                                            <td x-text="stock.sn"></td>
                                            <td x-text="stock.warehouse.name"></td>
                                        </tr>
                                        </tbody>
                                    </template>
                                </table>
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
                    </div>


                </form>
                @include('components.toast')
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        function sendItem() {
            return {
                isLoading: false,
                id: "{{ $item->id }}",
                placement: false,
                buttonLoading: false,
                selectAll: false,
                singleChecked: false,
                stocks: [],
                selectedCheckBox: [],
                form: document.getElementById('form'),
                async init() {
                    await this.getBranchData();
                    await this.getWarehouseData()
                    await this.getStock();
                },
                toggleAllCheckBox() {
                    this.selectAll = !this.selectAll;
                    this.singleChecked = false;
                    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    this.selectedCheckBox = [];
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = this.selectAll;
                        if (this.selectAll) {
                            this.selectedCheckBox.push(checkbox.value);
                        }
                    });
                    this.selectedCheckBox.shift();
                },
                selectCheckBox(event) {
                    const checkboxId = event.target.value;
                    if (event.target.checked) {
                        this.selectedCheckBox.push(checkboxId);
                    } else {
                        const index = this.selectedCheckBox.indexOf(checkboxId);
                        if (index !== -1) {
                            this.selectedCheckBox.splice(index, 1);
                        }
                    }
                },
                async getBranchData() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/inventory/list-of-items/central-warehouse-stocks/branches/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getWarehouseData() {
                    $(".warehouses-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Gudang",
                        ajax: {
                            url: '/inventory/list-of-items/central-warehouse-stocks/warehouses/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getStock() {
                    this.buttonLoading = true;
                    try {
                        const warehouseId = document.getElementById('find-warehouses').value;
                        const resp = await axios.get(`/inventory/list-of-items/central-warehouse-stocks/get-stock-with-sn/${this.id}`, {
                            params: {
                                warehouse_id: warehouseId
                            }
                        });
                        this.stocks = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.buttonLoading = false;
                    }

                },
                async saveSelectedCheckBox() {
                    console.log(this.selectedCheckBox);
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/list-of-items/central-warehouse-stocks/send-item/save/${this.id}`, new FormData(this.form));
                        await this.form.reset();
                        await showAlert('success', 'Data berhasil disimpan');
                    } catch (e) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
