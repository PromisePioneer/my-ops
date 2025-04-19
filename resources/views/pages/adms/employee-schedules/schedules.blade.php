<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>

        html {
            -webkit-print-color-adjust: exact;
        }

        table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        tr th, td {
            text-align: center;
            border: 1px solid black !important;
        }

        .shift-p {
            background-color: #5C95FF !important;
        }

        .shift-s {
            background-color: #FFA9A3 !important;
        }

        .shift-m {
            background-color: #7E6C6C !important;
        }

        .libur {
            background-color: #0000ff !important;
            color: white !important;
        }

        .fix:first-child {
            position: sticky;
            left: 0;
            width: 200px;
            background-color: white;
            border: 1px solid black !important;
        }

        .date-header {
            font-weight: bold;
            color: black;
            border: 1px solid #000 !important;
        }

        .table-title {
            background-color: #123458;
            color: white;
            text-align: center;
            font-weight: bold;
            padding: 8px;
            margin-bottom: 0;
        }

        .holiday-note {
            background-color: #123458;
            padding: 5px;
            margin-top: 10px;
            border: 1px solid #D4C9BE;
        }

        .py-5 {
            padding-top: 1.25rem !important;
            padding-bottom: 1.25rem !important
        }

        .fs-6 {
            font-size: 1rem !important;
        }

        .text-start {
            text-align: left !important;
        }


        .fs-9 {
            font-size: .75rem !important
        }

        .text-white {
            color: #FFFFFF !important
        }

        .text-dark {
            color: #000000;
        }

        .text-decoration-underline {
            text-decoration: underline !important
        }


        .bg-warning {
            background-color: yellow !important;
        }

        .p-0 {
            padding: 0 !important
        }

    </style>
</head>


<body>

<div>
    <h3 class="table-title">
        JADWAL LIBUR KARYAWAN BULAN
        {{ strtoupper(\Carbon\Carbon::parse($data[0]['date']->last()['period_date'])->translatedFormat('F Y')) }}
    </h3>

    <div class="py-5">
        <div class="">
            <table class="table fs-6">
                <thead>
                <tr class="text-start fw-bolder fs-7 text-uppercase gs-0 fs-9">
                    <th class="px-5 text-dark fix">
                        Nama
                    </th>
                    @foreach($data[0]['date'] as $date)
                        <th class="text-center text-black date-header">
                            {{ \Carbon\Carbon::parse($date['period_date'])->format('d') }}
                        </th>
                    @endforeach
                </tr>
                </thead>
                @foreach($data as $empSchedule)
                    <tbody class="fw-bold p-0">
                    <tr>
                        <td class="text-white fix fs-9"
                            style="background-color: #123458">{{ $empSchedule['name'] }}</td>
                        @foreach($empSchedule['date'] as $date)
                            @php
                                // Default class if none of the conditions match
                                $tdClass = 'text-center border border-black p-0 bg-primary';


                                if (empty($date['leaves']) && empty($date['permission']) && empty($date['sick'])){
                                    $tdClass = "text-center border border-black text-black bg-warning p-0 fw-bolder";
                                }
                                // Handle morning shift (Pagi)
                                 if ($date['schedules_date']?->status === 'L' || $date['is_holiday']) {
                                     $tdClass = 'text-center border border-black text-black bg-warning p-0';
                                 }

                               if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'Pagi') {
                                   $tdClass = 'text-center border border-black shift-p text-white  p-0';
                               }

                               if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'Pagi (Ramadhan)') {
                                   $tdClass = 'text-center border border-black text-white shift-p bg-info p-0';
                               }

                                if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'Lapangan') {
                                    $tdClass = 'text-center border border-black shift-p text-white  p-0';
                                }

                                 if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'Lapangan (Ramadhan)') {
                                     $tdClass = 'text-center border border-black text-white shift-p bg-info p-0';
                                 }

                                  if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'Duri') {
                                      $tdClass = 'text-center border border-black shift-p text-white  p-0';
                                  }


                                  if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'Duri (Ramadhan)') {
                                      $tdClass = 'text-center border border-black text-white shift-p bg-info p-0';
                                  }

                                   if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'Duri (Ramadhan)') {
                                       $tdClass = 'text-center border border-black shift-p text-white  p-0';
                                   }

                                    if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'Lapangan (Ramadhan)') {
                                        $tdClass = 'text-center border border-black text-white shift-p bg-info p-0';
                                    }


                                    if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'Sore') {
                                            $tdClass = 'text-center border border-black shift-s p-0';
                                    }


                                    if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'Malam') {
                                        $tdClass = 'text-center border border-black shift-m p-0';
                                    }

                                     if ($date['schedules_date']?->status === 'H' && $date['schedules_date']->workTime?->name === 'KU Malam') {
                                         $tdClass = 'text-center border border-black shift-m p-0';
                                     }


                                      if (!$date['work_time_schedules'] && !$date['is_holiday'] && !$date['schedules_date']) {
                                            $tdClass = 'text-center border border-black shift-p fw-bolder p-0';
                                        }


                            @endphp
                            <td class="{!! $tdClass !!}">
                                <a href="#"
                                   class="text-dark" style="text-decoration: none"
                                >

                                    @if(!$date['leaves'] && !$date['sick'] && !$date['permission'] && $date['schedules_date']?->status === 'H' && $date['schedules_date']->is_holiday === null)

                                        @if ($date['schedules_date']->workTime?->name === 'Pagi' || $date['schedules_date']->workTime?->name === 'Pagi (Ramadhan)' || $date['schedules_date']->workTime?->name === 'Duri' || $date['schedules_date']->workTime?->name === 'Duri (Ramadhan)' || $date['schedules_date']->workTime?->name === 'Lapangan' ||$date['schedules_date']->workTime?->name === 'Lapangan (Ramadhan)')
                                            {
                                            <span>P</span>
                                        @elseif($date['schedules_date']->workTime?->name === 'Sore')
                                            <span>S</span>

                                        @elseif($date['schedules_date']->workTime?->name === 'Malam' ||
                                        $date['schedules_date']->workTime?->name === 'KU Malam')
                                            <span>M</span>
                                        @else
                                            <span>P</span>
                                        @endif
                                    @endif

                                    @if($date['schedules_date']?->is_holiday === 1 || $date['schedules_date']?->status === 'L')
                                        <span>L</span>
                                    @endif
                                    @if(empty($date['work_time_schedules']) && empty($date['schedules_date']?->is_holiday) && empty($date['schedules_date']) && empty($date['schedules_date']?->leaves) && empty($date['schedules_date']?->leaves) && empty($date['schedules_date']?->permission) && empty($date['schedules_date']?->sick))
                                        <span>P</span>
                                    @endif
                                    @if($date['schedules_date']?->leaves)
                                        <span class="fw-bolder text-dark">C</span>
                                    @endif
                                    @if($date['schedules_date']?->sick)
                                        <span class="fw-bolder text-dark">S</span>
                                    @endif
                                    @if($date['schedules_date']?->permission)
                                        <span class="fw-bolder text-dark">I</span>
                                    @endif
                                    @if($date['schedules_date']?->important_leaves)
                                        <span class="fw-bolder text-dark">CP</span>
                                    @endif
                                </a>
                            </td>
                        @endforeach
                    </tr>
                    </tbody>
                @endforeach

            </table>
        </div>
        <div class="holiday-note mt-3">
            @foreach($nationalHoliday as $holiday)
                <p class="mb-1 text-white">{{ $holiday['date'] }} : {{ $holiday['description'] }}</p>
            @endforeach
        </div>
    </div>
</div>
</body>
</html>
