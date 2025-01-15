@php use function App\Helper\formatDate; @endphp
@extends('layouts.template')
@section('page-title', 'Buat Kode Untuk ' . $goodsPurchaseOrder->item->name)
@section('content')
    <div x-data="generateSN()">
        @include('pages.inventory.goods.stocks.generate-sn.modal.form')
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title text-uppercase text-decoration-underline fw-bold">
                            Informasi Barang
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <div class="table-responsive">
                            <table class="table w-100">
                                <thead>
                                <tr class="fw-bold ">
                                    <th class="w-200px">Tanggal Masuk</th>
                                    <th class="w-10px">:</th>
                                    <th>{{ formatDate($goodsPurchaseOrder->date) }}</th>
                                </tr>
                                <tr class="fw-bold">
                                    <th>Nama Barang</th>
                                    <th>:</th>
                                    <th>{{ $goodsPurchaseOrder->item->name }}</th>
                                </tr>
                                <tr class="fw-bold">
                                    <th>Qty (Belum diberi kode)</th>
                                    <th>:</th>
                                    <th x-text="purchaseOrder.qty - goodsStock?.data?.length"></th>
                                </tr>
                                <tr class="fw-bold">
                                    <th>Lokasi</th>
                                    <th>:</th>
                                    <th>{{ $goodsPurchaseOrder->branch->name ?? $goodsPurchaseOrder->warehouse->name }}</th>
                                </tr>
                                </thead>
                                <tbody class="fw-bold">
                                <tr>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="alert alert-info px-5 pt-2 mb-xl-8">
                    <div class="d-flex align-items-center mb-2 pt-5">
                        <i class="bi bi-info-circle-fill fs-1 text-info me-4">
                            <span
                                class="path1"></span><span class="path2"></span>
                        </i>
                        <h4 class="pt-1 text-dark">Informasi</h4>
                    </div>
                    <ol>
                        <li>Barang yang belum diberi SN tidak dapat didistribusikan.</li>
                        <li>Barang yang sudah terverifikasi tidak dapat dihapus maupun diubah jika ada kesalahan
                            (dapat menghubungi stakeholder terkait).
                        </li>
                        <li>Barang yang memiliki SN tidak dapat generate langsung dan harus memasukkan SN satu
                            persatu.
                        </li>
                    </ol>
                </div>
            </div>


        </div>
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
                            <template
                                x-if="purchaseOrder?.item?.need_sn === 0 && purchaseOrder?.qty !== 0 && purchaseOrder?.from_po === 1">
                                <button type="button" class="btn btn-light-primary btn-sm"
                                        @click="generateCodeWithoutSN()">
                                    <i class="ki-duotone ki-message-add fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i> Buat Kode Otomatis
                                </button>
                            </template>
                            <template
                                x-if="purchaseOrder?.item?.need_sn === 1 && purchaseOrder?.qty !== 0">
                                <button type="button" class="btn btn-light-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-sn-create" @click="add()">
                                    <i class="ki-duotone ki-message-add fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i> Tambah
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <form id="form-delete" @submit.prevent="destroy()" class="me-3">
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
                    <form id="form-confirm" @submit.prevent="confirm()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                        <button type="submit" class="btn btn-light-info btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <i class="bi bi-check-circle-fill"></i>
                            Konfirmasi
                        </button>
                    </form>
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table table-bordered fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox"
                                               @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">SN</th>
                                <th class="min-w-125px">Status</th>
                                <template x-if="purchaseOrder?.item?.need_sn === 1">
                                    <th class="min-w-125px">Actions</th>
                                </template>
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
                            <template x-if="!isLoading && goodsStock?.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="stock in goodsStock?.data"
                                      :key="stock.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td>
                                        <template x-if="stock.status === 0">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                 @click="selectCheckBox($event)">
                                                <input class="form-check-input" type="checkbox" :value="stock.id"
                                                       :id="'checkbox-' + stock.id"/>
                                            </div>
                                        </template>
                                    </td>
                                    <td x-text="stock.sn"></td>
                                    <template x-if="stock.status === 0">
                                        <td>
                                            <button class="btn btn-sm btn-light-danger">
                                                <i class="bi bi-x-square-fill"></i>
                                                Pending
                                            </button>
                                        </td>
                                    </template>
                                    <template x-if="stock.status === 1">
                                        <td>
                                            <button class="btn btn-sm btn-light-success">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Terverifikasi
                                            </button>
                                        </td>
                                    </template>
                                    <td>
                                        <template
                                            x-if="purchaseOrder?.item.need_sn === 1 && stock.status === 0">
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-sn-create" @click="edit(stock.id)">
                                                <i class="ki-duotone ki-pencil fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ url('/inventory/goods/stock/detail', $goodsPurchaseOrder->id) }}"
                           class="btn btn-light-danger btn-sm">Kembali</a>
                        <ul class="pagination float-end">
                            <template x-for="pagination in goodsStock?.links">
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
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function generateSN() {
            return {
                search: '',
                buttonLoading: false,
                isLoading: false,
                id: "{{ $goodsPurchaseOrder->id }}",
                purchaseOrder: {},
                selectedCheckBox: [],
                goodsStock: [],
                editVal: '',
                form: document.getElementById('form-sn-create'),
                modalSN: new bootstrap.Modal(document.getElementById('modal-sn-create')),
                formDelete: document.getElementById('form-delete'),
                formConfirm: document.getElementById('form-confirm'),
                async init() {
                    await this.getPurchaseOrder();
                    await this.getStockData();
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
                add() {
                    this.editVal = '';
                    this.form.reset();
                },
                async getStockData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/inventory/goods/stock/detail/po/generate-sn/po-detail/get-stock/${this.id}`);
                        this.goodsStock = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getPurchaseOrder() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/inventory/goods/stock/detail/po/generate-sn/po-detail/data/${this.id}`);
                        this.purchaseOrder = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async generateSN() {
                    this.buttonLoading = true;
                    try {
                        const resp = await axios.post(`/inventory/goods/stock/detail/po/generate-sn/po-detail/generate-sn/${this.id}`, {
                            selectedCheckBox: this.selectedCheckBox
                        });
                        this.goodsStock = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async save(id = null) {
                    this.buttonLoading = true;
                    try {

                        if (!id) {
                            await axios.post(`/inventory/goods/stock/detail/po/generate-sn/store/${this.id}`, new FormData(this.form))
                        } else {
                            await axios.post(`/inventory/goods/stock/detail/po/generate-sn/update/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        this.modalSN.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/inventory/goods/stock/detail/po/generate-sn/edit/${id}`);
                    this.editVal = resp.data;
                },
                async confirm() {
                    showConfirmModal("Anda yakin?", "Data tidak bisa dihapus atau diubah jika di konfirmasi.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/inventory/goods/stock/detail/po/generate-sn/confirm/`, new FormData(this.formConfirm));
                            await showAlert('success', 'Data sukses dikonfirmasi');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post('/inventory/goods/stock/detail/po/generate-sn/destroy/', new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
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
