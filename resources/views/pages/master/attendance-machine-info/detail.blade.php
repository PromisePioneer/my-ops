@extends('layouts.template')
@section('page-title', 'Data Cabang')
@section('content')

    <div x-data="attendanceMachineInformationDetail()">
        <a href="{{ url('master/attendance-machine-info/tarik-data-absen/' . $attendanceMachineInformation->id)  }}">Tarik
            Data Absen</a>
    </div>

@endsection

@push('script')
    <script>
        function attendanceMachineInformationDetail() {
            return {}
        }
    </script>
@endpush
