@extends('layouts.template')
@section('content')
    @push('styles')
        <style>
            .pdfobject-container {
                height: 700px;
                border: 1px solid #ccc;
            }
        </style>
    @endpush


    <div class="card-header border-0 pt-6">
        <div class="card-header border-0 pt-10">
            <a class="btn btn-info btn-sm mb-6" href="{{ url()->previous() }}">Kembali</a>
        </div>
        <div class="card-body py-3">
            <label for="exampleFormControlInput1" class=" form-label">File Sertifikat</label>
            <div id="certificate"></div>
        </div>
    </div>


    @push('script')
        <script src="https://unpkg.com/pdfobject"></script>
        <script>
            PDFObject.embed("{{ Storage::url($educationCertificate->file) }}", "#certificate", {
                pdfOpenParams: {
                    pagemode: "thumbs"
                },
            });
        </script>
    @endpush
@endsection
