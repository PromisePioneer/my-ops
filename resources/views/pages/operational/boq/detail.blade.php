@php use function App\Helper\formatDate; @endphp
@extends('layouts.template')
@section('page-title', 'Detail BoQ')
@section('content')

    <div x-data="boqDetail()">
        <div class="card">
            <div class="card-header card-header-stretch">
                <h3 class="card-title fw-bold">{{ $boq->boq_number }}</h3>
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                        <li class="nav-item">
                            <a class="nav-link fw-bold active" data-bs-toggle="tab" href="#rab">RAB</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" data-bs-toggle="tab" href="#projectTimeline">
                                Waktu Pekerjaan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" data-bs-toggle="tab" href="#attachment">Lampiran</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="rab" role="tabpanel">
                        <div class="d-flex justify-content-between mb-10">
                            <h6>{{ $boq->title }}</h6>
                            <h6>{{ formatDate($boq->date) }}</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm gs-7 gy-7 gx-7 border border-dark">
                                <thead>
                                <tr class="fw-bold text-center" style="background-color: #fabf8f">
                                    <td>No</td>
                                    <td>Barang</td>
                                    <td>Merk</td>
                                    <td>Jumlah</td>
                                    <td>Satuan</td>
                                    <td>Harga Satuan</td>
                                    <td>Total Harga</td>
                                    <td>Estimasi Penggunaan Barang</td>
                                    <td>Keterangan</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($boqCommodity as $commodity)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $commodity->name }}</td>
                                        <td class="text-center">{{ $commodity->merk }}</td>
                                        <td class="text-center">{{ $commodity->qty }}</td>
                                        <td class="text-center">{{ $commodity->unitType->name }}</td>
                                        <td class="text-center">Rp.{{ number_format($commodity->unit_price, 2)}}</td>
                                        <td class="text-center">Rp.{{ number_format($commodity->total_price , 2) }}</td>
                                        <td class="text-center">{{ formatDate($commodity->used_estimation) }}</td>
                                        <td class="text-center">{{ $commodity->description }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr style="background-color: #92d050" class="fw-bold">
                                    <td colspan="6" class="text-center">Total Harga</td>
                                    <td colspan="1" class="text-center">
                                        Rp.{{ number_format($boqCommodity->sum('total_price')) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-10">
                            <div class="text-center">
                                <h6 class="mb-10">Mengajukan</h6>
                                {!! QrCode::size(100)->generate(url('inventory/boq/detail/' . $boq->id)) !!}
                                <p class="mt-10">{{ $boq->submitterName->name }}</p>
                                <h6>{{ $boq->submitterName->name }}</h6>
                            </div>

                            <div class="text-center">
                                <h6 class="mb-10">Menyetujui</h6>
                                @if($boq->approved_by_operational_manager === 1)
                                    {!! QrCode::size(100)->generate(url('inventory/boq/detail/' . $boq->id)) !!}
                                @endif
                                <p class="mt-10">{{ $operationalManager?->name }}</p>
                                <h6>{{ $operationalManager->roles[0]?->name ?? '' }}</h6>
                            </div>

                            <div class="text-center">
                                <h6 class="mb-10">Mengetahui</h6>
                                <div class="d-flex">
                                    <div class="me-10 mb-10">
                                        @if($boq->known_by_director === 1)
                                            {!! QrCode::size(100)->generate(url('inventory/boq/detail/' . $boq->id)) !!}
                                        @endif
                                        <p class="mt-10">{{ $director?->name }}</p>
                                        <h6>{{ $director?->roles[0]?->name  }}</h6>
                                    </div>
                                    <div class="me-10 mb-10">
                                        @if($boq->known_by_gm === 1)
                                            {!! QrCode::size(100)->generate(url('inventory/boq/detail/' . $boq->id)) !!}
                                        @endif
                                        <p class="mt-10">{{ $generalManager?->name }}</p>
                                        <h6>{{ $generalManager?->roles[0]?->name  }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="projectTimeline" role="tabpanel">
                        <table class="table table-bordered table-sm gs-7 gy-7 gx-7 border border-dark">
                            <thead>
                            <tr class="fw-bold text-center" style="background-color: #fabf8f">
                                <td>No</td>
                                <td>Nama Pekerjaan</td>
                                <td>Qty</td>
                                <td>Uom</td>
                                <td>Tgl. Mulai</td>
                                <td>Tgl. Selesai</td>
                                <td>Nama PIC</td>
                                <td>Jumlah Teknisi</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($boqProjectTimeline as $projectTimeline)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $projectTimeline->name }}</td>
                                    <td class="text-center">{{ $projectTimeline->qty }}</td>
                                    <td class="text-center">{{ $projectTimeline->uom }}</td>
                                    <td class="text-center">{{ $projectTimeline->start_date }}</td>
                                    <td class="text-center">{{ $projectTimeline->end_date }}</td>
                                    <td class="text-center">{{ $projectTimeline->picName->name }}</td>
                                    <td class="text-center">{{ $projectTimeline->technician }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="attachment" role="tabpanel">
                        <div id="attachment-pdf" style="height: 1000px"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                @if($boq->approved_by_operational_manager === 0 && Auth::user()->roles[0]?->name === 'Operational Manager')
                    <button class="float-end btn btn-light-primary btn-sm" @click="approvedByOperationalManager()">
                        Setujui
                    </button>
                @endif
                @if($boq->approved_by_operational_manager === 1  && $boq->known_by_director === 0 && Auth::user()->roles[0]?->name === 'Director')
                    <button class="float-end btn btn-light-primary btn-sm" @click="knownByDirector()">
                        Mengetahui Direktur
                    </button>
                @endif
                @if($boq->known_by_director === 1 && $boq->known_by_gm === 0 && Auth::user()->roles[0]?->name === 'General Manager')
                    <button class="float-end btn btn-light-primary btn-sm" @click="knownByGm()">
                        Mengetahui Manager Umum
                    </button>
                @endif
            </div>
        </div>

    </div>
    @include('components.toast')
@endsection
@push('script')
    <script src="https://unpkg.com/pdfobject"></script>
    <script>
        PDFObject.embed("{{ Storage::url($boq->attachment) }}", "#attachment-pdf", {
            pdfOpenParams: {
                pagemode: "thumbs"
            },
        });
    </script>

    <script>
        function boqDetail() {
            return {
                buttonLoading: false,
                id: {{ $boq->id }},
                async init() {

                },
                async approvedByOperationalManager() {
                    showConfirmModal("Anda yakin?", "BoQ yang sudah di Setujui tidak bisa diubah ataupun dihapus.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/inventory/boq/approved-by-operational-manager/${this.id}`);
                            await showAlert('success', 'Data sukses Dikonfirmasi').then(() => {
                                // location.reload()
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async knownByDirector() {
                    showConfirmModal("Anda yakin?", "BoQ yang sudah di Setujui tidak bisa diubah ataupun dihapus.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/inventory/boq/known-by-director/${this.id}`);
                            await showAlert('success', 'Data sukses Dikonfirmasi').then(() => {
                                // location.reload()
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async knownByGm() {
                    showConfirmModal("Anda yakin?", "BoQ yang sudah di Setujui tidak bisa diubah ataupun dihapus.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/inventory/boq/known-by-gm/${this.id}`);
                            await showAlert('success', 'Data sukses Dikonfirmasi').then(() => {
                                // location.reload()
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                }
            }
        }
    </script>
@endpush