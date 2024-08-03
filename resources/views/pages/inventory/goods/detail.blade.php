@extends('layouts.template')
@section('page-title', 'Barang Detail')
@section('content')

    <div class="d-flex flex-column flex-lg-row" x-data="goodsDetail()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card">
                <div class="card-body p-12">
                    <div class="table-responsive">

                        <div class="mb-10">
                            <h6 class="fs-5">Detail Barang</h6>
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">No</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Harga satuan</th>
                                <th class="min-w-125px">Stok</th>
                                <th class="min-w-125px">Foto</th>
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
                            <tr>
                                <td>1</td>
                                <td x-text="goods.name"></td>
                                <td x-text="formatNumber(goods.unit_price)"></td>
                                <td x-text="goods.qty"></td>
                                <td class="gallery">
                                    <button class="btn btn-info btn-sm"
                                            @click="$dispatch('lightbox', `${getImageURL(goods.file)}`)">Lihat File
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="separator separator-dashed my-10"></div>

                    <div class="mb-10">
                        <h6 class="fs-5">Detail Barang Terpakai</h6>
                    </div>
                    <div class="table-responsive mb-10">

                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">No</th>
                                <th class="min-w-125px">Nama Barang</th>
                                <th class="min-w-125px">Total Barang Terpakai</th>
                                <th class="min-w-125px">Total Harga</th>
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
                            <template x-for="(row, index) in usedItems.data" :key="row.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="row.goods.name"></td>
                                    <td x-text="row.total_used"></td>
                                    <td x-text="formatNumber(row.total_used * row.goods.unit_price)"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                    <br>
                    <div class="separator separator-dashed my-10"></div>
                    <br>

                    <form id="form" @submit.prevent="saveUsedItems()" enctype="multipart/form-data" class="mb-10">
                        @csrf
                        <div class="mb-0">
                            <div class="row gx-10 mb-5">
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Nama Barang
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid" name="name"
                                               value="{{ $goods->name }}" disabled/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Barang Terpakai
                                    </label>
                                    <div class="mb-5">
                                        <input type="text" class="form-control form-control-solid" name="total_used"/>
                                    </div>
                                </div>
                            </div>
                            @if ($goods->type === 'aset')
                                <div class="mb-0">
                                    <div class="row gx-10 mb-5">
                                        <div class="col-lg-6">
                                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                                Debit (Aset)
                                            </label>
                                            <div class="mb-5">
                                                <select class="form-select form-select-solid asset-account-select2 "
                                                        name="asset_account">
                                                    <option value="0" selected>Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="float-end">
                                    <a href="{{ url('inventory/goods') }}" class="btn btn-sm btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-primary" :disabled="buttonLoading"
                                            x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                                    </button>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('components.toast')
        @endsection
        @push('script')
            <script>
                function goodsDetail() {
                    return {
                        goods: [],
                        isLoading: false,
                        id: "{{ $goods->id }}",
                        usedItems: [],
                        buttonLoading: false,
                        startIndex: null,
                        form: document.getElementById('form'),
                        usedItemId: '',
                        async init() {
                            await this.getGoodsData();
                            await this.getUsedItems();
                            await this.getAssetAccounts();
                            await this.getSupplyAccount();
                        },
                        async nextPage() {
                            if (this.usedItems.next_page_url) {
                                const resp = await axios.get(`${this.usedItems.next_page_url}`);
                                this.startIndex = this.usedItems.from
                                this.usedItems = resp.data
                            }
                        },
                        async previousPage() {
                            if (this.usedItems.prev_page_url) {
                                const resp = await axios.get(`${this.usedItems.prev_page_url}`);
                                this.startIndex = this.usedItems.from
                                this.usedItems = resp.data
                            }
                        },
                        async getGoodsData() {
                            const goods = await axios.get(`/inventory/goods/detail/data/${this.id}`);
                            this.goods = goods.data
                        },
                        async getUsedItems() {
                            const usedItems = await axios.get(`/inventory/used-items/get-used-items/${this.id}`);
                            this.usedItems = usedItems.data;
                            this.startIndex = this.usedItems.from;
                        },
                        async getAssetAccounts() {
                            $(".asset-account-select2").select2({
                                ajax: {
                                    url: '/inventory/used-items/account/asset/data',
                                    dataType: "json",
                                    type: "GET",
                                    data: params => ({search: params.term}),
                                    processResults: data => ({results: data}),
                                    cache: true
                                }
                            });
                        },
                        async getSupplyAccount() {
                            $(".supply-account-select2").select2({
                                ajax: {
                                    url: '/inventory/used-items/account/supply/data',
                                    dataType: "json",
                                    type: "GET",
                                    data: params => ({search: params.term}),
                                    processResults: data => ({results: data}),
                                    cache: true
                                }
                            });
                        },
                        async saveUsedItems() {
                            this.buttonLoading = true;
                            try {
                                await axios.post(`/inventory/used-items/save-used-items/${this.id}`, new FormData(this.form))
                                await showAlert('success', 'Data berhasil disimpan')
                                this.form.reset();
                                await this.init();
                            } catch (error) {
                                const respError = error.response.data.errors;
                                Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));

                            } finally {
                                this.buttonLoading = false;
                            }
                        },
                        getImageURL(imagePath) {
                            return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
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
