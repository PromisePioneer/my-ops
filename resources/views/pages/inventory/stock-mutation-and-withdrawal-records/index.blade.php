@extends('layouts.template')
@section('content')
    <div x-data="">

    </div>
@endsection
@push('script')
    <script>
        function stockMutationAndWithdrawalRecord() {
            return {
                isLoading: false,
                stockWithdrawalItem: [],
                stockMutationItem: [],
            }
        }
    </script>
@endpush
