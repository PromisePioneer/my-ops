@extends('layouts.template')
@section('page-title', 'Data Cuti Karyawan')
@section('content')
    <div x-data="leavesData()">
        @include('pages.manage-users.leaves.confirm')
        @include('pages.manage-users.leaves.form')
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column text-gray-600">
                            <div class="d-flex align-items-center py-2">
                                @can('Filter Data Manajemen Cuti Berdasarkan Cabang')
                                    <select class="form-select form-select-solid main-branches-select2"
                                            name="branch_id" id="branch_id">
                                    </select>
                                @endcan
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <select class="form-select-solid form-select" name="confirmation_status"
                                        id="confirmation_status" x-model="selectedConfirmationStatus">
                                    <option selected>Pilih Status</option>
                                    <option :value="`Diproses`">Diproses</option>
                                    <option :value="`Diterima`">Diterima</option>
                                    <option :value="`Ditolak`">Ditolak</option>
                                </select>
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <input type="number" name="year" id="year"
                                       class="form-control form-control-solid"
                                       placeholder="Filter Berdasarkan Tahun">
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <select class="form-select form-select-solid"
                                        name="month" id="month" data-control="select2"
                                        data-placeholder="Pilih Bulan" data-allow-clear="true">
                                    <option></option>
                                    <template x-for="month in months" :key="index">
                                        <option :value="month.number" x-text="month.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pt-4 text-end">
                        <button type="button" @click="filter()" class="btn btn-light btn-active-primary btn-sm">
                            Filter
                        </button>
                    </div>
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
                            <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                                <button class="btn btn-light-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-leaves"
                                >
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="col-12">
                            <form id="form-delete" @submit.prevent="destroy()">
                                <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                                @can('Tambah Data Manajemen Cuti')
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
                                @endcan
                            </form>
                        </div>
                        <div class="py-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 table-bordered"
                                       id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox"
                                                       @click="toggleAllCheckBox()">
                                            </div>
                                        </th>
                                        <th class="min-w-125px">Nama</th>
                                        <th class="min-w-125px">Tanggal</th>
                                        <th class="min-w-125px">Alasan Cuti</th>
                                        <th class="min-w-125px">Tipe</th>
                                        <th class="min-w-125px">Status</th>
                                        <th class="min-w-125px">File Sakit</th>
                                        <th class="min-w-125px">TGL Pengajuan</th>
                                        <th class="min-w-125px">Action</th>
                                    </thead>
                                    <tbody class=" fw-bold">
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
                                    <template x-for="(leave, index) in leaves?.data" :key="index">
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <input class="form-check-input" type="checkbox" :value="leave.id"
                                                           :id="'checkbox-' + leave.id"/>
                                                </div>
                                            </td>
                                            <td>
                                                <a :href="`${Number(viewDetailUserPermission) === 1 ? `/manage-users/users/detail/${leave.user_id}` : '#'}`"
                                                   x-text="leave.user_name"></a>
                                            </td>
                                            <td x-text="`${leave.start_date} - ${leave.end_date}`"></td>
                                            <td x-text="leave.reason ?? leave.important_leaves"></td>
                                            <td>
                                                <span x-text="leave.leaves_status"></span>
                                            </td>
                                            <td>
                                                <template x-if="leave.confirmation_status === 'Diproses'">
                                                    <span class="badge bg-warning">Diproses</span>
                                                </template>
                                                <template x-if="leave.confirmation_status === 'Diterima'">
                                                    <span class="badge bg-success">Diterima</span>
                                                </template>
                                                <template x-if="leave.confirmation_status === 'Ditolak'">
                                                    <span class="badge bg-danger">Ditolak</span>
                                                </template>
                                            </td>
                                            <td>
                                                <a href="#" @click="openImage(leave.sick_letter)">
                                                    <img :src="getImageURL(leave.sick_letter)" height="100"/>
                                                </a>
                                            </td>
                                            <td x-text="leave.created_at"></td>
                                            <template
                                                x-if="leave.confirmation_status === 'Diterima' || leave.confirmation_status === 'Ditolak'">
                                                <td>
                                                    <button class="btn btn-light-primary btn-sm" disabled>
                                                        <i class="ki-duotone ki-pencil fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </button>
                                                    <button class="btn btn-info btn-sm" disabled>
                                                        <i class="bi bi-gear-fill"></i>
                                                    </button>
                                                </td>
                                            </template>
                                            <template x-if="leave.confirmation_status === 'Diproses'">
                                                <td>
                                                    <template
                                                        x-if="Number(confirmPermission) === 1 && Number(userSessionId) !== leave.user_id">
                                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#modal-confirm"
                                                                @click="edit(leave.id)">
                                                            <i class="bi bi-gear-fill"></i>
                                                        </button>
                                                    </template>
                                                    <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#modal-leaves" @click="edit(leave.id)">
                                                        <i class="ki-duotone ki-pencil fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </button>
                                                </td>
                                            </template>
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
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        $('.date').flatpickr();

        function leavesData() {
            return {
                viewDetailUserPermission: "{{ request()->user( )->can('Lihat Detail Data Karyawan') }}",
                confirmPermission: "{{request()->user()->can('Konfirmasi Data Manajemen Cuti')}}",
                userSessionId: "{{ Auth::id() }}",
                modalForm: new bootstrap.Modal(document.getElementById('modal-leaves')),
                form: document.getElementById('form-leaves'),
                formDelete: document.getElementById('form-delete'),
                buttonLoading: false,
                isLoading: false,
                editVal: '',
                leavesStatus: false,
                leaves: [],
                search: '',
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                currentLoginId: "{{ Auth::id() }}",
                selectedConfirmationStatus: null,
                id: '',
                detailValue: '',
                modalConfirm: new bootstrap.Modal(document.getElementById('modal-confirm')),
                formConfirm: document.getElementById('form-confirm'),
                leavesLeft: 0,
                months: [],
                async init() {
                    this.getMonth();
                    await this.getMainBranches();
                    await this.getLeavesData();
                    await this.getUserData();
                    console.log(this.userSessionId)
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
                async getMainBranches() {
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/select2/main-branches-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
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
                async filter() {
                    console.log(this.selectedConfirmationStatus);
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/leaves/filter', {
                            params: {
                                search: this.search,
                                branch_id: document.getElementById('branch_id').value,
                                year: document.getElementById('year').value,
                                month: document.getElementById('month').value,
                                confirmation_status: this.selectedConfirmationStatus,
                            }
                        });
                        this.leaves = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/manage-users/leaves/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.leaves = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginate(url) {
                    try {
                        if (url) {
                            this.leaves = [];
                            this.isLoading = true;
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                    branch_id: document.getElementById('branch_id')?.value,
                                    year: document.getElementById('year')?.value,
                                    month: document.getElementById('month')?.value,
                                    confirmation_status: this.selectedConfirmationStatus,
                                }
                            });
                            this.leaves = resp.data
                        }
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async selectedUserData(id) {
                    const self = this;
                    const selectedUser = $('#selected-user');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-user/${id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedUser.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });

                    const resp = await axios.get('/manage-users/leaves/leaves-left', {
                        params: {
                            user_id: response.id
                        }
                    });
                    this.leavesLeft = resp.data;
                },
                async getUserData() {
                    const self = this;
                    $(".users-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Karyawan",
                        ajax: {
                            url: '/manage-users/leaves/users/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('change', async function (e) {
                        const userId = $(".users-select2").val()
                        const resp = await axios.get(`/manage-users/leaves/leaves-left`, {
                            params: {
                                user_id: userId
                            }
                        });

                        self.leavesLeft = resp.data
                    });
                },
                async save(id = null) {
                    this.buttonLoading = true;
                    try {

                        if (!id) {
                            await axios.post('/manage-users/leaves/', new FormData(this.form)).then(async () => {
                                await this.successResponse();
                            })
                        } else {
                            await axios.post(`/manage-users/leaves/update/${id}`, new FormData(this.form)).then(async () => {
                                await this.successResponse();
                            })
                        }
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/manage-users/leaves/edit/${id}`);
                    this.editVal = resp.data;
                    this.leavesStatus = this.editVal.leaves_status
                    await this.selectedUserData(this.editVal.id);
                },
                async confirm(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/leaves/${id}`, new FormData(this.formConfirm)).then(async () => {
                            await showAlert('success', 'Data berhasil disimpan')
                            this.formConfirm.reset();
                            this.modalConfirm.hide();
                            this.leavesLeft = 0;
                            const resp = await axios.get(`${this.leaves.path}?page=${this.leaves.current_page}`, {
                                params: {
                                    search: this.search,
                                    branch_id: document.getElementById('branch_id').value,
                                    year: document.getElementById('year').value,
                                    month: document.getElementById('month').value,
                                    confirmation_status: this.selectedConfirmationStatus,
                                }
                            });
                            this.leaves = resp.data
                        })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getLeavesData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/leaves/data');
                        this.leaves = resp.data
                        this.startIndex = resp.data.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/manage-users/leaves/destroy`, new FormData(this.formDelete)).then(async () => {
                                await showAlert('success', 'Data sukses dihapus');
                                this.leavesLeft = 0;
                                const resp = await axios.get(`${this.leaves.path}?page=${this.leaves.current_page}`, {
                                    params: {
                                        search: this.search,
                                        branch_id: document.getElementById('branch_id').value,
                                        year: document.getElementById('year').value,
                                        month: document.getElementById('month').value,
                                        confirmation_status: this.selectedConfirmationStatus,
                                    }
                                });
                                this.leaves = resp.data
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                getImageURL(imagePath) {
                    return imagePath ? "{{  Storage::url('') }}" + imagePath : '';
                },
                openImage(imagePath) {
                    const lightbox = new FsLightbox();
                    console.log(lightbox);
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        const image = "{{ asset('') }}" + placeholders
                        lightbox.props.sources = [image, image];
                        lightbox.open();
                    } else {
                        const image = "{{ Storage::url('') }}" + imagePath;
                        lightbox.props.sources = [image];
                        lightbox.open();
                    }
                },
                async successResponse() {
                    await showAlert('success', 'Data berhasil disimpan')
                    this.form.reset();
                    this.modalForm.hide();
                    this.leavesLeft = 0;
                    $('.users-select2').val('').trigger('change');
                    const resp = await axios.get(`${this.leaves.path}?page=${this.leaves.current_page}`, {
                        params: {
                            search: this.search,
                            branch_id: document.getElementById('branch_id').value,
                            year: document.getElementById('year').value,
                            month: document.getElementById('month').value,
                            confirmation_status: this.selectedConfirmationStatus,
                        }
                    });
                    this.leaves = resp.data
                },
            }
        }
    </script>
@endpush
