@extends('layouts.template')
@section('page-title', 'Informasi Identitas')
@section('content')
    @include('pages.utilities.user-profile.partials.header')
    <div x-data="spData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">No</th>
                                <th class="min-w-125px">SP aktif</th>
                                <th class="min-w-125px">Masa berlaku</th>
                                <th class="min-w-125px">Yg Memberi Sanksi</th>
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
                            <template x-if="!isLoading && spList.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(sp, index) in spList?.data"
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
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function spData() {
            return {
                isLoading: false,
                spList: [],
                startIndex: null,
                async init() {
                    const resp = await axios.get('/utility/user-profile/sp/data');
                    this.spList = resp.data;
                    this.startIndex = this.spList.from
                },
                async nextPage() {
                    if (this.spList.next_page_url) {
                        const resp = await axios.get(`${this.spList.next_page_url}`);
                        this.spList = resp.data
                        this.startIndex = this.spList.from
                    }
                },
                async previousPage() {
                    if (this.spList.prev_page_url) {
                        const resp = await axios.get(`${this.spList.prev_page_url}`);
                        this.spList = resp.data
                        this.startIndex = this.spList.from
                    }
                },
            }
        }
    </script>
@endpush
