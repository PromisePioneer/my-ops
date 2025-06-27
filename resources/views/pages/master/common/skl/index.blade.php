@extends('layouts.template')
@section('page-title', 'Syarat Ketentuan Layanan')
@section('breadcrumbs', 'Master Umum - Syarat Ketentuan Layanan')
@section('content')
    <div x-data="sklData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.master.common.skl.form')
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
                            @can('Tambah Data SKL')
                                <button type="button" class="btn btn-light-primary btn-sm me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-skl">
                                    <x-icons.add-item/>
                                    Tambah
                                </button>
                            @endcan
                            @can('Lihat Data Arsip SKL')
                                <a href="{{ url('/master/common/skl/archives') }}"
                                   class="btn btn-secondary btn-sm">
                                    <x-icons.archived/>
                                    Arsip
                                </a>
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
                            <x-icons.trash/>
                            Hapus
                        </button>
                    </form>
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox"
                                               @click="toggleAllCheckBox()" :disabled="Number(deletePermission) !== 1">
                                    </div>
                                </th>
                                <th class="min-w-125px">Nama</th>
                                <template x-if="Number(editPermission) === 1">
                                    <th class="min-w-125px">Actions</th>
                                </template>
                            </thead>
                            <template x-if="isLoading">
                                <x-table.loading colspan="2"/>
                            </template>
                            <template x-if="!isLoading && skl.data?.length === 0">
                                <x-table.loading colspan="2"/>
                                <template x-for="service in skl?.data" :key="service.id">
                                    <tbody class="fw-bold text-center">
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                 @click="selectCheckBox($event)">
                                                <input class="form-check-input" type="checkbox" :value="service.id"
                                                       :id="'checkbox-' + service.id"
                                                       :disabled="Number(deletePermission) !== 1"/>
                                            </div>
                                        </td>
                                        <td x-text="service.name"></td>
                                        <template x-if="Number(editPermission) === 1">
                                            <td>
                                                <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modal-skl" @click="edit(service.id)">
                                                    <x-icons.edit/>
                                                </button>
                                            </td>
                                        </template>
                                    </tr>
                                    </tbody>
                                </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in skl.links">
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
        function sklData() {
            return {
                editPermission: "{{ request()->user()->can('Edit Data SKL') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data SKL') }}",
                skl: [],
                isLoading: false,
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                form: document.getElementById('form-skl'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-skl')),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getSklData();
                },
                async getSklData() {
                    this.isLoading = false;
                    try {
                        const resp = await axios.get('/master/common/skl/data');
                        this.skl = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/master/common/skl/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.skl = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.skl = resp.data
                    }
                },
                toggleAllCheckBox() {
                    if (Number(this.deletePermission) === 1) {
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
                    }
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
                async edit(id) {
                    const resp = await axios.get(`/master/common/skl/${id}`);
                    console.log(resp);
                    this.editVal = resp.data;
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/common/skl/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async saveSKL(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/master/common/skl', new FormData(this.form))
                        } else {
                            await axios.post(`/master/common/skl/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        this.modalForm.hide();
                        await this.init();
                        this.selectedCheckBox = [];
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
