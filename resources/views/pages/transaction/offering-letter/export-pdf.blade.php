<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        * {
            margin: 0;
        }

        body {
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            font-family: Poppins, Helvetica, sans-serif;
            font-size: 62.5%;
        }

        .kop-header {
            margin: 0 auto;
        }

        .kop-image-header {
            width: 100%;
        }

        .kop-image-footer {
            width: 100%;
            margin-bottom: 100px;
        }

        .container {
            margin: 20px 20px 60px 20px;
        }

        .wrapper {
            position: relative;
            min-height: 100%;
            margin-bottom: -100px; /* Adjust based on footer height */
        }

        .heading-text {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .foreword {
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, tr, th, td {
            border: 1px solid;
            padding: 10px;
        }

        .bg-primary {
            background-color: rgb(0, 158, 247);
        }

        .text-end {
            text-align: right;
        }

        .foreword {
            line-height: 25px;
        }

        .notes {
            font-size: 13px;
        }

        .signature {
            font-size: 13px;
            padding-right: 40px;
            font-weight: bold;
            float: right;
        }

        .text-center {
            text-align: center;
        }

        .kop-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }


    </style>


</head>

<body>
<div class="wrapper">
    <div class="kop-header">
        @if(isset($letter_head->header) && $letter_head->header)
            <img class='img-fluid w-100' src="{{ Storage::url($letter_head->header) }}" alt=""/>
        @else
            <img
                 src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-headers.png'))) }}"
                 alt=""/>
        @endif

    </div>

    <div class="container">
        <div class="heading-text">
            <p>No Surat: {{ $offeringLetter->offering_number }}</p>
            <p>Lampiran: {{ $offeringLetter->attachment }}</p>
            <p>Tanggal: {{ $offeringLetter->date }}</p>
        </div>

        <div class="foreword">
            <p>Kepada Yth, {{ $offeringLetter->contact->full_name }}</p>
            <p>{!! $offeringLetter->foreword !!}</p>
        </div>


        <table>
            <thead>
            <tr class="bg-primary">
                <th>No</th>
                <th>Layanan</th>
                <th>Kapasitas</th>
                <th>Qty</th>
                <th>Jumlah</th>
                <th>@</th>
            </tr>
            </thead>
            <tbody>
            @foreach($offeringLetterServices as $service)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $service->serviceCategory->name }}</td>
                    <td>{{ $service->serviceCategory->capacity }} Mbps</td>
                    <td>{{ $service->qty }}</td>
                    <td>Rp.{{ number_format($service->unit_price) }}</td>
                    <td>Rp. {{ number_format($service->total_price) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" class="text-end">TOTAL</td>
                <td class="py-1">
                    Rp. {{ number_format($offeringLetterServices->sum('total_price')) }}</td>
            </tr>
            </tfoot>
        </table>

        <div class="notes">
            <p>
                {!! $offeringLetter->notes !!}
            </p>
        </div>
    </div>

    <div class="signature">
        <p class="text-center">PT. MAYATAMA SOLUSINDO <br><br><br><br><br>Marketing
            <br>{{ $offeringLetter->marketing_agent_name }}<br>{{ $offeringLetter->marketing_agent_contact}}
        </p>
    </div>

    <div class="kop-footer">
        <img class="kop-image-footer"
             src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"/>
    </div>
</div>
</body>
</html>
