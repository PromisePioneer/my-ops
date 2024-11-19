@php use function App\Helper\formatDate; @endphp
@extends('layouts.template')
@section('page-title', 'Pendapatan - PO Detail')
@section('content')

    <div class="d-flex flex-row flex-lg-row" x-data="PODetail()">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-8 me-xl-10">
            <div class="card">
                <div class="card-body p-0">
                    <img class="w-sm-100 h-200px" src="{{ asset('assets/media/logos/kop-header.png') }}" alt="">

                    <div class="text-center mb-15">
                        <h1><u>PURCHASE ORDER</u></h1>
                        <h3>{{ $purchaseOrder->po_number }}</h3>
                    </div>

                    <div class="d-flex ms-10 justify-content-between">
                        <div class="">
                            <table class="fs-6">
                                <tr>
                                    <td>Subjek</td>
                                    <td>:</td>
                                    <td>{{ $purchaseOrder->subject }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>:</td>
                                    <td>{{ formatDate($purchaseOrder->date) }}</td>
                                </tr>
                                <tr>
                                    <td>Subjek</td>
                                    <td>:</td>
                                    <td>{{ $purchaseOrder->subject }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="">
                            Yth, {{ $purchaseOrder->contact->pic_name }}, <br>
                            {{ $purchaseOrder->contact->company_name }}, <br>
                            {{ $purchaseOrder->contact->complete_address ?? 'Ditempat' }}
                        </div>
                    </div>
                </div>
                <img class="w-100" src="{{ asset('assets/media/logos/kop-footer.png') }}" alt="">
            </div>
        </div>

        <div class="flex-lg-auto min-w-sm-250px">
            <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
                 data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', lg: '250px'}"
                 data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false"
                 data-kt-sticky-zindex="95">
                <div class="card-body p-10">
                    <div class="mb-0">
                        @if($purchaseOrder->status === 0)
                            <div class="row mb-5">
                                @can('Edit Data Penawaran')
                                    <div class="col">
                                        <a href="{{ url('income-transactions/offering-letters/edit/' . $purchaseOrder->id) }}"
                                           class="btn btn-light btn-active-light-info w-100">Ubah</a>
                                    </div>
                                @endcan
                                @can('Hapus Data Penawaran')
                                    <div class="col">
                                        <button type="button" @click="destroy({{ $purchaseOrder->id }})"
                                                class="btn btn-light btn-active-light-danger w-100">Hapus
                                        </button>
                                    </div>
                                @endcan
                            </div>
                            @can('Konfirmasi Data Penawaran')
                                <button type="button" @click="confirmOfferingLetter()"
                                        class="btn btn-primary w-100 mb-4">
                                    Konfirmasi
                                </button>
                            @endcan
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

@endsection
@push('script')
    <script>
        function PODetail() {
            return {}
        }
    </script>
@endpush
