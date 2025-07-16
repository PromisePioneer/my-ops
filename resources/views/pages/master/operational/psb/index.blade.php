@extends('layouts.template')
@section('page-title', 'Data PSB')
@section('content')
    <div x-data="psbData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.master.operational.psb.form')
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
                            @can('Tambah Data PSB')
                                <button type="button" class="btn btn-light-primary btn-sm" @click="add()"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-psb">
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-bordered text-center">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Tgl. Penarikan</th>
                                <th class="min-w-125px">Tgl Daftar</th>
                                <th class="min-w-125px">Tgl Aktif</th>
                                <th class="min-w-125px">Ditarik Oleh</th>
                                <th class="min-w-125px">Jumlah Penarik</th>
                                <th class="min-w-125px">Nama Pelanggan</th>
                                <th class="min-w-125px">Area</th>
                                <template x-if="Number(editPermission) === 1">
                                    <th class="min-w-125px">Actions</th>
                                </template>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">
                                                    Loading...
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && psb.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="psb in psb?.data" :key="psb.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="psb.id"
                                                   :id="'checkbox-' + psb.id"/>
                                        </div>
                                    </td>
                                    <td x-text="psb.date"></td>
                                    <td x-text="psb.registration_date"></td>
                                    <td x-text="psb.active_date"></td>
                                    <td>

                                        <ol>
                                            <template x-for="user in psb.area.area_has_user">
                                                <li x-text="user.user.name"></li>
                                            </template>
                                        </ol>

                                    </td>
                                    <td x-text="`${psb.area.area_has_user.length} Orang`"></td>
                                    <td x-text="psb.customer_name"></td>
                                    <td x-text="`${psb.area.name} - ${psb.area.branch.name}`"></td>
                                    <td>
                                        <template x-if="(editPermission) === 1">
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-psb" @click="edit(psb.id)">
                                                <i class="ki-duotone ki-pencil fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in psb.links">
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
    <script defer>
        $('.date').flatpickr();

        function psbData() {
            return {
                createPermission: "{{ request()->user()->can('Tambah Data PSB') }}",
                editPermission: "{{ request()->user()->can('Ubah Data PSB') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data PSB') }}",
                psb: [],
                buttonLoading: false,
                isLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                modalOpen: false,
                modalForm: new bootstrap.Modal(document.getElementById('modal-psb')),
                form: document.getElementById('form-psb'),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getPsbData();
                    await this.getAreaData();
                },
                async getPsbData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/operational/psb/data');
                        this.psb = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getAreaData() {
                    $(".areas-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Area",
                        ajax: {
                            url: '/operational-master-data/psb/area/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/operational-master-data/psb/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.psb = response.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginate(url) {
                    try {
                        if (url) {
                            this.psb = [];
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search
                                }
                            });
                            this.psb = resp.data
                        }
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
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
                    this.selectedCheckBox.shift();
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
                async save(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/operational-master-data/psb', new FormData(this.form))
                        } else {
                            await axios.post(`/operational-master-data/psb/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        this.modalForm.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/operational-master-data/psb/${id}`);
                    this.editVal = resp.data;
                },
                add() {
                    this.editVal = '';
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/operational-master-data/psb/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async successResponse() {
                    await showAlert('success', 'Data berhasil disimpan')
                    this.form.reset();
                    this.modalForm.hide();
                    const resp = await axios.get(`${this.psb.path}?page=${this.psb.current_page}`);
                    this.psb = resp.data
                }
            }
        }
    </script>
@endpush
