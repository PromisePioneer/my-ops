@extends('layouts.template')
@section('page-title', 'Pemakaian Barang')
@section('breadcrumbs', 'Inventory Controller - Pemakaian Barang')
@section('content')
    <div x-data="stockWithdrawalData()">
        @include('pages.inventory.stock-withdrawals.detail')
        @include('pages.inventory.stock-withdrawals.drawer.filter')
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
                            <a href="{{ url('/inventory/stock-withdrawals/create') }}"
                               class="btn btn-light-primary btn-sm me-2">
                                <x-icons.add-item/>
                                Tambah
                            </a>

                            <button class="btn btn-light-info btn-sm" id="stock_withdrawal_filter_button">
                                <x-icons.filter/>
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">#</th>
                                <th class="min-w-125px">Cabang</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Deskripsi</th>
                                <th class="min-w-125px">PIC</th>
                                <th class="min-w-125px">Stocker</th>
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
                            <template x-for="(stockWithdrawal, index) in stockWithdrawals?.data"
                                      :key="stockWithdrawal.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td class="text-center" x-text="stockWithdrawal.branch_name"></td>
                                    <td class="text-center" x-text="stockWithdrawal.date"></td>
                                    <td class="text-center" x-text="stockWithdrawal.description"></td>
                                    <td class="text-center" x-text="stockWithdrawal.pic"></td>
                                    <td class="text-center" x-text="stockWithdrawal.stocker"></td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center flex-column">
                                            <button class="btn btn-light-info btn-sm mb-4" data-bs-toggle="modal"
                                                    data-bs-target="#modal-stock-withdrawal-detail"
                                                    @click="showDetail(stockWithdrawal.id)">
                                                <i class="ki-duotone ki-information fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                                Informasi Pemakaian
                                            </button>
                                            <template x-if="stockWithdrawal.status === 'Dibawa'">
                                                <a :href="`/inventory/stock-withdrawals/return/${stockWithdrawal.id}`"
                                                   class="btn btn-light-info btn-sm mb-4">
                                                    <i class="las la-boxes fs-2"></i>
                                                    Pengembalian Barang
                                                </a>
                                            </template>
                                            <a :href="`/inventory/stock-withdrawals/return/${stockWithdrawal.id}`"
                                               class="btn btn-light-warning btn-sm mb-4">
                                                <x-icons.back/>
                                                Barang Kembali
                                            </a>
                                            <button class="btn btn-light-danger btn-sm"
                                                    @click="destroy(stockWithdrawal.id)">
                                                <x-icons.trash/>
                                                Hapus
                                            </button>
                                        </div>
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
        $(document).ready(function () {
            flatpickr(".date-picker", {
                mode: "range",
                dateFormat: "d/m/Y",
                defaultDate: []
            });
        });

        function stockWithdrawalData() {
            return {
                isLoading: false,
                date: document.getElementById('date')?.value,
                stockWithdrawals: [],
                search: '',
                stockWithdrawalDetail: {},
                selectedCheckBox: [],
                detailModal: new bootstrap.Modal(document.getElementById('modal-stock-withdrawal-detail')),
                async init() {
                    await this.getStockWithdrawals();
                    await this.getMainBranches();
                },
                async getStockWithdrawals() {
                    try {
                        const resp = await axios.get('/inventory/stock-withdrawals/data');
                        this.stockWithdrawals = resp.data;
                        this.startIndex = this.stockWithdrawals.from;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getMainBranches() {
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/select2/main-branches-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    });
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
                async searchData() {
                    try {
                        this.isLoading = true;
                        this.stockWithdrawals = [];
                        const resp = await axios.get('/inventory/stock-withdrawals/search', {
                            params: {
                                search: this.search,
                                start_date: this.formatDate(this.date.split('to').map(part => part.trim())[0]),
                                end_date: this.formatDate(this.date.split('to').map(part => part.trim())[1]),
                                branch_id: $('#branch_id').val()
                            }
                        });
                        this.stockWithdrawals = resp.data
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    try {
                        this.isLoading = true;
                        this.stockWithdrawals = [];
                        const resp = await axios.get('/inventory/stock-withdrawals/filter', {
                            params: {
                                search: this.search,
                                start_date: this.formatDate(this.date.split('to').map(part => part.trim())[0]),
                                end_date: this.formatDate(this.date.split('to').map(part => part.trim())[1]),
                                branch_id: $('#branch_id').val()
                            }
                        });
                        this.stockWithdrawals = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false
                    }
                },
                async showDetail(id) {
                    try {
                        const resp = await axios.get(`/inventory/stock-withdrawals/show/${id}`);
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
                            await axios.delete(`/inventory/stock-withdrawals/destroy/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            await showAlert('error', `${error.response.data.message}`);
                        }
                    });
                },
                async confirmedByPIC(id) {
                    showConfirmModal("Anda yakin?", "Data yang diparaf tidak akan bisa diubah maupun dihapus,", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/inventory/stock-withdrawals/confirmed-by-pic/${id}`);
                            await showAlert('success', 'Data sukses Dikonfirmasi');
                            this.detailModal.hide();
                            await this.init();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async confirmedByStocker(id) {
                    showConfirmModal("Anda yakin?", "Data yang diparaf tidak akan bisa diubah maupun dihapus,", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/inventory/stock-withdrawals/confirmed-by-stocker/${id}`);
                            await showAlert('success', 'Data sukses Dikonfirmasi');
                            this.detailModal.hide();
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                formatDate(dateStr) {
                    if (!dateStr) {
                        return '';
                    }
                    const [day, month, year] = dateStr.split('/');
                    return `${year}-${month}-${day}`;
                },
            }
        }
    </script>
@endpush
