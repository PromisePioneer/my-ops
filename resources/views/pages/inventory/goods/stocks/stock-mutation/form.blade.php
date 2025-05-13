@extends('layouts.template')
@section('content')
    <div x-data="generateStockMutation()">
        @include('pages.inventory.goods.stocks.stock-mutation.drawer.item-catalog-details')
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6" href="{{ url('manage-users/users/') }}">Kembali</a>
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
                                <th class="min-w-50px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                    rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending">
                                    Cabang
                                </th>
                                <th class="min-w-50px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                    rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending">
                                    Nama
                                </th>
                                <th class="min-w-150px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                    rowspan="1" colspan="1"
                                    aria-label="User: activate to sort column ascending">
                                    Kondisi
                                </th>
                                <th class="text-center sorting_disabled" rowspan="1" colspan="1"
                                    aria-label="Actions">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="5">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="stock in currentStocks" :key="stock.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td x-text="stock.branch_name"></td>
                                    <td x-text="stock.name"></td>
                                    <td x-text="stock.code"></td>
                                    <td x-text="stock.condition"></td>
                                    <td>
                                        <button id="kt_drawer_example_basic_button" class="btn btn-light-info btn-sm">
                                            <i class="ki-duotone ki-filter-square">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Filter
                                        </button>
                                    </td>
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
                currentStocks: null,
                itemId: "{{ $itemCollection->id }}",
                async init() {
                    await this.getBranchData();
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

