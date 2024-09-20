@extends('layouts.template')
@section('page-titles', 'Berkas Project Bonus')
@section('content')
    @push('styles')
        <style>
            .pdfobject-container {
                height: 500px;
                border: 1px solid #ccc;
            }
        </style>
    @endpush

    <div class="card-header border-0 pt-6">
        <div class="card-header border-0 pt-10">
            <a class="btn btn-info btn-sm mb-6" href="{{ url('income-transactions/invoice/') }}">Kembali</a>
        </div>
        <div class="card-body py-3">
            <div class="row">
                <div class="col-6">
                    <label for="exampleFormControlInput1" class="required form-label">Berita Acara Aktivasi</label>
                    <div id="baa-pdf"></div>
                </div>
                <div class="col-6">
                    <label for="exampleFormControlInput1" class="required form-label">Berita Acara Serah Terima</label>
                    <div id="bast-pdf"></div>
                </div>
            </div>
        </div>
    </div>


    @push('script')
        <script src="https://unpkg.com/pdfobject"></script>
        <script>
            PDFObject.embed("{{ Storage::url($projectBonus->baa) }}", "#baa-pdf", {
                pdfOpenParams: {
                    pagemode: "thumbs"
                },
            });


            PDFObject.embed("{{ Storage::url($projectBonus->bast) }}", "#bast-pdf", {
                pdfOpenParams: {
                    pagemode: "thumbs",
                },
            });
        </script>
    @endpush
@endsection
