@extends('layouts.template')
@section('content')
    <div x-data="itemStockData()">
        <div class="row gy-5 gx-xl-10">
            <div class="col-xl-4 mb-xl-10">
                <div class="card h-md-100" dir="ltr">
                    <div class="card-body d-flex flex-column flex-center">
                        <div class="mb-2">
                            <h1 class="fw-semibold text-gray-800 text-center lh-lg">Quick form to
                                <br>
                                <span class="fw-bolder">Bid a New Shipment</span></h1>
                            <div class="py-10 text-center">
                                <img src="assets/media/svg/illustrations/easy/3.svg" class="theme-light-show w-200px"
                                     alt="">
                                <img src="assets/media/svg/illustrations/easy/3-dark.svg"
                                     class="theme-dark-show w-200px" alt="">
                            </div>
                        </div>
                        <div class="text-center mb-1">
                            <a class="btn btn-sm btn-primary me-2" data-bs-target="#kt_modal_bidding"
                               data-bs-toggle="modal">Start Now</a>
                            <a class="btn btn-sm btn-light" href="apps/invoices/view/invoice-2.html">Quick Guide</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 mb-5 mb-xl-10">
                <div class="row g-lg-5 g-xl-10">
                    <div class="col-md-6 col-xl-6 mb-5 mb-xl-10">
                        <div class="card overflow-hidden mb-xl-10">
                            <div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
                                <div class="mb-4 px-9">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">259,786</span>
                                    </div>
                                    <span class="fs-6 fw-semibold text-gray-500">Barang Masuk</span>
                                </div>
                            </div>
                        </div>

                        <div class="card card-flush mb-lg-10">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">69,700</span>
                                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Barang Kembali</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-end pt-0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6 mb-md-5 mb-xl-10">
                        <div class="card overflow-hidden  mb-5 mb-xl-10">
                            <div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
                                <div class="mb-4 px-9">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">47,769,700</span>
                                        <span class="d-flex align-items-end text-gray-500 fs-6 fw-semibold">Tons</span>
                                    </div>
                                    <span class="fs-6 fw-semibold text-gray-500">Barang Keluar</span>
                                </div>
                            </div>
                        </div>
                        <div class="card card-flush mb-lg-10">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">69,700</span>
                                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Stok</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-end pt-0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-5 g-xl-10">
            <!--begin::Col-->
            <div class="col-xl-4 mb-xl-10">
                <!--begin::List widget 10-->
                <div class="card card-flush h-lg-100">
                    <!--begin::Header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Informasi</span>
                        </h3>
                        <!--end::Title-->
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <a href="{{ url('inventory/goods/draft-stocks') }}" class="btn btn-sm btn-light"
                               data-bs-toggle="tooltip" data-bs-dismiss="click"
                               data-bs-custom-class="tooltip-inverse"
                               data-bs-original-title="Logistics App is coming soon" data-kt-initialized="1">Lihat
                                Selengkapnya</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills nav-pills-custom row position-relative mx-0 mb-9" role="tablist">
                            <li class="nav-item col-6 mx-0 p-0" role="presentation">
                                <a class="nav-link active d-flex justify-content-center w-100 border-0 h-100"
                                   data-bs-toggle="pill" href="#kt_list_widget_10_tab_1" aria-selected="true"
                                   role="tab">
                                    <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Belum Diberi kode</span>
                                    <span
                                            class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                </a>
                            </li>
                            <li class="nav-item col-6 mx-0 px-0" role="presentation">
                                <a class="nav-link d-flex justify-content-center w-100 border-0 h-100"
                                   data-bs-toggle="pill" href="#kt_list_widget_10_tab_2" aria-selected="false"
                                   tabindex="-1" role="tab">
                                    <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Sudah Boleh Reorder</span>
                                    <span
                                            class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                </a>
                            </li>
                            <span class="position-absolute z-index-1 bottom-0 w-100 h-4px bg-light rounded"></span>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="kt_list_widget_10_tab_1" role="tabpanel">
                                <div class="m-0 border border-dashed border-gray-400 p-5">
                                    <div class="d-flex align-items-center flex-row-fluid justify-content-between">
                                        <a href="#" class="fs-6 fw-bolder text-black">GPON</a>
                                        <span class="text-gray-800 fw-bold d-block fs-4">1000 Unit</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="kt_list_widget_10_tab_2" role="tabpanel">
                                <div class="m-0">
                                    <div class="d-flex align-items-sm-center mb-5">
                                        <div class="symbol symbol-45px me-4">
																	<span class="symbol-label bg-primary">
																		<i class="ki-duotone ki-airplane-square text-inverse-primary fs-1">
																			<span class="path1"></span>
																			<span class="path2"></span>
																		</i>
																	</span>
                                        </div>
                                        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                            <div class="flex-grow-1 me-2">
                                                <a href="#" class="text-gray-500 fs-6 fw-semibold">Plane Freight</a>
                                                <span class="text-gray-800 fw-bold d-block fs-4">#5635-342808</span>
                                            </div>
                                            <span
                                                    class="badge badge-lg badge-light-success fw-bold my-2 fs-8">Delivered</span>
                                        </div>
                                    </div>
                                    <div class="timeline">
                                        <div class="timeline-item align-items-center mb-7">
                                            <div class="timeline-line mt-1 mb-n6 mb-sm-n7"></div>
                                            <div class="timeline-icon">
                                                <i class="ki-duotone ki-cd fs-2 text-danger">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                            <div class="timeline-content m-0">
                                                <span class="fs-6 text-gray-500 fw-semibold d-block">KLM Cargo</span>
                                                <span
                                                        class="fs-6 fw-bold text-gray-800">Schipol Airport, Amsterdam</span>
                                            </div>
                                        </div>
                                        <div class="timeline-item align-items-center">
                                            <div class="timeline-line"></div>
                                            <div class="timeline-icon">
                                                <i class="ki-duotone ki-geolocation fs-2 text-info">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                            <div class="timeline-content m-0">
                                                <span
                                                        class="fs-6 text-gray-500 fw-semibold d-block">Singapore Cargo</span>
                                                <span
                                                        class="fs-6 fw-bold text-gray-800">Changi Airport, Singapore</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-6"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('components.toast')
    </div>
