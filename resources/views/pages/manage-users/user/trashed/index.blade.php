@extends('layouts.template')
@section('page-title', 'Karyawan tidak aktif')
@section('breadcrumbs', 'Manajemen Karyawan - Data Karyawan - Karyawan Tidak Aktif')
@section('content')
    <div class="d-flex flex-column flex-xl-row" x-data="trashedUserData()">
        <div class="flex-lg-row-fluid ms-lg-10">
            <div class="card card-flush mb-6 mb-xl-9">
                <div class="card-header pt-5">
                    <div class="card-title">
                        <a href="{{ url('/manage-users/users') }}" class="btn btn-light-danger btn-sm">
                            <x-icons.back/>
                            Kembali
                        </a>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex align-items-center position-relative my-1"
                             data-kt-view-roles-table-toolbar="base">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viemanagewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="17.0365" y="15.1223"
                                                                      width="8.15546" height="2" rx="1"
                                                                      transform="rotate(45 17.0365 15.1223)"
                                                                      fill="black"></rect>
																<path
                                                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                                    fill="black"></path>
															</svg>
														</span>
                            <input type="text" class="form-control form-control-solid w-250px ps-15"
                                   x-model="search" @input.debounce="searchData()" placeholder="Cari...">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="col-12 ">
                        <form id="form-restore" @submit.prevent="restore()">
                            <input type="hidden" :name="`id[]`"
                                   :value="selectedCheckBox.filter((val) => val !== 'on')">
                            <button type="submit" class="btn btn-light-info btn-sm mt-5"
                                    x-show="selectedCheckBox.length > 0"
                                    x-transition x-cloak>
                                <x-icons.unarchive/>
                                Hapus
                            </button>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6"
                               id="kt_roles_view_table">
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
                                <th class="min-w-125px">NIK & Email</th>
                                <th class="min-w-125px">Karyawan</th>
                                <th class="min-w-125px">Tanggal Masuk</th>
                                <th class="min-w-125px">Terakhir Login</th>
                                <template
                                    x-if="Number(editPermission === 1) || Number(activationPermission) === 1">
                                    <th class="text-center min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                        aria-label="Actions" style="width: 135.25px;">
                                        Actions
                                    </th>
                                </template>
                            </tr>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold text-gray-600">
                                <tr>
                                    <td colspan="7">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && users.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="7">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="user in users.data" :key="user.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="user.id"
                                                   :id="'checkbox-' + user.id"
                                                   :disabled="Number(deletePermission) !== 1"/>
                                        </div>
                                    </td>
                                    <td>
                                        <p x-text="user.nik"></p>
                                        <p x-text="user.email"></p>
                                        <p>
                                            ID Absen : <span class="badge badge-light-info"
                                                             x-text="user.absent_id"></span>
                                        </p>
                                    </td>
                                    <td class="d-flex align-items-center">
                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                            <a href="#">
                                                <div class="symbol-label">
                                                    <a href="#" @click="openImage(user.profile_pic)">
                                                        <img :src="getImageURL(user.profile_pic ?? null)"
                                                             alt="Image" class="w-100">
                                                    </a>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a :href="Number(viewDetailPermission) === 1 ? `/manage-users/users/detail/${user.id}` : '#'"
                                               class="text-gray-800 text-hover-primary mb-1">
                                                <span x-text="user.name"></span>
                                            </a>
                                            <span class="badge badge-light-info fw-bolder fs-8"
                                                  x-text="user.roles ?? ''">
                                                    </span>
                                        </div>
                                    </td>
                                    <td class="text-center" x-text="user.join_date"></td>
                                    <td x-text="user.last_login"></td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <div class="text-center mt-10">
                        <div class="col-sm-12  d-flex align-items-center justify-content-end">
                            <template x-for="pagination in users.links">
                                <ul class="pagination">
                                    <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                        <button
                                            class="page-link"
                                            @click="paginate(pagination.url)"
                                            x-html="pagination.label"></button>
                                    </li>
                                </ul>
                            </template>
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
        function trashedUserData() {
            return {
                users: [],
                isLoading: false,
                search: '',
                formRestore: document.getElementById('form-restore'),
                async init() {
                    await this.getTrashedData();
                },
                async paginate(url) {
                    try {
                        if (url) {
                            this.users = [];
                            this.isLoading = true;
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                    month: document.getElementById('month')?.value,
                                    year: document.getElementById('year')?.value,
                                    branch_id: $(".main-branches-select2")?.val(),
                                    company_id: $(".companies-select2")?.val(),
                                    active: document.getElementById('active')?.value,
                                    role_id: $('#role_id').val(),
                                }
                            });
                            this.users = resp.data
                        }
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getTrashedData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/users/trashed/data');
                        this.users = resp.data;
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
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/users/trashed/search', {
                            params: {
                                search: this.search
                            }
                        });
                        this.users = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
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
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        return "{{ asset('') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
                async restore() {
                    showConfirmModal("Anda yakin?", "Data akan dikembalikan ke karyawan aktif, pastikan tidak ada ID absen & email yang duplikat.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/manage-users/users/trashed/restore`, new FormData(this.formRestore));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
            }
        }
    </script>
@endpush
