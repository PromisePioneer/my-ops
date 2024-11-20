@php use function App\Helper\formatDate; @endphp
@extends('layouts.template')
@section('content')
    @push('styles')
        <style>
            .w-240px {
                width: 240px;
            }

            #check {
                accent-color: green !important;
            }
        </style>
    @endpush

    <div x-data="FABDetail">
        @include('pages.transaction.fab.modal.jurnal-entry')
        <div class="card border-top-0 mb-20">
            <div class="card-header p-0 border-0">
                <img class="w-100 img-fluid" src="{{ asset('assets/media/logos/kop-header.png') }}" alt="">
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-justify">
                            <b>PT. MAYATAMA SOLUSINDO</b>
                            <br>
                            JL. Sultan Hasanuddin No. 8A Kel. Rimba Sekampung Kec. Dumai Kota 28822 - Dumai, Riau
                            Indonesia <br>
                            +62-853-6579-9998 <br>
                            https://www.mayatama.id
                        </p>
                    </div>
                    <div class="d-flex flex-column mt-n3">
                        <table>
                            <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>:</th>
                                <th>
                                    <div class="border border-3 border-black p-1 w-250px">
                                        {{ $fab->fab_number }}
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <th>:</th>
                                <th>
                                    <div class="border border-3 border-black p-1">
                                        {{ formatDate($fab->date) }}
                                    </div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="text-center fw-bolder text-uppercase mt-1 mb-10 text-black"
                     style="background-color: #7dbbf5">
                    FORMULIR BERLANGGANAN
                </div>
                <div class="row ms-n2 mb-4">
                    <div class="col-lg-4">
                        <p class="fw-bolder" style="color: #172d69">
                            <i class="bi bi-square-fill fs-9 text-danger"></i> Layanan :
                        </p>
                    </div>

                    <div class="col-lg-6">
                        <div class="col-lg-12 fv-row">
                            <div class="row">
                                @foreach($serviceCategories as $service)
                                    <div class="col-md-5 mt-2">
                                        <input id="check"
                                               type="checkbox"
                                               class="form-check-input"
                                               value="{{ $service->id }}"
                                               {{ in_array($service->id, $test) ? 'checked' : '' }}  onclick="return false;"/>
                                        <label class="fw-bold">
                                            <span>{{ $service->name }}</span>
                                        </label>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row ms-n2 align-items-center">
                    <p class="fw-bolder" style="color: #172d69">
                        <i class="bi bi-square-fill fs-9 text-danger"></i> Data Perusahaan :
                    </p>
                </div>
                <div class="row ms-1 mb-1 align-items-center">
                    <div class="col-lg-4">
                        Nama Perusahaan :
                    </div>
                    <div class="col-lg-6">
                        <div class="border border-3 border-black p-1 w-250px">
                            {{ $fabCompanyName }}
                        </div>
                    </div>
                </div>
                <div class="row ms-1 mb-1">
                    <div class="col-lg-4 align-items-center">
                        Alamat :
                    </div>
                    <div class="col-lg-6">
                        <div
                            class="{{ $fab->po->contact->complete_address ? 'border border-3 border-black p-1 w-100 mb-1' : 'border border-3 border-black  w-100 p-4 mb-1' }}">
                            {{ $fab->po->contact->complete_address ?? '' }}
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                Phone
                            </div>
                            <div class="me-4">
                                <div
                                    class="{{ $fab->po->contact->phone_number ? 'border border-3 border-black w-240px p-1' : 'border border-3 border-black  w-240px p-4' }}">
                                    {{ $fab->po->contact->phone_number }}
                                </div>
                            </div>
                            <div class="me-3">
                                Fax
                            </div>
                            <div class="me-3">
                                <div
                                    class="{{ $fab->po->contact->fax ? 'border border-3 border-black w-240px mb-1 p-1' : 'border border-3 border-black  w-250px p-4 mb-1' }}">
                                    {{ $fab->po->contact->fax }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ms-1 mb-1 align-items-center">
                    <div class="col-lg-4">
                        <p>NPWP Perusahaan :</p>
                    </div>
                    <div class="col-lg-6">
                        <div
                            class="{{ $fab->po->contact->npwp ? 'border border-3 p-1 border-black w-100 mb-1' : 'border border-3 border-black  w-100 p-4 mb-1' }}">
                            {{ $fab->po->contact->npwp }}
                        </div>
                    </div>
                </div>
                <div class="row ms-1 mb-1 align-items-center">
                    <div class="col-lg-4">
                        <p>No.KTP/SIM/PASSPORT :</p>
                    </div>
                    <div class="col-lg-6">
                        <div
                            class="{{ $fab->po->contact->identity_number ? 'border border-3 p-1 border-black w-100 mb-1' : 'border border-3 border-black  w-100 p-4 mb-1' }}">
                            {{ $fab->po->contact->identity_number }}
                        </div>
                    </div>
                </div>
                <div class="row ms-n2 mt-10 align-items-center">
                    <p class="fw-bolder" style="color: #172d69">
                        <i class="bi bi-square-fill fs-9 text-danger"></i>
                        Keterangan :
                    </p>
                </div>
                <div class="row justify-content-center table-responsive px-10">
                    <table class="table text-center">
                        <thead>
                        <tr class="border-bottom border-top border-black fs-6 fw-bold text-black ms-5"
                            style="background-color: #7dbbf5">
                            <th class="px-1 w-1px">No</th>
                            <th>Deskripsi</th>
                            <th>Kapasitas</th>
                            <th>Harga</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fabHasServiceCategories as $serviceCategories)
                            <tr class="border-bottom border-top border-black fs-6">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $serviceCategories->service->name }}</td>
                                <td>{{ $serviceCategories->capacity .' '.  $serviceCategories->unitType->name }}</td>
                                <td>{{ number_format($serviceCategories->price) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="border-bottom border-top border-black fs-6">
                        <tr class="border-bottom border-top border-black fs-6">
                            <td colspan="3" class="text-end p-3">PPN</td>
                            <td class="py-3">{{ number_format($totalPPN, 2) }}</td>
                        </tr>
                        <tr class="border-bottom border-top border-black fs-6 fw-bold ms-5">
                            <td colspan="3" class="text-end fw-bolder">Total</td>
                            <td>{{ number_format($total, 2) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <ul class="fa-ul px-3 mb-10">
                    <li>
                        <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
                        SLA 99,5%
                    </li>
                    <li>
                        <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
                        Support Pelayanan 7 x 24 jam, online maupun onsite.
                    </li>
                    <li>
                        <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
                        Masa berlaku penawaran 1 bulan
                    </li>
                    <li>
                        <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
                        Minimum kontrak 1 tahun dan otomatis diperpanjang apabila tidak ada permintaan berhenti
                        berlangganan
                    </li>

                    @if(!empty($fabHasSKL))
                        @foreach($fabHasSKL as $skl)
                            <li>
                                <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
                                {{ $skl->skl?->name }}
                            </li>
                        @endforeach
                    @endif
                </ul>


                <div class="row ms-n2 fw-bolder">
                    <p style="color: #172d69" class="m-0">
                        <i class="bi bi-square-fill fs-9 text-danger"></i> Tata Cara Pembayaran
                    </p>
                </div>
                <div class="row ms-1  ms-4 mb-10 mt-0">
                    <p class="fw-bold mb-1">Pembayaran dapat dilakukan paling lambat pada tanggal 30 setiap bulannya,
                        dengan cara
                        transfer
                        ke rekening di :</p>
                    <table class="ms-3 mt-0 mb-1 w-400px">
                        <tr>
                            <td class="px-1">Bank</td>
                            <td>:</td>
                            <td>{{ $companyProfile->bank }}</td>
                        </tr>
                        <tr>
                            <td>Nomor Rekening</td>
                            <td>:</td>
                            <td>{{ $companyProfile->bank_account_number }}</td>
                        </tr>
                        <tr>
                            <td>Atas Nama</td>
                            <td>:</td>
                            <td class="">{{ $companyProfile->name }}</td>
                        </tr>
                    </table>
                    <p>Atau Sesuai yang tercantum dalam Kontrak</p>
                </div>


                <div class="d-flex justify-content-around">
                    <div class="text-center">
                        <h6 style="margin-bottom: 100px">{{ $fabCompanyName }}</h6>
                        <h6>{{ $fab->po->contact->pic_name }}</h6>
                        <h6>{{ $fab->po->contact->pic_position }}</h6>
                    </div>
                    <div class="text-center">
                        <h6 style="margin-bottom: 100px">PT Mayatama Solusindo</h6>
                        <h6>{{ $fab->user->name }}</h6>
                        <h6>{{ $fab->user->roles[0]?->name ?? '' }}</h6>
                    </div>
                </div>
            </div>


            <div class="separator"></div>

            <div class="d-flex justify-content-between align-items-center px-1">
                @if($fab->status === 0)
                    <div class="p-5 row ">
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
                <div class="px-3">
                    @if($fab->status === 0)
                        <button type="button" class="btn btn-primary" @click="confirm()"
                                x-text="buttonLoading ? 'Loading' : 'Konfirmasi FAB'">
                            Konfirmasi
                        </button>
                    @endif
                </div>
            </div>

            @if($fab->status === 1)
                <div class="d-flex align-items-center justify-content-between p-3">
                    <a href="{{ url('income-transactions/fab/export-pdf/'. $fab->id) }}"
                       class="btn btn-light-info me-3 btn-sm" target="_blank">
                        Print FAB
                    </a>
                    <a href="{{ url('income-transactions/fab/contract-pdf/'. $fab->id) }}"
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
                    let IDR = new Intl.NumberFormat('en-ID',
                            {
                                style: 'currency',
                                currency:
                                    "IDR"
                            }
                        )
                    ;

                    return IDR.format(curr);
                },
            }
        }
    </script>
@endpush
