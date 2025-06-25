@extends('layouts.template')
@section('page-title', 'Informasi Identitas')
@section('content')
    @include('pages.utilities.user-profile.partials.header')

    <div x-data="ownLeavesData">
        @include('pages.utilities.user-profile.leaves.form')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h1>Sisa Cuti : <span x-text="`${leavesLeft}`"></span></h1>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        @can('Tambah Data Manajemen Cuti')
                            <button class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-form"
                                    {{-- @click="add()" --}}
                            >
                                Tambah
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Alasan Cuti</th>
                                <th class="min-w-125px">File</th>
                                <th class="min-w-125px">TGL Pengajuan</th>
                                <th class="min-w-125px">Action</th>
                            </thead>
                            <tbody class="fw-bold">
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
                            <template x-for="(leave, index) in leaves?.data"
                                      :key="index">
                                <tr class="text-center">
                                    <td>
                                        <div class="mb-4">
                                            <a href="#"
                                               x-text="leave.user_name"></a>
                                        </div>
                                        <template x-if="leave.confirmation_status === 'Diproses'">
                                            <div>
                                                <p class="badge bg-light-warning text-warning fs-7"
                                                   x-text="`${leave.leaves_status} (Diproses)`"></p>
                                            </div>
                                        </template>
                                        <template x-if="leave.confirmation_status === 'Diterima'">
                                            <p class="badge bg-light-success text-success fs-7"
                                               x-text="`${leave.leaves_status} (Diterima)`"></p>
                                        </template>
                                        <template x-if="leave.confirmation_status === 'Ditolak'">
                                            <div>
                                                <p class="badge bg-light-danger text-danger fs-7"
                                                   x-text="`${leave.leaves_status} (Ditolak)`"></p>
                                                <span class="badge bg-light-danger text-danger fs-7"
                                                      x-text="`Alasan : ${leave.confirmation_reason}`"></span>
                                            </div>
                                        </template>
                                    </td>
                                    <td>
                                        <template
                                            x-if="leave.start_date === null && leave.end_date === null && leave.important_leaves === 'Memenuhi Panggilan Instansi Pemerintah' && leave.confirmation_status === 'Diproses'">
                                            <span class="text-danger">Tanggal akan ditentukan jika surat resmi terbukti benar dan sesuai.</span>
                                        </template>
                                        <template
                                            x-if="leave.start_date === null && leave.end_date === null && leave.important_leaves === 'Mendapat Musibah'  && leave.confirmation_status === 'Diproses'">
                                                    <span class="text-danger">
                                                        Tanggal akan ditetapkan sesuai dengan pertimbangan perusahaan.
                                                    </span>
                                        </template>
                                        <template x-if="leave.start_date && leave.end_date">
                                            <span x-text="`${leave.start_date} - ${leave.end_date}`"></span>
                                        </template>
                                    </td>
                                    <td x-text="leave.reason ?? leave.important_leaves"></td>
                                    <td>
                                        <a href="#" @click="openImage(leave.attachment)">
                                            <img :src="getImageURL(leave.attachment)" height="100" class="img-fluid"/>
                                        </a>
                                    </td>
                                    <td x-text="leave.created_at"></td>
                                    <template
                                        x-if="leave.confirmation_status === 'Diproses' && Number(editPermission) === 1">
                                        <td>
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-form" @click="edit(leave.id)">
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
    @include('components.toast')
@endsection
@push('script')
    <script>
        $('.date').flatpickr();

        function ownLeavesData() {
            return {
                editPermission: "{{ request()->user()->can('Edit Data Manajemen Cuti') }}",
                buttonLoading: false,
                isLoading: false,
                leaves: [],
                startIndex: 0,
                leavesStatus: null,
                modalForm: new bootstrap.Modal(document.getElementById('modal-form')),
                editVal: '',
                search: '',
                form: document.getElementById('form'),
                leavesLeft: 0,
                confirmationStatus: null,
                importantLeaveType: null,
                userId: "{{ Auth::id() }}",
                sickLetter: null,
                async init() {
                    await this.getOwnLeaves();
                    await this.getLeavesLeft();
                },
                add() {
                    this.form.reset();
                    this.editVal = '';
                    this.leavesStatus = this.editVal.leaves_status;
                    this.importantLeaveType = this.editVal.important_leaves;
                },
                async getLeavesLeft() {
                    try {
                        const resp = await axios.get('/manage-users/leaves/leaves-left', {
                            params: {
                                user_id: this.userId
                            }
                        });
                        this.leavesLeft = resp.data;
                    } catch (e) {
                        console.log(e)
                    }
                },
                ifNotImportantLeave() {
                    if (this.leavesStatus !== 'Cuti Penting') {
                        this.importantLeaveType = null;
                    }
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
                async getOwnLeaves() {
                    try {
                        this.isLoading = true;
                        const resp = await axios.get('/utility/user-profile/leaves/data');
                        this.leaves = resp.data;
                        this.startIndex = this.leaves.from
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginate(url) {
                    try {
                        if (url) {
                            this.leaves = [];
                            this.isLoading = true;
                            const resp = await axios.get(url);
                            this.leaves = resp.data;
                            this.startIndex = this.leaves.from
                        }
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/manage-users/leaves/edit/${id}`);
                    this.editVal = resp.data;
                    this.leavesStatus = this.editVal.leaves_status;
                    this.importantLeaveType = this.editVal.important_leaves;
                },
                async save(id =null) {
                    this.buttonLoading = true;
                    try {
                        console.log(id);

                        if (!id) {
                            await axios.post('/manage-users/leaves/', new FormData(this.form))
                                .then(async () => {
                                    await this.successResponse();
                                })
                        } else {
                            await axios.post(`/manage-users/leaves/update/${id}`, new FormData(this.form))
                                .then(async () => {
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
                async successResponse() {
                    await showAlert('success', 'Data berhasil disimpan')
                    this.form.reset();
                    this.modalForm.hide();
                    const resp = await axios.get(`${this.leaves.path}?page=${this.leaves.current_page}`);
                    this.leaves = resp.data
                }
            }
        }
    </script>
@endpush
