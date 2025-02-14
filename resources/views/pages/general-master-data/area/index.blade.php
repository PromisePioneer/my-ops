@extends('layouts.template')
@section('page-title', 'Master Umum - Area Management')
@section('content')
    <div x-data="areaData()">
        @include('pages.general-master-data.area.form')
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <form id="form-filter" @submit.prevent="filter()">
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600">
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid branches-select2"
                                            name="branch_id_filter" id="branch_id_filter">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pt-4 text-end">
                            <button type="submit" class="btn btn-light btn-active-primary btn-sm">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="flex-lg-row-fluid ms-lg-10">
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
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                                    @can('Tambah Data Area')
                                        <button type="button" class="btn btn-light-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-area">
                                            <i class="ki-duotone ki-message-add fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i> Tambah
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="col-12 ">
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
                        </div>
                        <div class="py-5">
                            <div class="table-responsive">
                                <table class="table align-middle fs-6 gy-5 table-bordered" id="kt_table_users">
                                    <thead>
                                    <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox"
                                                       @click="toggleAllCheckBox()"
                                                       :disabled="Number(deletePermission) !== 1">
                                            </div>
                                        </th>
                                        <th class="min-w-125px">Departemen</th>
                                        <th class="min-w-125px">Nama Area</th>
                                        <th class="min-w-125px">Total Karyawan</th>
                                        <th class="min-w-125px">Actions</th>
                                    </thead>
                                    <template x-if="isLoading">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <div style="text-align: center;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden"></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-if="!isLoading && areas.data?.length === 0">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="area in areas?.data" :key="area.id">
                                        <tbody class="fw-bold text-center">
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <input class="form-check-input" type="checkbox" :value="area.id"
                                                           :id="'checkbox-' + area.id"
                                                           :disabled="Number(deletePermission) !== 1"
                                                    />
                                                </div>
                                            </td>
                                            <td x-text="area.department_name"></td>
                                            <td>
                                                <a :href="`/general-master-data/area/detail/${area.id}`"
                                                   x-text="area.area_name" class="text-uppercase"></a>
                                            </td>
                                            <td x-text="`${area.total_user} Karyawan`"></td>
                                            <td>
                                                <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modal-area" @click="edit(area.id)">
                                                    <i class="ki-duotone ki-pencil fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                </table>
                            </div>
                            <ul class="pagination float-end mb-4 mt-4">
                                <template x-for="pagination in areas.links">
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
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    @include('pages.general-master-data.area.script')
@endpush
