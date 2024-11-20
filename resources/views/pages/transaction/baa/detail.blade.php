@extends('layouts.template')
@section('page-title', 'Detail Berita Acara Aktivasi')
@section('content')
    <div x-data="BaaDetail()" class="align-items-center">
        @include('pages.transaction.baa.spk.create')
        <div class="card border-top-0 mb-20 w-1000px">
            <div class="card-header p-0 border-0">
                <img class="w-100 img-fluid" src="{{ asset('assets/media/logos/kop-header.png') }}" alt="">
            </div>
            <div class="card-body px-20">
                <div class="text-center mb-20">
                    <h2 class="text-uppercase text-decoration-underline fs-1">BERITA ACARA AKTIVASI</h2>
                    <p class="fs-2">Nomor : {{ $baa->baa_number }}</p>
                </div>


                <p class="text-justify ms-10 mb-4 fs-5">
                    Pada hari ini Kamis, 01 Oktober 2024 yang bertanda tangan dibawah ini:
                </p>

                <div style="padding-left: 5rem; padding-right: 15rem" class="fs-5">
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

                <p class="text-justify ms-10 fs-5">
                    Selanjutnya disebut "<b>MYFIBER</b>"
                </p>


                <div style="padding-left: 5rem; padding-right: 15rem" class="fs-5">
                    <table class="table mb-4">
                        <tr>
                            <td class="w-3px text-start">Nama</td>
                            <td class="text-center">:</td>
                            <td>{{ $baa->fab->po->contact->pic_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-start">Perusahaan</td>
                            <td class="text-center">:</td>
                            <td>PT MAYATAMA SOLUSINDO</td>
                        </tr>
                    </table>
                </div>

                <p class="text-justify ms-10 fs-5">
                    Selanjutnya disebut "<b>Pelanggan</b>"
                </p>


                <p class="text-justify ms-10 fs-5">
                    Pelanggan dan MYFIBER secara bersama-sama selanjutnya disebut juga “<b>Para Pihak</b>” dengan ini
                    menyatakan bahwa sebagai berikut :
                </p>


                <div style="padding-left: 15rem; padding-right: 15rem" class="fs-5">
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
                            <td>{{ $baa->fab->po->contact->company_name }}</td>
                        </tr>
                        <tr>
                            <th>Layanan</th>
                            <td>{{ $serviceCategory }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $baa->fab->po->contact->complete_address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi Pekerjaan</th>
                            <td>{{ $baa->work_location }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>


                <p class="text-justify lh-lg ms-10 mb-4 fs-5">
                    Pada tanggal tersebut di bawah Layanan Dedicated telah selesai dipasang dan di uji dengan hasil
                    baik, dan oleh karenanya terhitung sejak tanggal tersebut :
                </p>

                <ol class="text-justify lh-lg ms-10 mb-4 fs-5">
                    <li>Layanan tersebut sudah dapat digunakan/dioperasikan, dan</li>
                    <li>Seluruh syarat dan ketentuan tersebut diatas berlaku dan mengikat Para Pihak</li>
                </ol>

                <p class="text-justify lh-lg ms-10 mb-20 fs-5">
                    Demikian Berita Acara ini dibuat dan ditandatangani oleh Para Pihak dalam rangkap 2 (dua) asli yang
                    sama bunyinya, mempunyai kekuatan hukum yang sama dan mengikat Para Pihak pada tanggal
                    ditandatangani BAA.
                </p>


                <div class="d-flex align-items-center justify-content-around fs-5">
                    <div class="text-center">
                        <p class="m-1">MYFIBER</p>
                        <p class="mb-20"><b>PT MAYATAMA SOLUSINDO</b></p>
                        <p class="text-decoration-underline"><b>{{ $baa->fab->fabPic?->name }}</b></p>
                        <p><b>{{ $baa->fab->picName->roles[0]?->name ?? '' }}</b></p>
                    </div>

                    <div class="text-center fs-5">
                        <p class="m-1">PELANGGAN</p>
                        <p class="mb-20"><b>{{ $baa->fab->po->contact->company_name }}</b></p>
                        <p class="text-decoration-underline"><b>{{ $baa->fab->po->contact->pic_name }}</b></p>

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

                    <div class="d-flex">
                        <button data-bs-toggle="modal"
                                data-bs-target="#spk-create"
                                class="btn btn-light-info btn-sm me-3" @click="getSpk()"
                        >
                            Buat SPK / Ubah SPK
                        </button>

                        @if(!empty($baa->spk))

                            <a href="{{ url('income-transactions/baa/spk/export-pdf', $baa->id) }}"
                               class="btn btn-light-danger btn-sm me-3" @click="getSpk()" target="__blank"
                            >
                                Print SPK
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    @include('components.toast')
@endsection

@push('script')
    <script>
        $('.date').flatpickr();

        function BaaDetail() {
            return {
                formSpk: document.getElementById('form-spk-create'),
                id: "{{ $baa->id }}",
                buttonLoading: false,
                spkVal: null,
                async init() {
                    await this.getUserData();
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
                async getSpk() {
                    const resp = await axios.get(`/income-transactions/baa/get-spk/${this.id}`);
                    this.spkVal = resp.data;

                    await this.selectedFrom();
                    await this.selectedTo();
                },

                async selectedFrom() {
                    const selectedFrom = $('#selectedFrom');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/income-transactions/baa/get-selected-from/${this.spkVal.from}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedFrom.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async selectedTo() {
                    const selectedTo = $('#selectedTo');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/income-transactions/baa/get-selected-to/${this.spkVal.to}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedTo.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async getUserData() {
                    $(".users-select2").select2({
                        placeholder: "Pilih Karyawan",
                        allowClear: true,
                        ajax: {
                            url: '/income-transactions/baa/users/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async saveSPK() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/income-transactions/baa/save-spk/${this.id}`, new FormData(this.formSpk))
                        await showAlert('success', 'Data sukses disimpan')
                            .then(() => window.location.reload());
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
