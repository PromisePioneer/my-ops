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
                <img class="w-sm-100 h-200px" src="{{ asset('assets/media/logos/kop-header.png') }}" alt="">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row">
                        <div class="me-10">
                            <p class="mb-2 fw-bolder text-hover-primary text-wrap" style="width: 20rem">
                                Yth, Bapak/Ibu {{ $offeringLetter->contact->pic_name }},<br>
                                <span>{{ $offeringLetterCompanyName }}</span>
                            </p>

                            <div class="mb-20">
                                <div class="fw-bold text-gray-800 fs-6">
                                    {{ $offeringLetter->contact?->complete_address ?? 'Ditempat' }}
                                </div>
                            </div>

                            <div class="separator mb-1" style="border-bottom-color: #7dbbf5"></div>

                            <div class="mb-3">
                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center">
                                    {{ \App\Helper\formatDate($offeringLetter->date) }}
                                </div>
                            </div>

                            <div class="separator mb-3" style="border-bottom-color: #7dbbf5"></div>

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
                        </div>
                        <div class="flex-lg-row-fluid me-md-18 mb-10 mb-xl-0">
                            <div class="mt-n1">
                                <div class="m-0">
                                    <div class="row g-5 mb-11">
                                        <p class="fw-bold d-flex align-items-center">
                                            Dengan hormat,
                                            <br>
                                            Kami dari PT. Mayatama
                                            Solusindo bermaksud menawarkan harga layanan dedicated
                                            untuk {{ $offeringLetterCompanyName }}, berikut adalah harga
                                            terbaik
                                            yang kami
                                            tawarkan :
                                        </p>
                                    </div>
                                    <div class="flex-grow-1">
                                        <!--begin::Table-->
                                        <div class="table-responsive mb-4">
                                            <table class="table">
                                                <thead>
                                                <tr class="border-bottom border-black fs-6 fw-bold">
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Layanan</th>
                                                    <th class="text-center">Kapasitas / Jumlah</th>
                                                    <th class="text-center">Harga / Bulan</th>
                                                </tr>
                                                </thead>
                                                <tbody class="border-bottom border-black">
                                                @foreach($offeringLetterServices as $service)
                                                    <tr class="fs-5 text-end border-bottom border-black">
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td class="text-center">
                                                            {{ $service->serviceCategory->name }}
                                                        </td>
                                                        <td class="text-center"> {{ $service->capacity }} {{ $service->unitType->name }}</td>
                                                        <td class="text-center">
                                                            {{ number_format($service->price, false, '.', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr class="border-bottom border-black p-1">
                                                    <td colspan="3" class="text-end fw-bold fs-6 text-gray-800">
                                                        PPN
                                                    </td>
                                                    <td class="text-center fw-bold fs-6 text-gray-800">  {{ number_format($totalPPN, false,'.', '.') }}</td>
                                                </tr>
                                                <tr class="p-1">
                                                    <td colspan="3" class="text-end fw-bold fs-6 text-gray-800">
                                                        Total
                                                    </td>
                                                    <td class="text-center fw-bold fs-6 text-gray-800">  {{ number_format($total, false,'.', '.') }}</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <p class="fw-bold d-flex align-items-center mb-1">
                                            Adapun syarat dan ketentuan layanan yang kami berikan antara lain :
                                        </p>
                                        <ul class="fw-bold mb-10">
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


                                        <div class="ms-4 mb-4 flex-column">
                                            <div class="fw-bold mb-20">
                                                Penanggung Jawab
                                            </div>
                                            <div class="fw-bold">
                                                {{ $offeringLetter->user->roles[0]?->name ?? '' }}
                                            </div>
                                            <div class="fw-bold">
                                                {{ $offeringLetter->user->name }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <img class="w-100" src="{{ asset('assets/media/logos/kop-footer.png') }}" alt="">
            </div>
        </div>

        <div class="flex-lg-auto min-w-lg-300px">
            <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
                 data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', lg: '300px'}"
                 data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false"
                 data-kt-sticky-zindex="95">
                <div class="card-body p-10">
                    <div class="mb-0">
                        @if($offeringLetter->status === 0)
                            <div class="row mb-5">
                                <div class="col">
                                    <a href="{{ url('income-transactions/offering-letters/edit/' . $offeringLetter->id) }}"
                                       class="btn btn-light btn-active-light-info w-100">Ubah</a>
                                </div>
                                <div class="col">
                                    <button type="button" @click="destroy({{ $offeringLetter->id }})"
                                            class="btn btn-light btn-active-light-danger w-100">Hapus
                                    </button>
                                </div>
                            </div>
                            <button type="button" @click="confirmOfferingLetter()" class="btn btn-primary w-100 mb-4">
                                Konfirmasi
                            </button>
                        @else
                            <a href="{{ url('income-transactions/offering-letters/export-pdf/'. $offeringLetter->id) }}"
                               class="btn btn-light-info w-100 mb-4" target="_blank">
                                Print PDF
                            </a>
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
