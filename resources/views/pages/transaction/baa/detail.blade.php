@extends('layouts.template')
@section('page-title', 'Detail Berita Acara Aktivasi')
@section('content')
    <div x-data="BaaDetail()" class="align-items-center">
        <div class="card border-top-0 mb-20 w-1000px">
            <div class="card-header p-0 border-0">
                <img class="w-100 img-fluid" src="{{ asset('assets/media/logos/kop-header.png') }}" alt="">
            </div>
            <div class="card-body px-20">
                <div class="text-center mb-19">
                    <h2 class="text-uppercase text-decoration-underline">BERITA ACARA AKTIVASI</h2>
                    <p class="fs-5">Nomor : {{ $baa->baa_number }}</p>
                </div>


                <p class="text-justify ms-10 mb-4">
                    Pada hari ini Kamis, 01 Oktober 2024 yang bertanda tangan dibawah ini:
                </p>

                <div style="padding-left: 5rem; padding-right: 15rem">
                    <table class="table">
                        <tr>
                            <td class="text-start w-3px">Nama</td>
                            <td class="text-center">:</td>
                            <td>{{ $baa->fab->fabPic->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-start">Jabatan</td>
                            <td class="text-center">:</td>
                            <td>{{ $baa->fab->fabPic->roles[0]?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-start">Perusahaan</td>
                            <td class="text-center">:</td>
                            <td>PT MAYATAMA SOLUSINDO</td>
                        </tr>
                    </table>
                </div>

                <p class="text-justify ms-10">
                    Selanjutnya disebut "<b>MYFIBER</b>"
                </p>


                <div style="padding-left: 5rem; padding-right: 15rem">
                    <table class="table mb-4">
                        <tr>
                            <td class="w-3px text-start">Nama</td>
                            <td class="text-center">:</td>
                            <td>{{ $baa->fab->contact->pic_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-start">Perusahaan</td>
                            <td class="text-center">:</td>
                            <td>PT MAYATAMA SOLUSINDO</td>
                        </tr>
                    </table>
                </div>

                <p class="text-justify ms-10">
                    Selanjutnya disebut "<b>Pelanggan</b>"
                </p>


                <p class="text-justify ms-10">
                    Pelanggan dan MYFIBER secara bersama-sama selanjutnya disebut juga “<b>Para Pihak</b>” dengan ini
                    menyatakan bahwa sebagai berikut :
                </p>


                <div style="padding-left: 15rem; padding-right: 15rem">
                    <table class="table table-bordered border-black border-3">
                        <thead>
                        <tr>
                            <th class="w-50 text-center">Deskripsi</th>
                            <th class="text-center">Data</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>Nomor PO</th>
                            <td>{{ $baa->po_number }}</td>
                        </tr>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <td>{{ $baa->fab->contact->company_name }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $baa->fab->contact->complete_address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi Pekerjaan</th>
                            <td>{{ $baa->work_location }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>


                <p class="text-justify lh-lg ms-10 mb-4">
                    Pada tanggal tersebut di bawah Layanan Dedicated telah selesai dipasang dan di uji dengan hasil
                    baik, dan oleh karenanya terhitung sejak tanggal tersebut :
                </p>

                <ol class="text-justify lh-lg ms-10 mb-4">
                    <li>Layanan tersebut sudah dapat digunakan/dioperasikan, dan</li>
                    <li>Seluruh syarat dan ketentuan tersebut diatas berlaku dan mengikat Para Pihak</li>
                </ol>

                <p class="text-justify lh-lg ms-10 mb-20">
                    Demikian Berita Acara ini dibuat dan ditandatangani oleh Para Pihak dalam rangkap 2 (dua) asli yang
                    sama bunyinya, mempunyai kekuatan hukum yang sama dan mengikat Para Pihak pada tanggal
                    ditandatangani BAA.
                </p>


                <div class="d-flex align-items-center justify-content-around">
                    <div class="text-center">
                        <p class="m-1">MYFIBER</p>
                        <p class="mb-20">PT MAYATAMA SOLUSINDO</p>
                        <p class="text-decoration-underline">{{ $baa->fab->fabPic?->name }}</p>
                        <p>{{ $baa->fab->picName->roles[0]?->name ?? '' }}</p>
                    </div>

                    <div class="text-center">
                        <p class="m-1">PELANGGAN</p>
                        <p class="mb-20">{{ $baa->fab->contact->company_name }}</p>
                        <p class="text-decoration-underline">{{ $baa->fab->contact->pic_name }}</p>

                    </div>
                </div>
            </div>


            <div class="separator"></div>

            <div class="d-flex justify-content-between align-items-center px-1">
                @if($baa->status === 0)
                    <div class="p-5 row">
                        <div class="col">
                            <a href="{{ url('income-transactions/baa/edit/' . $baa->id) }}"
                               class="btn btn-light btn-sm btn-active-light-info w-100">Ubah</a>
                        </div>
                        <div class="col">
                            <button type="button" @click="destroy({{ $baa->id }})"
                                    class="btn btn-light btn-sm btn-active-light-danger w-100">Hapus
                            </button>
                        </div>
                    </div>
                @endif
                <div class="px-4">
                    @if($baa->status === 0)
                        <button type="button" class="btn btn-primary btn-sm w-100" @click="confirm({{ $baa->id }})"
                                x-text="buttonLoading ? 'Loading' : 'Konfirmasi BAA'">
                        </button>
                    @endif
                </div>
            </div>

            @if($baa->status === 1)
                <div class="d-flex align-items-center justify-content-between p-3">
                    <a href="{{ url('income-transactions/baa/export-pdf/'. $baa->id) }}"
                       class="btn btn-light-info btn-sm me-3" target="_blank">
                        Print BAA
                    </a>
                    <a href="{{ url('income-transactions/baa/contract-pdf/'. $baa->id) }}"
                       class="btn btn-light-primary btn-sm" target="_blank">
                        Print Kontrak
                    </a>
                </div>
            @endif
        </div>
    </div>
    @include('components.toast')
@endsection

@push('script')
    <script>
        function BaaDetail() {
            return {
                buttonLoading: false,
                async init() {

                },
                async confirm(id) {
                    showConfirmModal("Anda yakin?", "FAB yang sudah di konfirmasi tidak akan dapat dihapus ataupun diubah.", "Konfirmasi", async () => {
                        try {
                            await axios.post(`/income-transactions/baa/confirm/${id}`);
                            await showAlert('success', 'Data sukses dikonfirmasi').then(() => {
                                location.reload();
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/income-transactions/baa/destroy/${id}`);
                            await showAlert('success', 'Data sukses dihapus').then(() => {
                                window.location.href = "/income-transactions/baa"
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
