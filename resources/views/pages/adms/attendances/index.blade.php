@extends('layouts.template')
@section('page-title', 'Data Kehadiran')
@section('content')

    <table>
        <thead>
        <tr>
            <th>sn</th>
            <th>table</th>
            <th>stamp</th>
            <th>employee_id</th>
            <th>timestamp</th>
            <th>status1</th>
            <th>status2</th>
            <th>status3</th>
            <th>status4</th>
            <th>status5</th>
        </tr>
        </thead>
        <tbody>
        @foreach($attendance as $att)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $att->table }}</td>
                <td>{{ $att->stamp }}</td>
                <td>{{ $att->employee_id }}</td>
                <td>{{ $att->timestamp }}</td>
                <td>{{ $att->status1 }}</td>
                <td>{{ $att->status2 }}</td>
                <td>{{ $att->status3 }}</td>
                <td>{{ $att->status4 }}</td>
                <td>{{ $att->status5 }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection