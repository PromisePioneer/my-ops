@extends('layouts.template')
@section('content')
    @push('style')
        <style>
            .bg-purple {
                background-color: #83B4FF !important;
            }

            @page {
                size: A4;
                margin: 0;
            }

            @media print {
                html, body {
                    width: 210mm;
                    height: 297mm;
                }
            }
        </style>
    @endpush

    <div class="d-flex flex-column flex-lg-row" x-data="offeringLetterDetail">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card">
                <div class="card-header p-0 border-0">
                    <img class="w-100" src="{{ asset('assets/media/logos/kop-header.png') }}" alt="">
                </div>
                <div class="card-body p-12">
                    <div class="row ">
                        <div class="col-lg-6">
                            <div class="text-black fs-6 fw-bold mb-3">
                                No. Surat : {{ $offeringLetter->offering_number }}
                            </div>
                            <div class="text-black fs-6 fw-bold mb-3">
                                Perihal : {{ $offeringLetter->regarding }},
                            </div>
                        </div>
                        <div class="col-lg-6 text-end">
                            <div class="text-black fs-6 fw-bold mb-3">
                                {{ \App\Helper\formatDate($offeringLetter->date) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-black fs-6 fw-normal">
                                Kepada Yth, <br>{{ $offeringLetter->contact->company_name }},
                                <div class="mt-10 align-items-center">
                                    Dengan hormat, Kami dari PT. Mayatama Solusindo bermaksud menawarkan harga layanan
                                    dedicated
                                    untuk {{ $offeringLetter->contact->company_name }}, berikut di bawah ini harga
                                    terbaik yang kami tawarkan :
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="d-flex justify-content-between flex-column flex-md-row">
                                <div class="flex-grow-1 pt-8">
                                    <div class="table-responsive mb-4 row justify-content-center">
                                        <table class="table table-sm gs-7 gy-7 gx-7 border border-dark">
                                            <thead>
                                            <tr class=" border border-dark bg-primary">
                                                <th class="fw-bold w-1px">No</th>
                                                <th class="fw-bold text-center">Layanan</th>
                                                <th class="fw-bold text-center">Kapasitas</th>
                                                <th class="fw-bold text-center">Harga / Bulan</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @forelse($offeringLetterServices as $service)
                                                <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                                    <td class="py-3">{{ $loop->iteration }}</td>
                                                    <td class="text-start py-3">{{ $service->serviceCategory->name }}</td>
                                                    <td class="text-center py-3">{{ $service->capacity }} {{ $service->unitType->name }}</td>
                                                    <td class="py-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div>Rp</div>
                                                            <div>{{ number_format($service->price, false, '.', '.') }}</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">Data Layanan Kosong</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                            <tfoot>
                                            <tr class="fs-6 text-gray-800 border border-dark">
                                                <td colspan="3" class="text-end py-1">PPN</td>
                                                <td class="fw-bolder py-1">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>Rp</div>
                                                        <div>{{ number_format($totalPPN, false, ".", ".")  }}</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                                <td colspan="3" class="text-end py-1">Total</td>
                                                <td class="py-1 text-end">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>Rp</div>
                                                        <div>{{ number_format($total, false, ".", '.') }}</div>
                                                    </div>
                                                </td>
                                            </tr>

                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="mb-10">
                                        Adapun syarat, ketentuan dan layanan yang kami berikan antara lain :
                                        <ul>
                                            <li style="font-size: 13px">SLA 99,5%</li>
                                            <li style="font-size: 13px">Support Pelayanan 7 x 24 jam, online maupun
                                                onsite.
                                            </li>
                                            <li style="font-size: 13px">Masa berlaku penawaran 1 bulan</li>
                                            <li style="font-size: 13px">
                                                Minimum kontrak 1 tahun dan otomatis diperpanjang apabila tidak ada
                                                permintaan berhenti berlangganan
                                            </li>
                                            @foreach($offeringLetterServiceDescription as $desc)
                                                <li>{{ $desc->skl->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-8">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6 text-end">
                                <table align="right">
                                    <thead>
                                    <tr>
                                        <th class="pb-20">PT. MAYATAMA SOLUSINDO</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"><u>{{ $offeringLetter->user->name }}</u></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">{{ $offeringLetter->user->roles[0]?->name ?? '' }}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-0 border-0">
                    <img class="w-100" src="{{ asset('assets/media/logos/kop-footer.png') }}" alt="">
                </div>
            </div>
        </div>
        <div class="flex-lg-auto min-w-lg-300px">
            <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
                 data-kt-sticky-offset="{default: false, lg: '200px'}"
                 data-kt-sticky-width="{lg: '250px', lg: '300px'}" data-kt-sticky-left="auto"
                 data-kt-sticky-top="150px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
                <div class="card-body p-10">
                    @if($offeringLetter->status === 0)
                        <div class="mb-0">
                            <div class="row mb-5">
                                <div class="col">
                                    <a href="{{ url('income-transactions/offering-letters/edit/' . $offeringLetter->id) }}"
                                       class="btn btn-light btn-active-light-info w-100">Ubah</a>
                                </div>
                                <div class="col">
                                    <button type="button" @click="destroy()"
                                            class="btn btn-light btn-active-light-danger w-100">Hapus
                                    </button>
                                </div>
                            </div>
                            <button type="button" @click="confirmOfferingLetter()" class="btn btn-primary w-100 mb-5"
                                    data-bs-toggle="modal" data-bs-target="#jurnal_entry">
                                Konfirmasi
                            </button>
                        </div>
                    @endif
                    @if($offeringLetter->status === 1)
                        <a href="{{ url('income-transactions/offering-letters/export-pdf/'. $offeringLetter->id) }}"
                           class="btn btn-light-info w-100 mb-4" target="_blank">
                            Print PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function offeringLetterDetail() {
            return {
                id: "{{ $offeringLetter->id }}",
                buttonLoading: false,
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/income-transactions/offering-letters/${this.id}`);
                            await showAlert('success', 'Data sukses dihapus').then(() => {
                                window.location.href = "/income-transactions/offering-letters"
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async confirmOfferingLetter() {
                    showConfirmModal("Anda yakin?", "Penawaran yang sudah di konfirmasi tidak akan dapat dihapus ataupun diubah.", "Konfirmasi", async () => {
                        try {
                            await axios.post(`/income-transactions/offering-letters/confirm/${this.id}`);
                            await showAlert('success', 'Data sukses dikonfirmasi').then(() => {
                                location.reload();
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
            }
        }
    </script>
@endpush
