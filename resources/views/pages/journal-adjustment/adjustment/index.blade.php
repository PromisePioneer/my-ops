@extends('layouts.template')
@section('page-title', 'Jurnal Penyesuaian')
@section('content')

    <div x-data="journalAdjustment()">
        @include('pages.journal-adjustment.adjustment.modal.create')
        @include('pages.journal-adjustment.adjustment.modal.edit')
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
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modal-create" @click="add()">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">No</th>
                                <th class="min-w-125px">Tanggal Pembayaran</th>
                                <th class="min-w-125px">Jurnal Awal</th>
                                <th class="min-w-125px">Deskripsi</th>
                                <th class="min-w-125px">Pembayaran Bulanan</th>
                                <th class="min-w-125px">Actions</th>
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
                            <template x-if="!isLoading && journalAdjustment.data?.length === 0">
                                <tr>
                                    <td colspan="7">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(row, index) in journalAdjustment.data" :key="row.id">
                                <tr>
                                    <td x-text="startIndex + index++">
                                    </td>
                                    <td x-text="formatDate(row.payment_date)"></td>
                                    <td x-text="row.initial_journal.description"></td>
                                    <td x-text="row.description"></td>
                                    <td x-text="row.total_payment_per_month"></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(row.id)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" @click="destroy(row.id)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        function journalAdjustment() {
            return {
                buttonLoading: false,
                isLoading: false,
                startIndex: null,
                journalAdjustment: [],
                search: '',
                journalAdjustmentId: '',
                editVal: '',
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formCreate: document.getElementById('form-create'),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formEdit: document.getElementById('form-edit'),
                async init() {
                    await this.journalAdjustmentData();
                },
                async journalAdjustmentData() {
                    const resp = await axios.get('/journal-adjustment/adjustment/data');
                    this.journalAdjustment = resp.data;
                    this.startIndex = this.journalAdjustment.from;
                },
                async searchData() {

                },
                async nextPage() {
                    if (this.journalAdjustment.next_page_url) {
                        const resp = await axios.get(`${this.journalAdjustment.next_page_url}`);
                        this.startIndex = this.journalAdjustment.from
                        this.journalAdjustment = resp.data
                    }
                },
                async previousPage() {
                    if (this.journalAdjustment.prev_page_url) {
                        const resp = await axios.get(`${this.journalAdjustment.prev_page_url}`);
                        this.startIndex = this.journalAdjustment.from
                        this.journalAdjustment = resp.data
                    }
                },
                async add() {
                    await this.initialJournalData();
                },
                async initialJournalData() {
                    $(".initial-journal-select2").select2({
                        ajax: {
                            url: '/journal-adjustment/adjustment/initial-journal/data',
                            dataType: "json",
                            type: "GET",
                            data: function (params) {
                                return {
                                    search: params.term
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    });
                },
                async save() {
                    try {
                        const resp = await axios.post('/journal-adjustment/adjustment/', new FormData(this
                            .formCreate))
                        if (resp.status === 200) {
                            Swal.fire({
                                title: "Berhasil",
                                icon: 'success'
                            })
                            this.formCreate.reset();
                            this.modalCreate.hide();
                            await this.init();
                            this.buttonLoading = false;
                        } else {
                            Swal.fire({
                                title: "Error",
                                icon: 'error'
                            })
                            this.buttonLoading = false;
                        }
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => {
                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast.blade.php-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            };
                            toastr.error(respError[err][0])
                            this.buttonLoading = false;
                        })
                    }
                },
                async edit(id) {
                    this.journalAdjustmentId = id;
                    const resp = await axios.get(`/journal-adjustment/adjustment/${id}`);
                    this.editVal = resp.data
                    await this.initialJournalData();
                    await this.selectedInitialJournal();
                },
                async selectedInitialJournal() {
                    const selectedInitialJournal = $('#selectedInitialJournal');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/journal-adjustment/adjustment/initial-journal/selected/${this.editVal.initial_journal_id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedInitialJournal.append(option).trigger('change');

                        selectedInitialJournal.trigger({
                            type: 'select2:select',
                            params: {
                                results: response
                            }
                        });
                    });
                },
                async update() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/journal-adjustment/adjustment/${this.journalAdjustmentId}`, new FormData(this
                            .formEdit)).then(() => {
                            Swal.fire({
                                title: "Berhasil",
                                icon: 'success'
                            }).then(async () => {
                                this.formEdit.reset();
                                this.modalEdit.hide();
                                await this.init();
                                this.buttonLoading = false;
                            })
                        })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => {
                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast.blade.php-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            };
                            toastr.error(respError[err][0])
                            this.buttonLoading = false;
                        })
                    }
                },
                async destroy(id) {
                    await Swal.fire({
                        title: "Anda yakin?",
                        text: "Data akan hilang.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Hapus!"
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                await axios.delete(`/journal-adjustment/adjustment/${id}`);
                                await Swal.fire({
                                    title: "Terhapus!",
                                    text: "Data sukses dihapus",
                                    icon: "success"
                                });
                                await this.init();
                            } catch (error) {
                                console.error(error);
                            }
                        }
                    });
                },
                formatDate(val) {
                    const date = new Date(val);
                    const formatter = new Intl.DateTimeFormat('en-US', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                    return formatter.format(date);
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                },
            }
        }
    </script>
@endpush
