@extends('layouts.template')
@section('content')

    <div x-data="contractData()">
        @include('pages.manage-users.contract-management.drawer.filter')
        <div class="flex-lg-row-fluid ms-lg-10">
            <div class="card card-flush mb-6 mb-xl-9">
                <div class="card-header pt-5">
                    <div class="card-title">
                        <button id="kt_drawer_example_basic_button" class="btn btn-light-info btn-sm">
                            <x-icons.filter/>
                            Filter
                        </button>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex align-items-center position-relative my-1"
                             data-kt-view-roles-table-toolbar="base">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viemanagewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="17.0365" y="15.1223"
                                                                      width="8.15546" height="2" rx="1"
                                                                      transform="rotate(45 17.0365 15.1223)"
                                                                      fill="black"></rect>
																<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                                      fill="black"></path>
															</svg>
														</span>
                            <input type="text" class="form-control form-control-solid w-250px ps-15"
                                   x-model="search" @input.debounce="searchData()" placeholder="Cari...">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5 mb-0 dataTable no-footer"
                               id="kt_roles_view_table">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-200px sorting">
                                    Nama
                                </th>
                                <th class="min-w-5px sorting">
                                    Masa Berlaku
                                </th>
                                <th class="min-w-10px sorting">
                                    Status
                                </th>
                                <th class="min-w-125px sorting">
                                    Salinan Kontrak
                                </th>
                                <th class="min-w-100px sorting_disabled">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <template x-if="isLoading">
                                <x-table.loading colspan="5"/>
                            </template>
                            <template x-if="!isLoading && contracts.data?.length === 0">
                                <x-table.empty colspan="5"/>
                            </template>
                            <template x-for="(contract, index) in contracts.data" :key="contract.id">
                                <tbody class="fw-bold text-gray-600">
                                <tr>
                                    <td>
                                        <a :href="`/manage-users/users/detail/${contract.id}`"
                                           class="text-gray-800 text-hover-primary mb-1">
                                            <span x-text="contract.name"></span>
                                        </a>
                                    </td>
                                    <td x-text="contract.contract_date"></td>
                                    <template x-if="contract.expired === false">
                                        <td>
                                            <span class="badge bg-danger">Sudah Habis</span>
                                        </td>
                                    </template>
                                    <template x-if="contract.expired === true">
                                        <td>
                                            <span class="badge bg-success">Masih Berlaku</span>
                                        </td>
                                    </template>
                                    <template x-if="contract.expired === true">
                                        <td>
                                            <a :href="`/manage-users/contract-management/contract-pdf/${contract.id}`"
                                               class="btn btn-active-danger btn-light-danger btn-sm">
                                                <i class="bi bi-file-pdf-fill"></i>
                                            </a>
                                        </td>
                                    </template>
                                    <template x-if="contract.expired === false">
                                        <td>
                                            <button disabled
                                                    class="btn btn-active-danger btn-light-danger btn-sm">
                                                <i class="bi bi-file-pdf-fill"></i>
                                            </button>
                                        </td>
                                    </template>
                                    <template x-if="contract.expired === false">
                                        <td class="text-end">
                                            <button class="btn btn-light btn-active-info btn-sm"
                                                    @click="extendContract(contract.user_id)">
                                                Extend
                                            </button>
                                        </td>
                                    </template>
                                    <template x-if="contract.expired === true">
                                        <td class="text-end">
                                            <button class="btn btn-light btn-active-info btn-sm" disabled>
                                                Extend
                                            </button>
                                        </td>
                                    </template>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in contracts.links">
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
        function contractData() {
            return {
                buttonLoading: false,
                isLoading: false,
                startIndex: null,
                contracts: [],
                months: [],
                search: '',
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                async init() {
                    await this.getContractData();
                    await this.getMainBranches();
                    await this.getMonth();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/manage-users/contract-management/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.contracts = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async filter() {
                    const year = document.getElementById('year')?.value ?? '';
                    const month = document.getElementById('month')?.value ?? '';
                    const branch_id = $(".main-branches-select2")?.val();
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/contract-management/filter', {
                            params: {
                                month: month,
                                year: year,
                                branch_id: branch_id,
                            }
                        });
                        this.contracts = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                getMonth() {
                    this.months.push(
                        {name: "Januari", number: '01'},
                        {name: "Februari", number: '02'},
                        {name: "Maret", number: '3'},
                        {name: "April", number: '04'},
                        {name: "Mei", number: '05'},
                        {name: "Juni", number: '06'},
                        {name: "Juli", number: '07'},
                        {name: "Agustus", number: '08'},
                        {name: "September", number: '09'},
                        {name: "Oktober", number: '10'},
                        {name: "November", number: '11'},
                        {name: "Desember", number: '12'},
                    )
                },
                async paginationEndPoint(url) {
                    if (url) {
                        this.branches = [];
                        this.isLoading = true;
                        try {
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                }
                            });
                            this.contracts = resp.data
                        } catch (e) {
                            console.log(e)
                        } finally {
                            this.isLoading = false
                        }
                    }
                },
                async extendContract(id) {
                    showConfirmModal("Anda yakin?", "Perpanjang Kontrak ?", "Ya, Perpanjang!", async () => {
                        try {
                            await axios.post(`/manage-users/contract-management/extend-contract/${id}`)
                            await showAlert('success', 'Data sukses dieksekusi');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
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
                async getContractData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/contract-management/data');
                        this.contracts = resp.data;
                        this.startIndex = this.contracts.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
            }
        }
    </script>
@endpush
