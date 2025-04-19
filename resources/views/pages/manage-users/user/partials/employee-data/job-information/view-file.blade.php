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
            <a class="btn btn-info btn-sm mb-6" href="{{ url('manage-users/users/detail/'. $user->id) }}">Kembali</a>
        </div>
        <div class="card-body py-3">
            <div class="row">
                <div class="col-6">
                    <label for="exampleFormControlInput1" class=" form-label">File SK</label>
                    <div id="sk-pdf"></div>
                </div>
                <div class="col-6">
                    <label for="exampleFormControlInput1" class=" form-label">File Kontrak Pegawai</label>
                    <div id="contract-pdf"></div>
                </div>
            </div>
        </div>
    </div>


    @push('script')
        <script src="https://unpkg.com/pdfobject"></script>
        <script>
            PDFObject.embed("{{ Storage::url($user->sk_file) }}", "#sk-pdf", {
                pdfOpenParams: {
                    pagemode: "thumbs"
                },
            });

            PDFObject.embed("{{ Storage::url($user->contract_file) }}", "#contract-pdf", {
                pdfOpenParams: {
                    pagemode: "thumbs"
                },
            });
        </script>
    @endpush
@endsection
