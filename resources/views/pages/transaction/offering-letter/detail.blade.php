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
    <div class="d-flex flex-column flex-lg-row" x-data="invoiceDetail">
        <div class="card">
            <img class="w-sm-100 h-200px" src="{{ asset('assets/media/logos/kop-header.png') }}" alt="">
            <div class="card-body">
                <div class="d-flex flex-column flex-xl-row">
                    <div class="me-10">
                            <h6 class="mb-2 fw-bolder text-hover-primary text-wrap" style="width: 20rem">
                                Yth, {{ $offeringLetter->contact->pic_name }}
                                ,<br> {{ $offeringLetter->contact->company_name }}
                            </h6>

                            <div class="mb-6">
                                <div class="fw-bold text-gray-800 fs-6">
                                    {{ $offeringLetter->contact?->complete_address ?? 'Ditempat' }}
                                </div>
                            </div>

                            <div class="separator mb-3"></div>

                            <div class="mb-4">
                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center">
                                    {{ \App\Helper\formatDate($offeringLetter->date) }}
                                </div>
                            </div>

                            <div class="separator mb-3"></div>

                            <div class="mb-6">
                                <div class="fw-semibold text-gray-600 fs-7">Nomor:</div>
                                <div class="fw-bold fs-6 text-gray-800">
                                    {{ $offeringLetter->offering_number }}
                                </div>
                            </div>
                            <div class="mb-6">
                                <div class="fw-semibold text-gray-600 fs-7">Perihal:</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ $offeringLetter->regarding }}</div>
                            </div>
                            <div class="m-0">
                                <div class="fw-semibold text-gray-600 fs-7">Time Spent:</div>
                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center">230 Hours
                                    <span class="fs-7 text-success d-flex align-items-center">
														<span class="bullet bullet-dot bg-success mx-2"></span>35$/h Rate</span>
                                </div>
                            </div>
                    </div>
                    <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                        <div class="mt-n1">
                            <div class="m-0">
                                <div class="row g-5 mb-11">
                                    <p class="fw-bold text-gray-700 d-flex align-items-center">
                                        Dengan hormat,
                                        <br>
                                        Kami dari PT. Mayatama
                                        Solusindo bermaksud menawarkan harga layanan dedicated
                                        untuk {{ $offeringLetter->contact->company_name }}, berikut adalah harga terbaik
                                        yang kami
                                        tawarkan :
                                    </p>
                                </div>
                                <div class="flex-grow-1">
                                    <!--begin::Table-->
                                    <div class="table-responsive border-bottom mb-9">
                                        <table class="table mb-3">
                                            <thead>
                                            <tr class="border-bottom fs-6 fw-bold text-muted">
                                                <th class="text-center">No</th>
                                                <th class="text-center">Layanan</th>
                                                <th class="text-center">Kapasitas / Jumlah</th>
                                                <th class="text-center">Harga / Bulan</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($offeringLetterServices as $service)
                                                <tr class="fw-bold text-gray-700 fs-5 text-end">
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td class="text-center">
                                                        {{ $service->serviceCategory->name }}
                                                    </td>
                                                    <td class="text-center"> {{ $service->capacity }} {{ $service->unitType->name }}</td>
                                                    <td class="text-center">
                                                        <div>
                                                            Rp {{ number_format($service->price, false, '.', '.') }}</div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end mb-10">
                                        <div class="mw-300px">
                                            <!--begin::Item-->
                                            <div class="d-flex flex-stack mb-3">
                                                <div class="fw-semibold pe-10 text-gray-600 fs-7">PPN</div>
                                                <div
                                                    class="text-end fw-bold fs-6 text-gray-800">
                                                    Rp {{ number_format($totalPPN, false,'.', '.') }}
                                                </div>
                                            </div>

                                            <div class="d-flex flex-stack">
                                                <div class="fw-semibold pe-10 text-gray-600 fs-7">Total</div>
                                                <div
                                                    class="text-end fw-bold fs-6 text-gray-800">
                                                    Rp {{ number_format($total, false,'.', '.') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="fw-bold text-gray-700 d-flex align-items-center mb-1">
                                        Adapun syarat, ketentuan dan layanan yang kami berikan antara lain :
                                    </p>
                                    <ul class="fw-bold text-gray-700">
                                        <li>SLA 99,5%</li>
                                        <li>Support Pelayanan 7 x 24 jam, online maupun onsite.</li>
                                        <li>Masa berlaku penawaran 1 bulan</li>
                                        <li>
                                            Minimum kontrak 1 tahun dan otomatis diperpanjang apabila tidak ada
                                            permintaan
                                            berhenti berlangganan
                                        </li>
                                        @foreach($offeringLetterServiceDescription as $desc)
                                            <li style="font-size: 13px">{{ $desc->skl->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <img class="w-100" src="{{ asset('assets/media/logos/kop-footer.png') }}" alt="">
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
