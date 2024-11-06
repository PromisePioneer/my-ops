@extends('layouts.template')
@section('content')

    @push('style')
        <style>
            .bg-purple {
                background-color: #83B4FF !important;
            }
        </style>
    @endpush



    <div class="d-flex flex-column flex-lg-row" x-data="FABDetail">
        @include('pages.transaction.fab.modal.jurnal-entry')
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card border-top-0">
                <div class="card-header p-0 border-0">
                    <img class="w-100" src="{{ asset('assets/media/logos/kop-header.png') }}" alt="">
                </div>
                <div class="card-body">
                    <div class="text-center mb-20">
                        <h1><u>FORMULIR APLIKASI BERLANGGANAN</u></h1>
                    </div>


                    <div class="row mb-10">
                        <div class="col-lg-6">
                            @php
                                $date = DateTime::createFromFormat('Y-m-d', $fab->date);
                                    $formattedTransactiondate = $date->format('d M Y');
                                    $formattedTransactionMonth = $date->format('M Y');
                            @endphp
                            <table>
                                <tr>
                                    <th width="50%">Nama Pelanggan</th>
                                    <th>:</th>
                                    <th class="px-5">{{ $fab->contact->full_name }}</th>
                                </tr>
                                <tr>
                                    <th width="50%">NPWP</th>
                                    <th>:</th>
                                    <th class="px-5">{{ $fab->contact->npwp }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" class="text-uppercase">Identitas ({{ $fab->contact->identity_type }}
                                        )
                                    </th>
                                    <th>:</th>
                                    <th class="px-5">{{ $fab->contact->identity_number }}</th>
                                </tr>
                                <tr>
                                    <th width="50%" class="text-uppercase">No. Telepon</th>
                                    <th>:</th>
                                    <th class="px-5">{{ $fab->contact->phone_number }}</th>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table align="center">
                                <tr>
                                    <th width="40%">Kode FAB</th>
                                    <th>:</th>
                                    <th class="px-5">{{ $fab->fab_number }}</th>
                                </tr>
                                <tr>
                                    <th width="40%">Tanggal</th>
                                    <th>:</th>
                                    <th class="px-5">{{ $formattedTransactiondate }}</th>
                                </tr>
                            </table>
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
                                            <th>Layanan</th>
                                            <th>@</th>
                                            <th>Qty</th>
                                            <th>Jumlah</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($fabHasServiceCategories as $service)
                                            <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $service->service->name }}</td>
                                                <td>Rp.{{ number_format($service->unit_price) }}</td>
                                                <td>{{ $service->qty }}</td>
                                                <td>Rp. {{ number_format($service->total_price) }}</td>
                                            </tr>
                                        @empty
                                            <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                                <td colspan="5" class="text-center">Data Kosong</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                        <tfoot>
                                        <tr class="fw-bolder fs-6 text-gray-800 border border-dark">
                                            <td colspan="4" class="text-end  py-1">TOTAL</td>
                                            <td class="py-1">
                                                Rp. {{ number_format($fabHasServiceCategories->sum('total_price')) }}</td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="">
                                    <table>
                                        <tr>
                                            <th width="20%">Alamat Penagihan</th>
                                            <th>:</th>
                                            <th class="px-5">{{ $fab->billing_address }}</th>
                                        </tr>
                                        <tr>
                                            <th width="30%">Alamat Pemasangan</th>
                                            <th>:</th>
                                            <th class="px-5">{{ $fab->billing_address }}</th>
                                        </tr>
                                    </table>
                                </div>
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
                 data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', lg: '300px'}"
                 data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false"
                 data-kt-sticky-zindex="95">
                <div class="card-body p-10">
                    <div class="mb-0">
                        @if($fab->status === 0)
                            <div class="row mb-5">
                                <div class="col">
                                    <a href="{{ url('income-transactions/fab/edit/' . $fab->id) }}"
                                       class="btn btn-light btn-active-light-info w-100">Ubah</a>
                                </div>
                                <div class="col">
                                    <button type="button" @click="destroy({{ $fab->id }})"
                                            class="btn btn-light btn-active-light-danger w-100">Hapus
                                    </button>
                                </div>
                            </div>
                        @endif
                        @if(isset($letterHead))
                            <a href="{{ url('income-transactions/fab/export-pdf/'. $fab->id) }}"
                               class="btn btn-light-info w-100 mb-4" target="_blank">
                                Print PDF
                            </a>
                        @endif
                        @if($fab->status === 0)
                            <button type="button" class="btn btn-primary w-100 mb-4" @click="confirm()"
                                    x-text="buttonLoading ? 'Loading' : 'Konfirmasi FAB'">Konfirmasi
                            </button>
                        @endif
                        @if($fab->status === 1)
                            <button @click="openJurnalEntry()" class="btn btn-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#jurnal_entry">
                                Jurnal Entry
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
        function FABDetail() {
            return {
                id: "{{ $fab->id }}",
                buttonLoading: false,
                jurnalEntry: [],
                async openJurnalEntry() {
                    const jurnalEntry = await axios.get(`/income-transactions/fab/jurnal-entry/${this.id}`);
                    this.jurnalEntry = jurnalEntry.data;
                },
                async confirm() {
                    showConfirmModal("Anda yakin?", "FAB yang sudah di konfirmasi tidak akan dapat dihapus ataupun diubah.", "Konfirmasi", async () => {
                        try {
                            await axios.post(`/income-transactions/fab/confirm/${this.id}`);
                            await showAlert('success', 'Data sukses dikonfirmasi').then(() => {
                                location.reload();
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/income-transactions/fab/${this.id}`);
                            await showAlert('success', 'Data sukses dihapus').then(() => {
                                window.location.href = "/income-transactions/fab"
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
