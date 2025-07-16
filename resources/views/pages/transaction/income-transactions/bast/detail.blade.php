@php use function App\Helper\formatDate; @endphp
@php @endphp
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
                    <div class="text-center mb-15">
                        <h2><u>BERITA ACARA SERAH TERIMA (“BAST”)</u></h2>
                        <h2>{{ $bast->bast_number }}</h2>
                    </div>

                    <div>
                        <p>Pada hari ini {{ formatDate($bast->date) }} kami yang bertandatangan dibawah ini:</p>
                    </div>

                    <div class="ms-5 mb-4">
                        <table>
                            <tr>
                                <td class="min-w-100px">Nama</td>
                                <td class="min-w-1px">:</td>
                                <td class="px-10">{{ $bast->baa->fab->fabPic->name }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td class="px-10">{{ $bast->baa->fab->fabPic->roles[0]?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Perusahaan</td>
                                <td>:</td>
                                <td class="px-10">PT Mayatama Solusindo</td>
                            </tr>
                        </table>
                    </div>

                    <p>Selanjutnya disebut “<b>MYFIBER</b>”.</p>

                    <div class="ms-5 mb-4">
                        <table>
                            <tr>
                                <td class="min-w-100px">Nama</td>
                                <td class="min-w-1px">:</td>
                                <td class="px-10">{{ $bast->baa->fab->po->contact->pic_name }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td class="px-10">{{ $bast->baa->fab->po->contact->pic_position }}</td>
                            </tr>
                            <tr>
                                <td>Perusahaan</td>
                                <td>:</td>
                                <td class="px-10">{{ $bast->baa->fab->po->contact->company_name }}</td>
                            </tr>
                        </table>
                    </div>

                    <div>
                        <p>Selanjutnya disebut “<b>PELANGGAN</b>”.</p>
                    </div>

                    <p>
                        <b>PELANGGAN</b> dan <b>MYFIBER</b> secara bersama-sama selanjutnya disebut juga “Para Pihak”,
                        dengan ini
                        menerangkan bahwa pekerjaan sebagai berikut:
                    </p>

                    <div>
                        <table class="table table-bordered border-black">
                            <thead>
                            <tr>
                                <th class="w-10px">No</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Data</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td><b>Tanggal PO</b></td>
                                <td>{{ formatDate($bast->baa->fab->po->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td><b>Nama Pekerjaan</b></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td><b>Nomor PO</b></td>
                                <td>{{ $bast->baa->fab->po->po_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                <td><b>Nilai PO</b></td>
                                <td class="py-0">
                                    <div class=" border-bottom border-black">
                                        @foreach($getPoItem as $poItem)
                                            <div class="d-flex justify-content-between">
                                                <div>{{ $poItem->item }}</div>
                                                <div>Rp.{{ number_format($poItem->price) }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>PPN</div>
                                        <div>Rp.{{ number_format($totalPPN) }}</div>
                                    </div>
                                    <div
                                        class="d-flex align-items-center justify-content-between border-bottom border-black">
                                        <div>Total</div>
                                        <div>Rp.{{ number_format($getPoItem->sum('price')) }}</div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="text-black fw-bolder text-uppercase">Total Keseluruhan</div>
                                        <div class="text-black fw-bolder text-uppercase">
                                            Rp.{{ number_format($total)  }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">5</td>
                                <td><b>Cara Pembayaran</b></td>
                                <td>
                                    <p class="p-0 m-0">{{ $companyProfile->bank }}</p>
                                    <p class="p-0 m-0">A/C No: {{ $companyProfile->bank_account_number }}</p>
                                    <p class="p-0 m-0">Nama Akun: {{ $companyProfile->bank_account_name }}</p>
                                    <p>
                                        Atau rekening bank sebagaimana ditentukan di dalam tagihan (invoice)
                                        MAYATAMA.
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="mb-20">
                        Pada tanggal tersebut perkerjaan sudah selesai dikerjakan dan ditest dengan hasil baik. Demikian
                        Berita Acara Serah Terima ini dibuat dan ditandatangani oleh <b>PARA PIHAK</b> dalam rangkap 2
                        (dua)
                        asli yang sama bunyinya, mempunyai kekuatan hukum yang sama dan mengikat <b>PARA PIHAK</b> pada
                        tanggal
                        ditanda tanganinya BAST ini.
                    </p>


                    <div class="d-flex align-items-center justify-content-around">
                        <div class="text-center">
                            <div><b>MY FIBER</b></div>
                            <div style="margin-bottom: 7rem"><b>PT Mayatama Solusindo</b></div>
                            <div>{{ $bast->baa->fab->fabPic->name }}</div>
                            <div>{{ $bast->baa->fab->fabPic->roles[0]?->name ?? '-' }}</div>
                        </div>
                        <div class="text-center">
                            <div><b>PELANGGAN</b></div>
                            <div style="margin-bottom: 7rem"><b>{{ $bast->baa->fab->po->contact->company_name }}</b>
                            </div>
                            <div>{{ $bast->baa->fab->po->contact->pic_name }}</div>
                            <div>{{ $bast->baa->fab->po->contact->pic_position }}</div>
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
                                <a href="{{ url('income-transactions/bast/export-pdf/'. $bast->id) }}"
                                   class="btn btn-light-info w-100 mb-4" target="_blank">
                                    Print PDF
                                </a>
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
                            await showAlert('success', 'Data sukses dikonfirmasi').then(() => {
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
