@extends('layouts.template')
@section('content')
    <div x-data="generateStockMutation()">
        @include('pages.inventory.goods.stocks.stock-mutation.drawer.item-catalog-details')
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6" href="{{ url('/inventory/goods/stock') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body">
                        @if(!Auth::user()->branch_id)
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="col-form-label required fw-bold fs-6">Pilih Cabang</label>
                                    <select name="" id="" class="form-select form-select-solid branches-select2">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        @else
                        @endif
                    </div>

                    <div class="separator py-2"></div>

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
                            <template x-for="stock in currentStocks" :key="stock.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="stock.id"
                                                   :id="'checkbox-' + stock.id"
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
                selectAll: false,
                singleChecked: false,
                itemId: "{{ $itemCollection->id }}",
                async init() {
                    await this.getBranchData();
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
                    localStorage.setItem('selectedCheckBox', JSON.stringify(this.selectedCheckBox));
                },
                selectCheckBox(event) {
                    const checkboxId = event.target.value;
                    if (event.target.checked) {
                        this.selectedCheckBox.push(checkboxId);
                        console.log(checkboxId);
                        localStorage.setItem('selectedCheckBox', JSON.stringify(this.selectedCheckBox));
                    } else {
                        const index = this.selectedCheckBox.indexOf(checkboxId);
                        if (index !== -1) {
                            this.selectedCheckBox.splice(index, 1);
                            localStorage.setItem('selectedCheckBox', JSON.stringify(this.selectedCheckBox));
                        }
                    }
                },
                async getStock() {

                },
                async getBranchData() {
                    const self = this;
                    $(".branches-select2").select2({
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
                        const branchId = e.params.data.id;
                        const itemId = self.itemId;

                        const resp = await axios.get(`/inventory/goods/stock/${branchId}/${itemId}`);
                        self.currentStocks = resp.data;
                    });
                }
            }
        }
    </script>
@endpush

