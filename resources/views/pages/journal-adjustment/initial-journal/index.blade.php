@extends('layouts.template')
@section('page-title', 'Jurnal Awal')
@section('content')

    <div x-data="initialJournal()">
        @include('pages.journal-adjustment.initial-journal.modal.create')
        @include('pages.journal-adjustment.initial-journal.modal.edit')
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
                                <th class="min-w-125px">Deskripsi</th>
                                <th class="min-w-125px">Saldo Awal Periode</th>
                                <th class="min-w-125px">Akun Debit</th>
                                <th class="min-w-125px">Akun Credit</th>
                                <th class="min-w-125px">Status Konfirmasi</th>
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
                            <template x-if="!isLoading && initialJournal.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(row, index) in initialJournal.data" :key="row.id">
                                <tr>
                                    <td x-text="startIndex + index++">
                                    </td>
                                    <td x-text="row.description"></td>
                                    <td x-text="formatNumber(row.initial_payment)"></td>
                                    <td x-text="`${row.sub_account_debit.code} - ${row.sub_account_debit.name}`"></td>
                                    <td x-text="`${row.sub_account_credit.code} - ${row.sub_account_credit.name}`"></td>
                                    <template x-if="row.status_confirmation === 0">
                                        <td>
                                            <span class="badge bg-danger">Belum dikonfirmasi</span>
                                        </td>
                                    </template>
                                    <template x-if="row.status_confirmation === 0">
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-edit" @click="edit(row.id)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-success btn-sm" @click="confirm(row.id)">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" @click="destroy(row.id)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </template>
                                    <template x-if="row.status_confirmation === 1">
                                        <td>
                                            <span class="badge bg-success fs-7">Terkonfirmasi.</span>
                                        </td>
                                    </template>
                                    <template x-if="row.status_confirmation === 1">
                                        <td>
                                            <button class="btn btn-primary btn-sm"
                                                    disabled>
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-success btn-sm" disabled>
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </template>
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
        function initialJournal() {
            return {
                buttonLoading: false,
                isLoading: false,
                startIndex: null,
                initialJournal: [],
                search: '',
                editVal: '',
                initialJournalId: '',
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formCreate: document.getElementById('form-create'),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formEdit: document.getElementById('form-edit'),
                async init() {
                    await this.initialJournalData();
                },
                async initialJournalData() {
                    const resp = await axios.get('/journal-adjustment/initial-journal/data');
                    this.initialJournal = resp.data;
                    this.startIndex = this.initialJournal.from;
                },
                async searchData(url = '/journal-adjustment/initial-journal/search') {
                    this.isLoading = true;

                    this.initialJournal = await axios.get(url, {
                        params: {
                            search: this.search
                        },
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });
                    this.isLoading = false;
                },
                async nextPage() {
                    if (this.initialJournal.next_page_url) {
                        const resp = await axios.get(`${this.initialJournal.next_page_url}`);
                        this.startIndex = this.initialJournal.from
                        this.initialJournal = resp.data
                    }
                },
                async previousPage() {
                    if (this.initialJournal.prev_page_url) {
                        const resp = await axios.get(`${this.initialJournal.prev_page_url}`);
                        this.startIndex = this.initialJournal.from
                        this.initialJournal = resp.data
                    }
                },
                async add() {
                    await this.accountDebitData();
                    await this.accountCreditData();
                },
                async accountDebitData() {
                    $(".account-debit-select2").select2({
                        ajax: {
                            url: '/journal-adjustment/initial-journal/account/debit/data',
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
                async accountCreditData() {
                    $(".account-credit-select2").select2({
                        ajax: {
                            url: '/journal-adjustment/initial-journal/account/credit/data',
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
                    this.buttonLoading = true;
                    try {
                        const resp = await axios.post('/journal-adjustment/initial-journal/', new FormData(this
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
                    this.initialJournalId = id;
                    const resp = await axios.get(`/journal-adjustment/initial-journal/edit/${id}`)
                    this.editVal = resp.data
                    await this.accountDebitData();
                    await this.accountCreditData();
                    await this.selectedCreditAccount();
                    await this.selectedDebitAccount();
                },
                async selectedDebitAccount() {
                    const selectedDebitAccount = $('#selectedDebitAccount');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/journal-adjustment/initial-journal/debit-account/selected/${this.editVal.id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedDebitAccount.append(option).trigger('change');

                        selectedDebitAccount.trigger({
                            type: 'select2:select',
                            params: {
                                results: response
                            }
                        });
                    });
                },
                async selectedCreditAccount() {
                    const selectedCreditAccount = $('#selectedCreditAccount');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/journal-adjustment/initial-journal/credit-account/selected/${this.editVal.id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedCreditAccount.append(option).trigger('change');

                        selectedCreditAccount.trigger({
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
                        await axios.post(`/journal-adjustment/initial-journal/update/${this.initialJournalId}`, new FormData(this
                            .formEdit)).then(() => {
                            Swal.fire({
                                title: "Berhasil",
                                icon: 'success'
                            }).then(async () => {
                                this.formEdit.reset();
                                this.modalEdit.hide();
                                await this.init();
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
                async confirm(id) {
                    await Swal.fire({
                        title: "Anda yakin?",
                        text: "Data yang dikonfirmasi tidak bisa dihapus ataupun diubah kembali.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Konfirmasi!"
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                await axios.post(`/journal-adjustment/initial-journal/confirm/${id}`);
                                await Swal.fire({
                                    title: "Terkonfirmasi!",
                                    text: "Data sukses dikonfirmasi",
                                    icon: "success"
                                });
                                await this.init();
                            } catch (error) {
                                console.error(error);
                            }
                        }
                    });
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
                                await axios.delete(`/journal-adjustment/initial-journal/${id}`);
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
