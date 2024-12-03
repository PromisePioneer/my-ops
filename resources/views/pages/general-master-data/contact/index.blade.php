@extends('layouts.template')
@section('page-title', 'Master Umum - Data Kontak')
@section('content')

    <div x-data="contactData()">
        @include('pages.general-master-data.contact.modal.create')
        @include('pages.general-master-data.contact.modal.edit')
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
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <template x-if="Number(createPermission) === 1">
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#contact-create">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Tambah
                            </button>
                        </template>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <form id="deleteForm" @submit.prevent="destroy()">
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
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3"
                                         @click="toggleAllCheckBox()">
                                        <input class="form-check-input" type="checkbox" :disabled="
                                        Number(deletePermission) !== 1"
                                        />
                                    </div>
                                </th>
                                <th class="min-w-125px">PIC</th>
                                <th class="min-w-125px">Nama Perusahaan</th>
                                <th class="min-w-125px">No. Handphone</th>
                                <template x-if="Number(editPermission) === 1">
                                    <th class="min-w-125px">Actions</th>
                                </template>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class=" fw-bold">
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
                            <template x-if="!isLoading && contacts.data?.length === 0">
                                <tbody class=" fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(contact,index) in contacts?.data" :key="index">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="contact.id"
                                                   :id="'checkbox-' + contact.id" :disabled="Number(deletePermission) !== 1"/>
                                        </div>
                                    </td>
                                    <td x-text="contact.pic_name"></td>
                                    <td x-text="`${contact.company_code} - ${contact.company_name}`"></td>
                                    <td x-text="contact.phone_number"></td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#contact-edit" @click="edit(contact.id)">
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
                        <template x-for="pagination in contacts.links">
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
    <script defer>
        function contactData() {
            return {
                createPermission: "{{  request()->user()->can('Tambah Data Kontak')  }}",
                editPermission: "{{  request()->user()->can('Edit Data Kontak')  }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Kontak') }}",
                contacts: [],
                isLoading: true,
                buttonLoading: false,
                selectAll: false,
                selectedCheckBox: [],
                singleChecked: false,
                search: '',
                editVal: '',
                formCreate: document.getElementById('contactFormCreate'),
                modalCreate: new bootstrap.Modal(document.getElementById('contact-create')),
                formEdit: document.getElementById('contactFormEdit'),
                modalEdit: new bootstrap.Modal(document.getElementById('contact-edit')),
                deleteForm: document.getElementById('deleteForm'),
                async init() {
                    await this.contactData();
                    await this.filterByBranch();
                },
                async searchData() {
                    try {
                        this.contacts = await axios.get('/general-master-data/contact/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.contacts = resp.data
                    }
                },
                toggleAllCheckBox() {
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
                async saveContact() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/general-master-data/contact`, new FormData(this.formCreate))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreate.reset();
                        this.modalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/general-master-data/contact/edit/${id}`);
                    this.editVal = resp.data;
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/general-master-data/contact/update/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formEdit.reset();
                        this.modalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false
                    }
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/general-master-data/contact/destroy`, new FormData(this.deleteForm));
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
                        placeholder: "Pilih Cabang",
                        allowClear: true,
                        ajax: {
                            url: '/general-master-data/contact/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                    $(".filter-branch-select2").on('change', async function (e) {
                        const selectedBranch = $(this).select2('data')[0];
                        const response = await axios.get(`/general-master-data/contact/filter/branch/data/${selectedBranch.id}`);
                        self.contacts = response.data;
                    });
                },
                async contactData() {
                    const resp = await axios.get('/general-master-data/contact/data')
                    this.contacts = resp.data
                    this.isLoading = false;
                }
            }
        }
    </script>
@endpush
