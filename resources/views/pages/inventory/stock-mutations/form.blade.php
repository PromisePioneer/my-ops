@extends('layouts.template')
@section('page-title', 'Mutasi Barang - Tambah')
@section('content')
    <div x-data="generateStockMutation()">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-light-danger btn-sm mb-6" href="{{ url('/inventory/stock-mutations') }}">
                    <x-icons.back/>
                    Kembali
                </a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body p-12">
                        <div class="row gx-10 mb-10">
                            <div class="col-lg-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Pilih Opsi</label>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10 mb-4">
                                    <input class="form-check-input" type="checkbox"
                                           id="itemWithCodeOption"
                                           name="item_with_code_option" x-model="itemWithCode"/>
                                    <label class="form-check-label" for="itemWithCodeOption">
                                        Barang memiliki kode
                                    </label>
                                </div>
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" id="itemWithoutCodeOption"
                                           name="item_without_code_option" x-model="itemWithoutCode"/>
                                    <label class="form-check-label" for="itemWithoutCodeOption">
                                        Barang tidak memiliki kode
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            @if(empty(Auth::user()->branch_id))
                                <div class="col-lg-6">
                                    <label
                                        class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Pilih Cabang Awal
                                    </label>
                                    <select class="form-select form-select-solid branches-select2"
                                            name="from_branch" id="from_branch">
                                    </select>
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                    Pilih Cabang Tujuan
                                </label>
                                <select class="form-select form-select-solid branches-select2"
                                        name="to_branch" id="to_branch">
                                </select>
                            </div>

                            @if(Auth::user()->branch_id)
                                <div class="col-lg-6">
                                    <label
                                        class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Pilih Cabang
                                    </label>
                                    <select class="form-select form-select-solid main-branches-select2"
                                            name="branch_id" id="branch-id">
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                    Penerima Barang
                                </label>
                                <select class="form-select form-select-solid stocker-by-branch-select2"
                                        name="receiver_id" id="receiver_id">
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive mb-10" x-show="itemWithCode" x-cloak x-transition>
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Barang Berkode</label>
                            <table class="table fw-bolder text-gray-700"
                                   data-kt-element="items">
                                <thead>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th class="min-w-100px w-200px">Barang</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                    <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                        <select :name="`${itemWithCode ? 'itemWithCodeFields[]' : ''}`"
                                                class="form-select form-select-solid stock-with-codes-select2"
                                                multiple>
                                            <option></option>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive mb-20" x-show="itemWithoutCode" x-cloak x-transition>
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
                                <template x-for="(field,index) in itemWithoutCodeFields " :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                            <select x-model="field.stock_id"
                                                    :name="`itemWithoutCodeFields[${index}][stock_id]`"
                                                    :id="`stock-without-codes-select2-${index}`"
                                                    class="form-select form-select-solid stock-without-codes-select2">
                                                <option></option>
                                            </select>
                                        </td>
                                        <td class="ps-0" style='text-align:center; vertical-align:middle'>
                                            <input class="form-control form-control-solid" type="number" min="1"
                                                   x-model="field.qty" :name="`itemWithoutCodeFields[${index}][qty]`"
                                                   placeholder="1"
                                                   value="1"/>
                                        </td>
                                        <td class="pt-5 text-end" style='text-align:center; vertical-align:middle'>
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                                    @click="removeItemWithoutCode(index)">
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
                                        <button type="button" class="btn btn-link py-1" @click="addItemWithoutCode()">
                                            Tambah
                                        </button>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-bolder text-gray-700">Catatan</label>
                            <textarea name="description" class="form-control form-control-solid" rows="3"
                                      placeholder="cth : Penggunaan untuk maintenance"></textarea>
                        </div>
                    </div>


                    <div class="float-end d-flex py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2 btn-sm">Reset</button>
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
    @include('components.toast')
@endsection
@push('script')
    <script>
        function generateStockMutation() {
            return {
                itemWithCode: false,
                itemWithoutCode: false,
                editVal: '',
                buttonLoading: false,
                form: document.getElementById('form'),
                itemWithCodes: [],
                itemWithoutCodeFields: [{
                    stock_id: '',
                    qty: '',
                }],
                toBranchParentId: null,
                async init() {
                    await this.getStockWithCodesData();
                    await this.getBranches();
                    await this.getStockerByBranch();

                    for (const val of this.itemWithoutCodeFields) {
                        const index = this.itemWithoutCodeFields.indexOf(val);
                        await this.getStockWithoutCodesData(index);
                    }
                },
                async getBranches() {
                    const self = this;
                    $(`.branches-select2`).select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/select2/branches-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({
                                search: params.term,
                            }),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', function (e) {
                        self.toBranchParentId = e.params.data.parent_id;
                    })
                },
                async getStockerByBranch() {
                    const self = this;
                    $(`.stocker-by-branch-select2`).select2({
                        allowClear: true,
                        placeholder: "Pilih Stocker",
                        ajax: {
                            url: '/select2/stocker-by-branch-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({
                                search: params.term,
                                branch_id: self.toBranchParentId
                            }),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getStockWithCodesData() {
                    const self = this;
                    $(`.stock-with-codes-select2`).select2({
                        allowClear: true,
                        placeholder: "Pilih Barang",
                        ajax: {
                            url: '/select2/stock-with-codes-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({
                                search: params.term,
                                branch_id: $('#from_branch').val()
                            }),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', function (e) {
                        self.itemWithCodeFields.push({
                            code: e.params.data.code,
                            stock_id: e.params.data.stock_id
                        });
                    });
                },
                removeItemWithoutCode(index) {
                    if (this.itemWithoutCodeFields.length > 1) {
                        this.itemWithoutCodeFields.splice(index, 1);
                        $(`#stock-without-codes-select2-${index}`).val('').trigger('change')
                        this.$nextTick(() => {
                            this.getStockWithoutCodesData(index)
                        })
                    }
                },
                async getStockWithoutCodesData(index) {
                    const self = this;
                    const ids = self.itemWithoutCodeFields.map((item) => {
                        return item.stock_id;
                    }).filter(val => val !== "");
                    $(`#stock-without-codes-select2-${index}`).select2({
                        allowClear: true,
                        placeholder: "Pilih Barang",
                        ajax: {
                            url: '/select2/stock-without-codes-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({
                                search: params.term,
                                ids: ids,
                                branch_id: $('#from_branch').val()
                            }),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    }).on('change', function (e) {
                        self.itemWithoutCodeFields[index].stock_id = $(`#stock-without-codes-select2-${index}`)?.val() ?? ""
                    });
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        let formData = new FormData(this.form);
                        formData.append('item_with_codes', JSON.stringify(this.itemWithCodeFields));
                        await axios.post(`/inventory/stock-mutations/store`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '/inventory/stock-mutations';
                        })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                addItemWithoutCode() {
                    this.$nextTick(() => {
                        this.itemWithoutCodeFields.forEach(async (val, index) => {
                            await this.getStockWithoutCodesData(index);
                        })
                    })


                    this.itemWithoutCodeFields.push({
                        stock_id: '',
                        qty: '',
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
            }
        }
    </script>
@endpush

