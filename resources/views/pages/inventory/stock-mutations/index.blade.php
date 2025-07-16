@extends('layouts.template')
@section('page-title', 'Mutasi Barang')
@section('breadcrumbs', 'Inventory Controller - Mutasi Barang')
@section('content')
    <div x-data="stockMutations()">
        @include('pages.inventory.stock-mutations.detail')
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
                    @can('Tambah Data Mutasi Barang')
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <a href="{{ url('/inventory/stock-mutations/create') }}" type="button"
                               class="btn btn-light-primary btn-sm">
                                <x-icons.add-item/>
                                Tambah
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12 ">
                    @can('Hapus Mutasi Barang')
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
                    @endcan
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    #
                                </th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Yg Terlibat</th>
                                <th class="min-w-125px">Actions</th>
                            </tr>
                            </thead>
                            <template x-if="isLoading">
                                <x-table.loading colspan="5"/>
                            </template>
                            <template x-if="!isLoading && stockMutation.data?.length === 0">
                                <x-table.empty colspan="5"/>
                            </template>
                            <template x-for="(stock, index) in stockMutation?.data" :key="stock.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td class="text-center">
                                        <p x-text="stock.date"></p>
                                        <div class="mb-4">
                                            <template x-if="stock.status === 'Diterima'">
                                                <span class="badge bg-light-success fs-6 text-success">Diterima</span>
                                            </template>
                                            <template x-if="stock.status === 'Dikirim'">
                                                <span class="badge bg-light-info fs-6 text-info">Dikirim</span>
                                            </template>
                                            <template x-if="stock.status === 'Dibatalkan'">
                                                <span class="badge bg-light-danger text-danger fs-6">Dibatalkan</span>
                                            </template>
                                        </div>
                                        <p x-text="`Dari ${stock.old_branch_name} ke ${stock.new_branch_name}`"></p>
                                    </td>
                                    <td>
                                        <p class="text-center" x-text="`Pengirim : ${stock.sender_name}`"></p>
                                        <p class="text-center" x-text="`Penerima : ${stock.receiver_name}`"></p>
                                    </td>
                                    <td>
                                        <ul>
                                            <template x-for="(item, index) in stock.items" :key="index">
                                                <template x-if="!item.code">
                                                    <li x-text="`${item.name} (${item.qty})`"></li>
                                                </template>
                                                <template x-if="item.code">
                                                    <li x-text="`${item.code} ${item.name} (${item.qty})`"></li>
                                                </template>
                                            </template>
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-stock-mutations-detail"
                                                @click="showDetail(stock.id)">
                                            <x-icons.info/>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in stockMutation.links">
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
        function stockMutations() {
            return {
                isLoading: false,
                loggedUserId: "{{ Auth::id() }}",
                stockMutation: [],
                deletePermission: "{{ request()->user()->can('Hapus Data Mutasi Barang') }}",
                receiveItemPermission: "{{ request()->user()->can('Terima Mutasi Barang') }}",
                startIndex: null,
                search: '',
                editVal: {},
                selectedCheckBox: [],
                formDelete: document.getElementById('form-delete'),
                detailModal: new bootstrap.Modal(document.getElementById('modal-stock-mutations-detail')),
                async init() {
                    await this.getStockMutations();
                },
                async getStockMutations() {
                    try {
                        const resp = await axios.get('/inventory/stock-mutations/data');
                        this.stockMutation = resp.data;
                        this.startIndex = resp.data.from;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/stock-mutations/search', {
                            params: {
                                search: this.search
                            }
                        });
                        this.stockMutation = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async showDetail(id) {
                    try {
                        const resp = await axios.get(`/inventory/stock-mutations/show/${id}`);
                        this.editVal = resp.data;
                    } catch (e) {
                        console.log(e);
                    }
                },
                async sendStock() {
                    showConfirmModal("Anda yakin?", "Barang yang dikirim tidak akan bisa dihapus atau dikembalikan lagi.", "Ya, Kirim!", async () => {
                        try {
                            await axios.post(`/inventory/stock-mutations/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses Dikirim');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/inventory/stock-mutations/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async sendItem(id) {
                    showConfirmModal("Anda yakin?", "Barang yang sudah anda kirim akan hilang dalam stock. jika ingin membatalkan pengiriman, lakukan sebelum penerima mengkonfirmasi mereka telah menerima barang. stok yang tadinya hilang akan kembali.", "Ya, Kirim!", async () => {
                        try {
                            await axios.post(`/inventory/stock-mutations/send-item/${id}`, new FormData(this.formDelete));
                            await showAlert('success', 'Barang sukses dikirim');
                            await this.init();
                            await this.detailModal.hide();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async cancelDelivery(id) {
                    showConfirmModal("Anda yakin?", "Batalkan Pengiriman?", "Ya, Kirim!", async () => {
                        try {
                            await axios.post(`/inventory/stock-mutations/cancel-item-delivery/${id}`);
                            await showAlert('success', 'Pengiriman berhasil dibatalkan');
                            await this.init();
                            await this.detailModal.hide();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async receiveItem(id) {
                    showConfirmModal("Anda yakin?", "Terima Pengiriman? Mohon di pastikan barang sudah sesuai", "Ya, Terima!", async () => {
                        try {
                            await axios.post(`/inventory/stock-mutations/receive/${id}`);
                            await showAlert('success', 'Pengiriman berhasil diterima');
                            await this.init();
                            await this.detailModal.hide();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                }
            }
        }
    </script>
@endpush
