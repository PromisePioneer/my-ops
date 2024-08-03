@extends('layouts.template')
@section('page-title', 'Buku Besar')
@section('content')
    <div x-data="bigBook">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="row py-5">
                    <template x-for="row in bukuBesar">
                        <div class="col-md-6" style="margin: 5px auto">
                            <a :href="`/journals/general-ledger/detail/${row.id}`" class="btn btn-info btn-lg w-100"
                               x-text="row.name"></a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function bigBook() {
            return {
                bukuBesar: [],
                startIndex: null,
                async init() {
                    const bukuBesar = await axios.get('/journals/general-ledger/data');
                    this.bukuBesar = bukuBesar.data;
                },
            }
        }
    </script>
@endpush
