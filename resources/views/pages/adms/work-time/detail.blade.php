@extends('layouts.template')
@section('page-title', 'Pengaturan Shift ' . $workTime->name)
@section('content')

    <div x-data="workTimeDetail()">
        @include('pages.adms.work-time.modal.assign-user')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex my-1 align-items-center">
                        <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                            <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                                   class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                        </div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modal-assign-user">
                        <i class="ki-duotone ki-message-add fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Tambah
                    </button>
                </div>

            </div>
            <div class="card-body py-3">
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
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">NIK</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jabatan</th>
                                <th class="min-w-125px">ID Absen</th>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
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
                            <template x-if="!isLoading && users.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(user, index) in users?.data" :key="user.id">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="user.id"
                                                   :id="'checkbox-' + user.id"/>
                                        </div>
                                    </td>
                                    <td x-text="user.user.nip"></td>
                                    <td>
                                        <a :href="`/manage-users/users/detail/${user.user_id}`"
                                           x-text="user.user.name"></a>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger text-white"
                                              x-text="user.user.roles[0]?.name ?? 'Tidak Ada'"></span>
                                    </td>
                                    <td x-text="user.user.absent_id"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ url('/adms/work-time') }}" class="btn btn-danger btn-sm">Kembali</a>
                        <ul class="pagination">
                            <template x-for="pagination in users.links">
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
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function workTimeDetail() {
            return {
                buttonLoading: false,
                isLoading: false,
                startIndex: null,
                users: [],
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                workTimeId: "{{ $workTime->id }}",
                modalAssignUser: new bootstrap.Modal(document.getElementById('modal-assign-user')),
                formAssignUser: document.getElementById('form-assign-user'),
                formDelete: document.getElementById('form-delete'),
                search: '',
                async init() {
                    await this.getRelatedUsersForThisWorkTime();
                    await this.getUserData();
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.branches = resp.data
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
                async getRelatedUsersForThisWorkTime() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/adms/work-time/detail/data/${this.workTimeId}`);
                        this.users = resp.data;
                        this.startIndex = this.users.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async assignShift() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/adms/work-time/assign-work-time/${this.workTimeId}`, new FormData(this.formAssignUser))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formAssignUser.reset();
                        this.modalAssignUser.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getUserData() {
                    $(".user-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Karyawan",
                        ajax: {
                            url: '/adms/work-time/user/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async searchData() {
                    try {
                        this.users = await axios.get(`/adms/work-time/detail/data/search/${this.workTimeId}`, {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/adms/work-time/detail/data/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                }
            }
        }
    </script>
@endpush