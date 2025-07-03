@extends('layouts.template')
@section('page-title', 'Riwayat Aktifitas')
@section('breadcrumbs', 'Utilitas - Riwayat Aktifitas')
@section('content')
    <div x-data="activityLogData()">
        @include('pages.utilities.activity-log.filter')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header">
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
                    <button id="kt_drawer_example_basic_button" class="btn btn-light-info btn-sm">
                        <i class="ki-duotone ki-filter-square">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Filter
                    </button>
                </div>
            </div>
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
                            <template x-if="!isLoading && activityLog.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(log, index) in activityLog?.data" :key="index">
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
                                                                <p class="text-start"
                                                                   x-text="`${index} : ${value}`"></p>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-show="!expanded && sliceString(log.before).length > 100"
                                                         x-transition x-cloak>
                                                        <p id="slice-string" class="text-start"
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
                                        <template x-if="!log.before">
                                            <span class="badge bg-light-danger text-danger">Kosong</span>
                                        </template>
                                    </td>
                                    <td>
                                        <template x-if="log.after">
                                            <div x-data="{ expanded: false }">
                                                <div class="row">
                                                    <template x-for="[index, value] in Object.entries(log.after)"
                                                              :key="index">

                                                        <div class="col-md-12">
                                                            <div
                                                                x-show="expanded || sliceString(log.after).length <= 100"
                                                                x-transition x-cloak>
                                                                <p class="text-start"
                                                                   x-text="`${index} : ${value}`"></p>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-show="!expanded && sliceString(log.after).length > 100"
                                                         x-transition x-cloak>
                                                        <p id="slice-string" class="text-start"
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
                                        <template x-if="!log.after">
                                            <span class="badge bg-light-danger text-danger text-center">Kosong</span>
                                        </template>
                                    </td>
                                    <td x-text="log.created_at"></td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in activityLog.links">
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
        @include('components.select2.script')
    </div>
@endsection
@push('script')
    <script>

        function activityLogData() {
            return {
                isLoading: false,
                activityLog: [],
                search: '',
                startDate: "{{ $startDate }}",
                endDate: "{{ $endDate }}",
                date: document.getElementById('date')?.value,
                async init() {
                    flatpickr(".date-picker", {
                        mode: "range",
                        dateFormat: "d/m/Y",
                        defaultDate: [this.startDate, this.endDate],
                    });

                    await this.getActivityLog();
                    await select2('.main-branches-select2', 'Pilih Cabang', '/select2/main-branches-data');
                    await select2('.roles-select2', 'Pilih Jabatan', '/select2/roles-data');
                },
                async getActivityLog() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/utility/activity-log/data');
                        this.activityLog = resp.data;
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/utility/activity-log/search', {
                            params: {
                                search: this.search
                            }
                        })

                        this.activityLog = resp.data
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginate(url) {
                    try {
                        if (url) {
                            this.activityLog = [];
                            this.isLoading = true;
                            const resp = await axios.get(url);
                            this.activityLog = resp.data;
                            this.startIndex = this.activityLog.from
                        }
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/utility/activity-log/filter', {
                            params: {
                                start_date: this.formatDate(this.date.split('to').map(part => part.trim())[0]),
                                end_date: this.formatDate(this.date.split('to').map(part => part.trim())[1]),
                                branch_id: $('#branch_id').val(),
                                role_id: $('#role_id').val(),
                            }
                        });
                        this.activityLog = resp.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                formatDate(dateStr) {
                    if (!dateStr) {
                        return '';
                    }
                    const [day, month, year] = dateStr.split('/');
                    return `${year}-${month}-${day}`;
                },
                sliceString(value) {
                    const newObj = {};
                    for (const key in value) {
                        if (value[key] !== null && value[key] !== undefined) {
                            newObj[key] = value[key];
                        }
                    }

                    let str = '';
                    for (const [p, val] of Object.entries(newObj)) {
                        str += `<p>${p} : ${val}</p>`;
                    }

                    return str.slice(0, 100) + '...'
                }
            }
        }
    </script>
@endpush
