@extends('layouts.template')
@section('page-title', 'Arsip Kontak')
@section('breadcrumbs', 'Master Umum - Kontak - Arsip')
@section('content')

    <div x-data="contactTrashedData()">
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
            </div>
            <div class="card-body py-3">
                <div class="col-12 ">
                    <form id="restore-form" @submit.prevent="restore()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox.filter((val) => val !== 'on')">
                        <button type="submit" class="btn btn-light-info btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <x-icons.restore/>
                            Pulihkan
                        </button>
                    </form>
                    <form id="force-delete-form" @submit.prevent="forceDelete()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox.filter((val) => val !== 'on')">
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-bordered">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Tipe</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <template x-if="isLoading">
                                <x-table.loading colspan="4"/>
                            </template>
                            <template x-if="!isLoading && contacts.data?.length === 0">
                                <x-table.empty colspan="5"/>
                            </template>
                            <template x-for="contact in contacts?.data" :key="contact.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="contact.id"
                                                   :id="'checkbox-' + contact.id"/>
                                        </div>
                                    </td>
                                    <td x-text="contact.name"></td>
                                    <td x-text="contact.type"></td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#contact-modal" @click="edit(contact.id)">
                                            <x-icons.edit/>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ url('/master/common/contact/') }}" class="btn btn-light-danger btn-sm">
                            <x-icons.back/>
                            Kembali
                        </a>

                        <ul class="pagination float-end mb-4 mt-4">
                            <template x-for="pagination in contacts.links">
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
    @include('components.toast')
@endsection
@push('script')
    <script>
        function contactTrashedData() {
            return {
                contacts: [],
                isLoading: false,
                buttonLoading: false,
                selectAll: false,
                selectedCheckBox: [],
                contactType: null,
                singleChecked: false,
                search: '',
                restoreForm: document.getElementById('restore-form'),
                forceDeleteForm: document.getElementById('force-delete-form'),
                async init() {
                    await this.contactData();
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/master/common/contact/archives/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.contacts = resp.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                },
                async paginate(url) {
                    if (url) {
                        try {
                            this.contacts = [];
                            this.isLoading = true;
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search
                                }
                            });
                            this.contacts = resp.data
                        } catch (e) {
                            console.log(e)
                        } finally {
                            this.isLoading = false
                        }
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
                async forceDelete() {
                    showConfirmModal("Anda yakin?", "Data akan hilang secara permanen.", "Ya, Hapus Permanen!", async () => {
                        try {
                            await axios.post(`/master/common/contact/archives/force-delete`, new FormData(this.forceDeleteForm));
                            await showAlert('success', 'Data sukses dihapus permanen');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            console.log(error)
                            await showAlert('error', error.response.data.message);
                        }
                    });
                },
                async restore() {
                    showConfirmModal("Anda yakin?", "Data akan dipulihkan.", "Ya, pulihkan!", async () => {
                        try {
                            await axios.post(`/master/common/contact/archives/restore`, new FormData(this.restoreForm));
                            await showAlert('success', 'Data sukses dipulihkan');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },

                async contactData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/common/contact/archives/data')
                        this.contacts = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
