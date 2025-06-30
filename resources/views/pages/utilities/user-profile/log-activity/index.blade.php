@extends('layouts.template')
@section('page-title', 'Aktifitas')
@section('content')
    @include('pages.utilities.user-profile.partials.header')
    <div x-data="logActivityData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Event</th>
                                <th class="min-w-125px">Deskripsi</th>
                                <th class="min-w-125px">Sebelum</th>
                                <th class="min-w-125px">Sesudah</th>
                                <th class="min-w-125px">Waktu</th>
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
                            <template x-if="!isLoading && logActivity.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(log, index) in logActivity?.data" :key="index">
                                <tbody class="fw-bold">
                                <tr class="text-center">
                                    <td x-text="log.causer"></td>
                                    <td x-text="log.event"></td>
                                    <td x-text="log.description"></td>
                                    <td>
                                        <template x-if="log.before">
                                            <div x-data="{ expanded: false }">
                                                <div class="row">
                                                    <template x-for="[index, value] in Object.entries(log.before)"
                                                              :key="index">

                                                        <div class="col-md-12">
                                                            <div
                                                                x-show="expanded || sliceString(log.before).length <= 100"
                                                                x-transition x-cloak>
                                                                <p x-text="`${index} : ${value}`"></p>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-show="!expanded && sliceString(log.before).length > 100"
                                                         x-transition x-cloak>
                                                        <p id="slice-string"
                                                           x-html="sliceString(log.before)"></p>
                                                    </div>
                                                </div>

                                                <template x-if="Object.entries(log.before).length > 4">
                                                    <button type="button" @click="expanded = !expanded"
                                                            class="btn btn-link text-primary btn-sm ps-0 m-0 p-0">
                                                        <span x-text="expanded ? 'Sembunyikan' : 'Selengkapnya'"></span>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                    </td>
                                    <td class="text-start">
                                        <template x-if="log.after">
                                            <div x-data="{ expanded: false }">
                                                <div class="row">
                                                    <template x-for="[index, value] in Object.entries(log.after)"
                                                              :key="index">

                                                        <div class="col-md-12">
                                                            <div
                                                                x-show="expanded || sliceString(log.after).length <= 100"
                                                                x-transition x-cloak>
                                                                <p x-text="`${index} : ${value}`"></p>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-show="!expanded && sliceString(log.after).length > 100"
                                                         x-transition x-cloak>
                                                        <p id="slice-string"
                                                           x-html="sliceString(log.after)"></p>
                                                    </div>
                                                </div>

                                                <template x-if="Object.entries(log.after).length > 4">
                                                    <button type="button" @click="expanded = !expanded"
                                                            class="btn btn-link text-primary btn-sm ps-0 m-0 p-0">
                                                        <span x-text="expanded ? 'Sembunyikan' : 'Selengkapnya'"></span>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                    </td>
                                    <td x-text="log.created_at"></td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in logActivity.links">
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
        function logActivityData() {
            return {
                logActivity: [],
                isLoading: false,
                async init() {
                    await this.getActivityLog();
                }, async getActivityLog() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/utility/user-profile/activity-log/data`);
                        this.logActivity = resp.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginate(url) {
                    try {
                        if (url) {
                            this.logActivity = [];
                            this.isLoading = true;
                            const resp = await axios.get(url);
                            this.logActivity = resp.data;
                            this.startIndex = this.logActivity.from
                        }
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                sliceString(value) {
                    let str = '';
                    for (const [p, val] of Object.entries(value)) {
                        str += `<p>${p} : ${val}</p>`;
                    }

                    return str.slice(0, 100) + '...'
                }
            }
        }
    </script>
@endpush
