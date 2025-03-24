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
                                    @click="add()"
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
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">No</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Status (Cuti / Izin / Sakit)</th>
                                <th class="min-w-125px">Keterangan</th>
                                <th class="min-w-125px">Status Konfirmasi</th>
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
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="`${leave.start_date} - ${leave.end_date}`"></td>
                                    <td x-text="leave.leaves_status"></td>
                                    <td x-text="leave.reason"></td>
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
                sickLetter: null,
                modalForm: new bootstrap.Modal(document.getElementById('modal-form')),
                editVal: '',
                search: '',
                form: document.getElementById('form'),
                leavesLeft: 0,
                userId: "{{ Auth::id() }}",
                async init() {
                    await this.getOwnLeaves();
                    await this.getLeavesLeft();
                },
                add() {
                    this.form.reset();
                    this.editVal = '';
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
                },
                async save(id) {
                    this.buttonLoading = true;
                    try {
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
