@extends('layouts.template')
@section('content')
    @push('style')
        <style>
            .bg-purple {
                background-color: #83B4FF !important;
            }

            @media print {
                * {
                    margin: 0 !important;
                    padding: 0 !important;
                }

                #controls, .footer, .footerarea {
                    display: none;
                }

                html, body {
                    height: 100%;
                    overflow: hidden;
                    background: #FFF;
                    font-size: 9.5pt;
                }

                .template {
                    width: auto;
                    left: 0;
                    top: 0;
                }

                img {
                    width: 100%;
                }

                li {
                    margin: 0 0 10px 20px !important;
                }
            }
        </style>
    @endpush
    <div class="d-flex flex-column flex-lg-row" x-data="invoiceDetail">
        @include('pages.transaction.invoice.modal.jurnal-entry')
        @include('pages.transaction.invoice.modal.payment-complete')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            @if(isset($letterHead->header))
                <img class="w-100" src="{{ Storage::url($letterHead->header) }}" alt="">
            @else
                <img class="w-100" src="{{ asset('assets/media/logos/kop-placeholder.png') }}" alt="">
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-column flex-sm-row mb-19 align-items-center">
                        <div class="col-lg-6">
                            <div class="text-black fs-6 fw-bolder mb-2">{{ $companyProfile->name }}</div>
                            <div class="text-gray-600 fs-6 fw-bold mb-2">{{ $companyProfile->address }}</div>
                            <div class="fs-6 text-gray-800 fw-bold mb-3">NPWP : {{ $companyProfile->npwp }}</div>
                        </div>
                        <div class="col-lg-6">
                            <div class="text-black fs-6 fw-bold mb-3">INVOICE NO : {{ $invoice->invoice_number }}</div>
                            <div class="text-black fs-6 fw-bold mb-3">TANGGAL
                                : {{ $invoice->created_at->format('d M y') }}</div>
                            <div class="text-black fs-6 fw-bold mb-3 text-uppercase">PERIODE
                                : {{ $invoice->created_at->format('M') }}</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="text-black fs-6 fw-bold mb-3">
                                Kepada : {{ $invoice->contact->full_name }},
                            </div>
                            <div class="text-black fs-6 fw-bold mb-3">
                                Alamat : {{ $invoice->contact->complete_address }}
                            </div>
                        </div>
                    </div>
                    <div class="pb-4">
                        <div class="d-flex justify-content-between flex-column flex-md-row">
                            <div class="flex-grow-1 pt-8 mb-13">
                                <div class="table-responsive mb-4 row justify-content-center">
                                    <table class="table table-sm gs-7 gy-7 gx-7 border border-dark">
                                        <thead>
                                        <tr class="fw-bolder fs-6 text-gray-800 border border-dark bg-primary">
                                            <th>No</th>
                                            <th>Keterangan</th>
                                            <th>@</th>
                                            <th>Qty</th>
                                            <th>Jumlah</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($invoiceServiceList as $invoiceService)
                                            <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $invoiceService->description }}</td>
                                                <td>Rp.{{ number_format($invoiceService->unit_price) }}</td>
                                                <td>{{ $invoiceService->qty }}</td>
                                                <td>Rp. {{ number_format($invoiceService->total_price) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                            <td colspan="4" class="text-end  py-1">Sub TOTAL</td>
                                            <td class="py-1">Rp. {{ number_format($invoiceService->total_price) }}</td>
                                        </tr>
                                        <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                            <td colspan="4" class="text-end  py-1">PPN 11%</td>
                                            <td class="py-1">Rp. -</td>
                                        </tr>
                                        <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                            <td colspan="4" class="text-end  py-1">Pph 2%</td>
                                            <td class="py-1">Rp. -</td>
                                        </tr>
                                        <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                            <td colspan="4" class="text-end  py-1">TOTAL</td>
                                            <td class="py-1">Rp. {{ number_format($invoiceService->total_price) }}</td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="d-flex flex-column mw-300px w-100">
                                    <table>
                                        <tr>
                                            <td class="fw-bold fs-5 mb-3 text-dark">Bank :</td>
                                            <td class="text-end fw-normal">{{ $companyProfile->bank }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold fs-5 mb-3 text-dark">No Rekening :</td>
                                            <td class="text-end fw-normal">{{ $companyProfile->bank_account_number }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold fs-5 mb-3 text-dark">Atas Nama :</td>
                                            <td class="text-end fw-normal text-uppercase">{{ $companyProfile->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6 text-end mb-20">
                            <table align="right">
                                <thead>
                                <tr>
                                    <th class="pb-20">PT. MAYATAMA SOLUSINDO</th>
                                </tr>
                                <tr>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th><u>YOGA ARYA ESA PRATAMA</u></th>
                                </tr>
                                <tr>
                                    <th class="text-center">Direktur</th>
                                </tr>
                                </thead>
                            </table>

                        </div>
                    </div>

                    <div class="border border-dark mb-10">
                        <ol>
                            <li>Pembayaran wajib melampirkan bukti transfer ke email <a
                                    href="#">finance@mayatama.net</a></li>
                            <li>Keterlambatan pembayaran dapat menyebabkan layanan anda terblokir.</li>
                            <li>Mohon abaikan tagihan ini apabila anda telah melakukan pembayaran.</li>
                            <li> Untuk konfirmasi silahkan menghubungi account representative kami.</li>
                        </ol>
                    </div>
                </div>
            </div>
            @if(isset($letterHead->footer))
                <img class="w-100" src="{{ Storage::url($letterHead->footer) }}" alt="">
            @else
                <img class="w-100" src="{{ asset('assets/media/logos/kop-placeholder.png') }}" alt="">
            @endif
        </div>
        <div class="flex-lg-auto min-w-lg-300px">
            <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
                 data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', lg: '300px'}"
                 data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false"
                 data-kt-sticky-zindex="95">
                <div class="card-body p-10">
                    <div class="mb-0">
                        @if($invoice->status === 0)
                            <div class="row mb-5">
                                <div class="col">
                                    <a href="{{ url('income-transactions/invoice/edit/' . $invoice->id) }}"
                                       class="btn btn-light btn-active-light-info w-100">Ubah</a>
                                </div>
                                <div class="col">
                                    <button type="button" @click="destroy({{ $invoice->id }})"
                                            class="btn btn-light btn-active-light-danger w-100">Hapus
                                    </button>
                                </div>
                            </div>
                            <button type="button" @click="confirm()" class="btn btn-primary w-100 mb-4">
                                Konfirmasi
                            </button>
                        @else
                            <a href="{{ url('income-transactions/invoice/export-pdf/'. $invoice->id) }}"
                               class="btn btn-light-info w-100 mb-4" target="_blank">
                                Print PDF
                            </a>
                            <button type="button" @click="jurnalEntries()" href="#" class="btn btn-primary w-100"
                                    data-bs-toggle="modal"
                                    data-bs-target="#jurnal-entry">
                                Jurnal Entry
                            </button>

                            @if($invoice->payment_status === "Belum Lunas")
                                <button type="submit" href="#"
                                        class="@if($invoice->payment_status === 'Belum Lunas')
                                btn btn-light-danger w-100 mt-4
                                @else
                                 btn btn-success w-100 mt-4
                                @endif"
                                        data-bs-toggle="modal" data-bs-target="#payment-complete-modal">
                                    {{ $invoice->payment_status }}
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function invoiceDetail() {
            return {
                id: '{{ $invoice->id }}',
                jurnalEntry: [],
                openPph23Form: false,
                jurnalEntryLunasModal: new bootstrap.Modal(document.getElementById('jurnal-entry')),
                paymentCompleteModal: new bootstrap.Modal(document.getElementById('payment-complete-modal')),
                paymentCompleteForm: document.getElementById('paymentCompleteForm'),
                async init() {
                },
                async jurnalEntries() {
                    const jurnalEntry = await axios.get(`/income-transactions/invoice/jurnal-entry/${this.id}`);
                    this.jurnalEntry = jurnalEntry.data;
                },
                async confirm() {
                    showConfirmModal("Anda yakin?", "Invoice yang sudah di konfirmasi tidak bisa diubah ataupun dihapus.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/income-transactions/invoice/confirm/${this.id}`);
                            await showAlert('success', 'Data sukses Dikonfirmasi').then(() => {
                                location.reload()
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                },
                openPphFormAction() {
                    this.openPph23Form = !this.openPph23Form;
                },
                async savePaymentComplete() {
                    showConfirmModal("Anda yakin?", "Ubah status Pelunasan? ", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/income-transactions/invoice/update-payment-status/${this.id}`, new FormData(this.paymentCompleteForm));
                            await showAlert('success', 'Data sukses Dikonfirmasi').then(() => {
                                location.reload()
                            });
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
