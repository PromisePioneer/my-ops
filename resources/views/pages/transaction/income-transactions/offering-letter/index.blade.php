@extends('layouts.template')
@section('page-title', 'Data Surat Penawaran')
@section('content')
    <div x-data="offeringLettersData()">
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
                    <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                        <template x-if="Number(createPermission) === 1">
                            <a href="{{ url('/income-transactions/offering-letters/create') }}"
                               class="btn btn-light-primary btn-sm">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Tambah
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_products">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    No
                                </th>
                                <th class="min-w-125px">Nomor Penawaran</th>
                                <th class="min-w-100px">Calon Klien</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-125px">Tgl Dibuat</th>
                                <th class="min-w-125px">PIC</th>
                            </thead>
                            <tbody class=" fw-bold">
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
                            <template x-if="!isLoading && offeringLetters.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(offeringLetter, index) in offeringLetters?.data" :key="offeringLetter.id">
                                <tr class="text-center">
                                    <td x-text="startIndex + index++">
                                    </td>
                                    <td>
                                        <a :href="`/income-transactions/offering-letters/detail/${offeringLetter.id}`"
                                           x-text="offeringLetter.offering_number"></a>
                                    </td>
                                    <td x-text="offeringLetter.company_name"></td>
                                    <template x-if="offeringLetter.status === 0">
                                        <td>
                                            <span class="badge bg-warning">Pending</span>
                                        </td>
                                    </template>
                                    <template x-if="offeringLetter.status === 1">
                                        <td>
                                            <span class="badge bg-success">Terkonfirmasi</span>
                                        </td>
                                    </template>
                                    <td x-text="formatDate(offeringLetter.created_at)"></td>
                                    <td x-text="offeringLetter.created_by"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-5">
                        <template x-for="pagination in offeringLetters.links">
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
@endsection
@push('script')
    <script>
        function offeringLettersData() {
            return {
                createPermission: "{{ request()->user()->can('Tambah Data Penawaran') }}",
                offeringLetters: [],
                isLoading: false,
                startIndex: null,
                search: '',
                filterCategory: '',
                async init() {
                    await this.offeringLettersData();
                    await this.filterByBranch();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/income-transactions/offering-letters/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.offeringLetters = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.offeringLetters = resp.data
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/income-transactions/offering-letters/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async filterByBranch() {
                    const self = this;
                    $(".filter-branch-select2").select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        ajax: {
                            url: '/income-transactions/offering-letters/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    }).on('change', async function (e) {
                        const selectedBranch = $(this).select2('data')[0];
                        const response = await axios.get(`/income-transactions/offering-letters/filter/branch/data/${selectedBranch.id}`);
                        self.offeringLetters = response.data;
                    });
                },
                async offeringLettersData() {
                    const offeringLetters = await axios.get('/income-transactions/offering-letters/data');
                    this.offeringLetters = offeringLetters.data;
                    this.startIndex = this.offeringLetters.from;
                }
            }
        }

    </script>
@endpush
