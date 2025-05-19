@extends('layouts.template')
@section('page-title', 'Mutasi Barang' . '  ' . $itemCollection->name)
@section('content')
    <div x-data="generateStockMutation()">
        @include('pages.inventory.stock-mutations.drawer.item-catalog-details')
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6" href="{{ url('/inventory/stocks') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                        @if(!Auth::user()->branch_id)
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="col-form-label required fw-bold fs-6">Pilih Cabang Awal</label>
                                    <select name="from_branch" id=""
                                            class="form-select form-select-solid main-branches-select2">
                                        <option value=""></option>
                                    </select>
                                </div>
                                @endif
                                <div class="col-lg-6">
                                    <label class="col-form-label required fw-bold fs-6">Pilih Cabang Tujuan</label>
                                    <select name="to_branch" id=""
                                            class="form-select form-select-solid branches-select2">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="item_collection_id[]"
                                   :value="JSON.parse(localStorage.getItem('selectedCheckBox'))">
                    </div>

                    <div class="separator py-2"></div>

                    <div x-show="itemMustHaveCode === 0" x-cloak x-transition>

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
                                <template x-for="(field,index) in itemWithoutCodeFields " :key="index">
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7" style='text-align:center; vertical-align:middle'>
                                            <select x-model="field.stock_id"
                                                    :name="`itemWithoutCodeFields[${index}][stock_id]`"
                                                    :id="`stock-without-codes-select2-${index}`"
                                                    class="form-select form-select-solid stocks-without-code-select2">
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
                    </div>


                    <div class="table-responsive" x-show="currentStocks.length > 0" x-cloak x-transition>
                        <table class="table align-middle table-bordered fs-6 gy-5 mb-0 dataTable no-footer"
                               id="kt_roles_view_table">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox"
                                               @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">
                                    Cabang
                                </th>
                                <th class="min-w-125px">
                                    Nama
                                </th>
                                <th class="min-w-125px">
                                    Kode
                                </th>
                                <th class="min-w-125px">
                                    Kondisi
                                </th>
                            </tr>
                            </thead>
                            <template x-if="!isLoading && currentStocks?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="7">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="stock in currentStocks" :key="stock.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="stock.id"
                                                   :id="stock.id"
                                                   :checked="localStorage.getItem('selectedCheckBox').includes(stock.id)"/>
                                        </div>
                                    </td>
                                    <td x-text="stock.branch_name"></td>
                                    <td x-text="stock.name"></td>
                                    <td x-text="stock.code"></td>
                                    <td x-text="stock.condition"></td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                        <ul class="pagination float-end mb-4 mt-4">
                            <template x-for="pagination in currentStocks.links">
                                <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                    <button class="page-link" @click="paginationEndPoint(pagination.url)"
                                            x-html="pagination.label">
                                    </button>
                                </li>
                            </template>
                        </ul>
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
                buttonLoading: false,
                currentStocks: [],
                selectedCheckBox: [],
                stockOnly: [],
                itemWithoutCodeFields: [],
                itemWithoutCode: false,
                selectAll: false,
                singleChecked: false,
                itemId: "{{ $itemCollection->id }}",
                itemMustHaveCode: "{{ $itemCollection->must_have_code }}",
                branchId: "{{ Auth::user()->branch_id }}",
                form: document.getElementById('form'),
                isLoading: false,
                async init() {
                    await this.getAllBranches();
                    await this.getMainBranches();
                    await this.getItemCollections();

                    for (const val of this.itemWithoutCodeFields) {
                        const index = this.itemWithoutCodeFields.indexOf(val);
                        await this.getStockWithoutCodesData(index);
                    }
                },
                toggleAllCheckBox() {
                    this.selectAll = !this.selectAll;
                    this.singleChecked = false;
                    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    this.selectedCheckBox = [];
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = this.selectAll;
                        if (this.selectAll) {
                            this.selectedCheckBox.push(checkbox.id);
                        }
                    });

                    this.selectedCheckBox.shift();
                    localStorage.setItem('selectedCheckBox', JSON.stringify(this.selectedCheckBox));
                    const selectedItem = JSON.parse(localStorage.getItem('selectedCheckBox'));
                    const resp = selectedItem.filter((value, index, array) => array.indexOf(value) === index)
                        .filter(value => !isNaN(value)).map(value => parseInt(value)).filter(value => !isNaN(value));
                    localStorage.setItem('selectedCheckBox', JSON.stringify(resp));
                },
                selectCheckBox(event) {
                    const checkboxId = event.target.value;
                    if (event.target.checked) {
                        this.selectedCheckBox.push(checkboxId);
                        localStorage.setItem('selectedCheckBox', JSON.stringify(this.selectedCheckBox));
                        const selectedItem = JSON.parse(localStorage.getItem('selectedCheckBox'));
                        const resp = selectedItem.filter((value, index, array) => array.indexOf(value) === index)
                            .filter(value => !isNaN(value)).map(value => parseInt(value)).filter(value => !isNaN(value));
                        localStorage.setItem('selectedCheckBox', JSON.stringify(resp));
                    } else {
                        const index = this.selectedCheckBox.indexOf(checkboxId);
                        if (index !== -1) {
                            this.selectedCheckBox.splice(index, 1);
                            localStorage.setItem('selectedCheckBox', JSON.stringify(this.selectedCheckBox));
                            const selectedItem = JSON.parse(localStorage.getItem('selectedCheckBox'));
                            const resp = selectedItem.filter((value, index, array) => array.indexOf(value) === index)
                                .filter(value => !isNaN(value)).map(value => parseInt(value)).filter(value => !isNaN(value));
                            localStorage.setItem('selectedCheckBox', JSON.stringify(resp));
                        }
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        this.currentStocks = [];
                        this.isLoading = true;
                        try {
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                }
                            });
                            this.currentStocks = resp.data
                        } catch (e) {
                            console.log(e)
                        } finally {
                            this.isLoading = false
                        }
                    }
                },
                async getMainBranches() {
                    const self = this;
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/select2/main-branches-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', async function (e) {
                        self.branchId = e.params.data.id;
                        const itemId = self.itemId;
                        if (self.itemMustHaveCode === 1) {
                            const resp = await axios.get(`/inventory/stocks/${self.branchId}/${itemId}`);
                            self.currentStocks = resp.data;
                        } else {
                            self.itemWithoutCode = true;
                            $('.stocks-without-code-select2').select2({
                                allowClear: true,
                                placeholder: "Pilih Stock",
                                ajax: {
                                    url: `/select2/stock-without-codes-data/`,
                                    dataType: "json",
                                    type: "GET",
                                    data: params => ({search: params.term}),
                                    processResults: data => ({results: data}),
                                    cache: true
                                }
                            });
                        }
                    });
                },
                async getAllBranches() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/select2/branches-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/stock-mutations/store/${this.itemId}`, new FormData(this.form))
                        this.form.reset();
                        await showAlert('success', 'Data berhasil disimpan');
                        window.location.href = '/inventory/stock-mutations';
                        localStorage.removeItem('selectedCheckBox');
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                addItemWithoutCode() {
                    this.$nextTick(() => {
                        $('.stocks-without-code-select2').select2({
                            allowClear: true,
                            placeholder: "Pilih Stock",
                            ajax: {
                                url: `/select2/stock-without-codes-data/`,
                                dataType: "json",
                                type: "GET",
                                data: params => ({search: params.term}),
                                processResults: data => ({results: data}),
                                cache: true
                            }
                        });
                    })
                    this.itemWithoutCodeFields.push({
                        stock_id: '',
                        qty: '',
                    });
                },

                removeItemWithoutCode(index) {
                    if (this.itemWithoutCodeFields.length > 1) {
                        this.itemWithoutCodeFields.splice(index, 1);
                        $(`#stock-without-codes-select2`).val('').trigger('change')
                        this.$nextTick(() => {
                            this.getStockWithoutCodesData(index)
                        })
                    }
                },
            }
        }
    </script>
@endpush