@endsection
@push('script')
    <script>
        function itemStockData() {
            return {
                buttonLoading: false,
                goodsStock: [],
                consumedStock: [],
                        ifMutated: null,
                        search: '',
                        placement: null,
                        isLoading: false,
                        startIndex: null,
                        stockDetail: null,
                draftStock: [],
                        async init() {
                            await this.getGoodsStock();
                            await this.debitAccounts();
                            await this.creditAccounts();
                            await this.getAllBranch();
                            await this.getDraftStockQty();
                            this.modal.addEventListener('hidden.bs.modal', () => {
                                this.form.reset();
                                this.ifMutated = null;
                            });
                        },
                        async getGoodsStock() {
                            this.isLoading = true;
                            try {
                                const resp = await axios.get('/inventory/goods/stock/data');
                                this.goodsStock = resp.data;
                                this.startIndex = resp.data.from;
                            } catch (e) {
                                console.log(e)
                            } finally {
                                this.isLoading = false;
                            }
                        },
                        async getAllBranch() {
                            $(".branches-select2").select2({
                                allowClear: true,
                                placeholder: "Pilih Cabang",
                                ajax: {
                                    url: `/select2/branches-data`,
                                    dataType: "JSON",
                                    type: "GET",
                                    data: params => ({search: params.term}),
                                    processResults: data => ({results: data}),
                                    cache: true
                                }
                            });
                        },
                async getDraftStockQty() {
                    try {
                        const resp = await axios.get('/inventory/goods/draft-stocks/get-qty');
                        this.draftStock = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                        async getMainBranchesWithStock(id) {
                            console.log(id);
                            $(".main-branches-select2").select2({
                                allowClear: true,
                                placeholder: "Pilih Cabang",
                                ajax: {
                                    url: `/inventory/goods/stock/branch/data/${id}`,
                                    dataType: "JSON",
                                    type: "GET",
                                    data: params => ({search: params.term}),
                                    processResults: data => ({results: data}),
                                    cache: false
                                }
                            });
                        },
                        async searchData() {
                            this.isLoading = true;
                            try {
                                const resp = await axios.get('/inventory/goods/stock/search', {
                                    params: {search: this.search},
                                    headers: {'Content-Type': 'application/json'}
                                });
                                this.goodsStock = resp.data;
                            } catch (error) {
                                console.log(error);
                            } finally {
                                this.isLoading = false;
                            }
                        },
                        async filter() {
                            this.isLoading = true;
                            try {
                                const resp = await axios.get('/inventory/goods/stock/filter', {
                                    params: {
                                        branch_id: $('#branch_id_filter').val(),
                                    }
                                });
                                this.goodsStock = resp.data;
                            } catch (e) {
                                console.log(e)
                            } finally {
                                this.isLoading = false;
                            }
                        },
                        async showStockDetail(id) {
                            await this.getMainBranchesWithStock(id);
                            const resp = await axios.get(`/inventory/goods/stock/show/${id}`);
                            this.stockDetail = resp.data;
                        },
                        async debitAccounts() {
                            $(".debit-accounts-select2").select2({
                                allowClear: true,
                                placeholder: "Pilih Akun",
                                ajax: {
                                    url: '/select2/asset-accounts-data',
                                    dataType: "json",
                                    type: "GET",
                                    data: params => ({search: params.term}),
                                    processResults: data => ({results: data}),
                                    cache: true
                                }
                            });
                        },
                        async creditAccounts() {
                            $(".credit-accounts-select2").select2({
                                allowClear: true,
                                placeholder: "Pilih Akun",
                                ajax: {
                                    url: '/select2/kas-accounts-data',
                                    dataType: "json",
                                    type: "GET",
                                    data: params => ({search: params.term}),
                                    processResults: data => ({results: data}),
                                    cache: true
                                }
                            });
                        },
                        async save() {
                            try {
                                this.buttonLoading = true;
                                await axios.post('/inventory/goods/consumed-stocks', new FormData(this.form));
                                await showAlert('success', 'Data berhasil disimpan');
                                this.form.reset();
                                this.ifMutated = null;
                                this.modal.hide();
                                await this.init();
                                $(".main-branches-select2").val(null).trigger("change");
                            } catch (error) {
                                const respError = error.response.data.errors;
                                Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                            } finally {
                                this.buttonLoading = false;
                            }
                        }
                    }
                }
            </script>
@endpush
