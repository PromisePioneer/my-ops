@php use Carbon\Carbon; @endphp
@extends('layouts.template')
@section('page-title', 'Detail BAST')
@section('content')

    <div class="d-flex flex-column flex-lg-row" x-data="BASTDetail()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card">
                <div class="card-header p-0 border-0">
                    <img class="w-100" src="{{ asset('assets/media/logos/kop-header.png') }}" alt="">
                </div>
                <div class="card-body p-12">
                    <div class="row mb-10">
                        <div class="text-center">
                            <div class="mb-1">
                                <h2>
                                    BERITA ACARA SERAH TERIMA (“BAST”)
                                </h2>
                            </div>
                            <div>
                                <h3>
                                    {{ $bast->bast_number }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    @php
                        $date = Carbon::parse($bast->date)->locale('id');
                        $date->settings(['formatFunction' => 'translatedFormat']);
                    @endphp
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-black fs-6 fw-normal mb-3">
                                <p> Pada hari ini {{ $date->format('l') }} Tanggal {{ $date->format('j') }}
                                    Bulan {{ $date->format('F') }} Tahun {{ $date->format('Y') }}. Kami yang bertanda
                                    tangan di bawah ini menyatakan :</p>
                            </div>
                        </div>

                        <div class="text-center">
                            <table class="mt-5" style="margin-left: auto; margin-right: auto;">
                                <thead>
                                <tr>
                                    <th class="px-3 text-start">Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</th>
                                    <td class="text-start">{{ $bast->first_party_identity_name }} </td>
                                </tr>
                                <tr>
                                    <th class="text-start">Jabatan &nbsp;:</th>
                                    <td class="text-start">{{ $bast->first_party_position }}</td>
                                </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="ml-3 mt-10">
                            <p>Dalam hal ini bertindak untuk dan atas nama PT. Mayatama Solusindo yang Selanjutnya
                                disebut <b>PIHAK PERTAMA</b></p>
                        </div>

                        <div class="text-center">
                            <table class="mt-5" style="margin-left: auto; margin-right: auto;">
                                <thead>
                                <tr>
                                    <th class="px-3 text-start">Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</th>
                                    <td class="text-start">{{ $bast->contact->full_name }} </td>
                                </tr>
                                <tr>
                                    <th class="text-start">Perusahaan &nbsp;:</th>
                                    <td class="text-start">{{ $bast->contact->company_name }}</td>
                                </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="ml-3 mt-10">
                            <p>Dalam hal ini bertindak untuk dan atas nama PT. Mayatama Solusindo yang Selanjutnya
                                disebut <b>PIHAK KEDUA</b></p>
                        </div>

                        <div class="ml-3 mt-10">
                            <p>{{ $bast->objective }}</p>
                        </div>


                        <div class="pb-4">
                            <div class="d-flex justify-content-between flex-column flex-md-row">
                                <div class="flex-grow-1 pt-8 mb-13">
                                    <div class="table-responsive mb-4 row justify-content-center">
                                        <table class="table table-sm gs-7 gy-7 gx-7 border border-dark"
                                               style="margin-left: auto; margin-right: auto;">
                                            <thead>
                                            <tr class="fw-bolder fs-6 text-gray-800 border border-dark bg-primary">
                                                <th>No</th>
                                                <th>Barang</th>
                                                <th>Qty</th>
                                                <th>SN/Kode</th>
                                                <th>Keterangan</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @forelse($bastProducts as $product)
                                                <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $product->product_name }}</td>
                                                    <td>{{ $product->qty }}</td>
                                                    <td>{{ $product->serial_number }}</td>
                                                    <td> {{ $product->description }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Data Barang Kosong</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class=" mb-10">
                                        <p>Demikianlah berita acara serah terima barang ini di perbuat oleh kedua belah
                                            pihak, sejak penandatanganan berita acara ini, maka barang tersebut, menjadi
                                            tanggung jawab <b>PIHAK KEDUA</b>.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center justify-content-between text-center px-10">
                            <div class="">
                                <table class="p-3">
                                    <thead>
                                    <tr>
                                        <th>Yang Menyerahkan :</th>
                                    </tr>
                                    <tr>
                                        <th class="pb-20">PIHAK PERTAMA</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"><u>{{ $bast->first_party_identity_name }}</u></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">{{ $bast->first_party_position }}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="">
                                <table class="p-3">
                                    <thead>
                                    <tr>
                                        <th>Yang Menerima :</th>
                                    </tr>
                                    <tr>
                                        <th class="pb-20">PIHAK KEDUA</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"><u>{{ $bast->contact->full_name }}</u></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">{{ $bast->contact->company_name }}</th>
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
                    <div class="mb-0">
                        @if($bast->status === 0)
                            <div class="row mb-5">
                                <div class="col">
                                    <a href="{{ url('income-transactions/bast/edit/' . $bast->id) }}"
                                       class="btn btn-light btn-active-light-info w-100">Ubah</a>
                                </div>
                                <div class="col">
                                    <button type="button" @click="destroy()"
                                            class="btn btn-light btn-active-light-danger w-100">Hapus
                                    </button>
                                </div>
                            </div>
                        @endif
                        @if($bast->status === 1)
                            @if(isset($letterHead))
                                <a href="{{ url('income-transactions/bast/export-pdf/'. $bast->id) }}"
                                   class="btn btn-light-info w-100 mb-4" target="_blank">
                                    Print PDF
                                </a>
                            @endif
                        @endif
                        @if($bast->status === 0)
                            <button type="button" @click="confirm()" href="#" class="btn btn-primary w-100"
                                    data-bs-toggle="modal" data-bs-target="#jurnal_entry">
                                Konfirmasi
                            </button>
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
        function BASTDetail() {
            return {
                id: '{{ $bast->id }}',
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/income-transactions/bast/${this.id}`);
                            await showAlert('success', 'Data sukses dihapus').then(() => {
                                window.location.href = "/income-transactions/bast"
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async confirm() {
                    showConfirmModal("Anda yakin?", "Data yang dikonfirmasi tidak akan dapat diubah kembali atau pun dihapus.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/income-transactions/bast/confirm/${this.id}`);
                            await showAlert('success', 'Data sukses dihapus').then(() => {
                                location.reload();
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
            }
        }
    </script>
@endpush
