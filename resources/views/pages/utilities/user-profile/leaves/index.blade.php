@extends('layouts.template')
@section('page-title', 'Informasi Identitas')
@section('content')
    @include('pages.utilities.user-profile.partials.header')

    <div x-data="ownLeavesData">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">No</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Status (Cuti / Izin / Sakit)</th>
                                <th class="min-w-125px">Keterangan</th>
                                <th class="min-w-125px">Status Konfirmasi</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <tbody class="fw-bold">
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
                            <template x-if="!isLoading && leaves.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(sp, index) in leaves?.data"
                                      :key="index">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="sp.sp_number"></td>
                                    <td x-text="sp.date"></td>
                                    <td x-text="sp.punished_by"></td>
                                    <td>
                                        <a :href="`/manage-users/sp/export-pdf/${sp.id}`" class="btn btn-danger btn-sm">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in leaves.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="paginate(pagination.url)"
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
        function ownLeavesData() {
            return {
                isLoading: false,
                leaves: [],
                startIndex: 0,
                async init() {
                    await this.getOwnLeaves();
                },
                async getOwnLeaves() {
                    try {
                        const resp = await axios.get('/utility/user-profile/leaves/data');
                        this.leaves = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async paginate(url) {
                    try {
                        if (url) {
                            this.leaves = [];
                            this.isLoading = true;
                            const resp = await axios.get(url);
                            this.leaves = resp.data;
                        }
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
