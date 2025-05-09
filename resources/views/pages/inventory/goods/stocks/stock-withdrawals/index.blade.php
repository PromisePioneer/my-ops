@extends('layouts.template')
@section('page-title', 'Pemakaian Barang')
@section('breadcrumbs', 'Inventory Controller - Pemakaian Barang')
@section('content')
    <div x-data="stockWithdrawalData()">
        @include('pages.inventory.goods.stocks.stock-withdrawals.detail')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <a href="{{ url('/inventory/goods/stock-withdrawals/create') }}"
                               class="btn btn-light-primary btn-sm">
                                <x-icons.plus/>
                                Tambah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12 ">
                    <form id="form-delete" @submit.prevent="destroy()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                        <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <i class="ki-duotone ki-trash-square fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            Hapus
                        </button>
                    </form>
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">#</th>
                                <th class="min-w-125px">Cabang</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Deskripsi</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold ">
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
                            <template x-if="!isLoading && stockWithdrawals.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="stockWithdrawal in stockWithdrawals?.data" :key="stockWithdrawal.id">
                                <tbody class="fw-bold ">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="stockWithdrawal.id"
                                                   :id="'checkbox-' + stockWithdrawal.id"/>
                                        </div>
                                    </td>
                                    <td class="text-center" x-text="stockWithdrawal.branch_name"></td>
                                    <td class="text-center" x-text="stockWithdrawal.date"></td>
                                    <td class="text-center" x-text="stockWithdrawal.description"></td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column justify-content-center">
                                            <button class="btn btn-light-info btn-sm mb-4" data-bs-toggle="modal"
                                                    data-bs-target="#modal-stock-withdrawal-detail"
                                                    @click="confirmedByKCA()">
                                                <i class="ki-duotone ki-information fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </button>
                                            <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-stock-withdrawal-detail"
                                                    @click="confirmedByStocker()">
                                                <i class="ki-duotone ki-information fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-stock-withdrawal-detail"
                                                @click="showDetail(stockWithdrawal.id)">
                                            <i class="ki-duotone ki-information fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </button>
                                        <button class="btn btn-light-danger btn-sm"
                                                @click="destroy(stockWithdrawal.id)">
                                            <i class="ki-duotone ki-trash fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in stockWithdrawals.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="paginationEndPoint(pagination.url)"
                                        x-html="pagination.label">
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function stockWithdrawalData() {
            return {
                isLoading: false,
                stockWithdrawals: [],
                search: '',
                stockWithdrawalDetail: {},
                selectedCheckBox: [],
                async init() {
                    await this.getStockWithdrawals();
                },
                async getStockWithdrawals() {
                    try {
                        const resp = await axios.get('/inventory/goods/stock-withdrawals/data');
                        this.stockWithdrawals = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        this.stockWithdrawals = [];
                        this.isLoading = true;
                        try {
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                }
                            });
                            this.stockWithdrawals = resp.data
                        } catch (e) {
                            console.log(e)
                        } finally {
                            this.isLoading = false
                        }
                    }
                },
                async showDetail(id) {
                    try {
                        const resp = await axios.get(`/inventory/goods/stock-withdrawals/show/${id}`);
                        this.stockWithdrawalDetail = resp.data;
                    } catch (e) {
                        console.log(e)
                    }
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        return "{{ asset('') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
                openImage(imagePath) {
                    const lightbox = new FsLightbox();
                    console.log(lightbox);
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        const image = "{{ asset('') }}" + placeholders
                        lightbox.props.sources = [image, image];
                        lightbox.open();
                    } else {
                        const image = "{{ Storage::url('') }}" + imagePath;
                        lightbox.props.sources = [image];
                        lightbox.open();
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/inventory/goods/stock-withdrawals/destroy/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async confirmedByKCA(id) {

                },
                async confirmedByStocker(id) {

                }
            }
        }
    </script>
@endpush
