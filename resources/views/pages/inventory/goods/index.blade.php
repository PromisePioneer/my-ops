@extends('layouts.template')
@section('page-title', 'Data Barang')
@section('content')

    @push('style')
        <style>
            .gallery {
                display: flex;
                gap: 1em;
            }

            .gallery img {
                cursor: zoom-in;
            }
        </style>
    @endpush

    <div x-data="goodsData">
        @include('pages.inventory.goods.modal.use-items')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light-info me-3 btn-sm " data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-funnel-fill"></i>
                            </span>
                            Filter
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" style="">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bolder">Filter</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5">
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-bold">Cabang:</label>
                                    <select name="" id=""
                                            class="form-select form-select-solid filter-branch-select2">
                                        <option value="0">Pilih Cabang</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <a href="{{ url('inventory/goods/create') }}" class="btn btn-primary btn-sm">
                                Tambah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">No</th>
                                <th class="min-w-125px">Kode / SN</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Status Konfirmasi</th>
                                <th class="min-w-125px">Stok</th>
                                <th class="min-w-125px">Foto</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && goods.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(item, index) in goods?.data" :key="item.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="item.serial_number"></td>
                                    <td x-text="item.name"></td>
                                    <template x-if="item.confirmation_status === 0">
                                        <td>
                                            <button @click="confirm(item.id)" class="btn btn-danger btn-sm">Belum Di
                                                konfirmasi
                                            </button>
                                        </td>
                                    </template>
                                    <template x-if="item.confirmation_status === 1">
                                        <td>
                                            <span class="badge bg-success">Terkonfirmasi</span>
                                        </td>
                                    </template>
                                    <td x-text="item.qty"></td>
                                    <td class="gallery">
                                        <img :src="getImageURL(item.file)"
                                             @click="$dispatch('lightbox', `${getImageURL(item.file)}`)" width="100"
                                             height="100"/>
                                    </td>
                                    <template x-if="item.qty <= 0">
                                        <td>
                                            <span class="badge bg-danger">Stok habis.</span>
                                        </td>
                                    </template>
                                    <template x-if="item.confirmation_status === 1 && item.qty > 0">
                                        <td>
                                            <a :href="`/inventory/goods/used-items-detail/${item.id}`"
                                               class="btn btn-info btn-sm">
                                                Pakai Barang
                                            </a>
                                        </td>
                                    </template>
                                    <template x-if="item.confirmation_status === 0">
                                        <td>
                                            <a :href="`/inventory/goods/edit/${item.id}`"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-danger btn-sm" @click="destroy(item.id)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </template>

                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')

    <script defer>
        function goodsData() {
            return {
                goods: [],
                isLoading: true,
                buttonLoading: false,
                startIndex: null,
                search: '',
                id: '',
                detailData: '',
                modalDetail: new bootstrap.Modal(document.getElementById('modal-item-details')),
                itemForms: document.getElementById('used-goods-form'),
                deleteForm: document.getElementById('deleteForm'),
                async init() {
                    await this.goodsData();
                    await this.filterByBranch();
                },
                async searchData() {
                    this.goods = await axios.get('/inventory/goods/search', {
                        params: {search: this.search},
                        headers: {'Content-Type': 'application/json'}
                    });
                },
                async nextPage() {
                    if (this.goods.next_page_url) {
                        const resp = await axios.get(`${this.goods.next_page_url}`);
                        this.startIndex = this.goods.from
                        this.goods = resp.data
                    }
                },
                async previousPage() {
                    if (this.goods.prev_page_url) {
                        const resp = await axios.get(`${this.goods.prev_page_url}`);
                        this.startIndex = this.goods.from
                        this.goods = resp.data
                    }
                },
                async confirm(id) {
                    showConfirmModal("Anda yakin?", "Data tidak akan bisa di ubah dan di hapus setelah terkonfirmasi", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/inventory/goods/confirm/${id}`);
                            await showAlert('success', 'Data sukses dikonfirmasi!');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async detail(id) {
                    this.id = id;
                    const response = await axios.get(`/inventory/goods/detail/data/${id}`);
                    this.detailData = response.data;
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/inventory/goods/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async goodsData() {
                    const goods = await axios.get('/inventory/goods/data');
                    this.goods = goods.data
                    this.startIndex = this.goods.from;
                    this.isLoading = false;
                },
                async filterByBranch() {
                    const self = this;
                    $(".filter-branch-select2").select2({
                        ajax: {
                            url: '/inventory/goods/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                    $(".filter-branch-select2").on('change', async function () {
                        const selectedBranch = $(this).select2('data')[0];
                        const response = await axios.get(`/inventory/goods/filter/branch/data/${selectedBranch.id}`);
                        self.goods = response.data;
                    });
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });
                    return IDR.format(curr);
                },
                getImageURL(imagePath) {
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
            }
        }
    </script>
@endpush
