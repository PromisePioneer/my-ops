@extends('layouts.template')
@section('page-titles', 'Berkas BAST')
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
            <a class="btn btn-info btn-sm mb-6" href="{{ url('income-transactions/bast/') }}">Kembali</a>
        </div>
        <div class="card-body py-3">
            <div id="my-pdf"></div>
        </div>
    </div>


    @push('script')
        <script src="https://unpkg.com/pdfobject"></script>
        <script>PDFObject.embed("{{ Storage::url($bast->file) }}", "#my-pdf", {
                pdfOpenParams: {
                    pagemode: "thumbs"
                },
            });
        </script>
    @endpush
@endsection
