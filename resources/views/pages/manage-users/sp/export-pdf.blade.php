@php use SimpleSoftwareIO\QrCode\Facades\QrCode; @endphp
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Peringatan #{{ $sp->sp_number }}</title>

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
            margin-bottom: 20px;
        }

        .kop-image-footer {
            width: 100%;
            margin-bottom: 100px;
        }

        .wrapper {
            position: relative;
            min-height: 100%;
            margin-bottom: -100px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-heading-container {
            float: left;
            display: inline-block;
        }

        .table-heading .table-data-heading {
            /*border: 1px solid;*/
            border: none !important;
            margin-bottom: 100px;
            width: 50%;
            text-align: left;
        }

        .heading-toolbar {
            text-align: center;
            font-size: 15px;
            font-weight: 400;
        }

        .clearfix {
            clear: both;
            margin-bottom: 40px;
        }


        .table-heading .table-data-heading {
            /*border: 1px solid;*/
            border: none !important;
            margin-bottom: 100px;
            width: 50%;
            text-align: left;
        }


        .kop-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }


        .table-heading-container {
            width: 48%;
            margin-right: 10%;
            float: left;
            display: inline-block;
        }

        .clearfix {
            clear: both;
            margin-bottom: 40px;
        }

        .table-heading .table-data-heading {
            /*border: 1px solid;*/
            border: none !important;
            margin-bottom: 100px;
            width: 50%;
            text-align: left;
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

        .heading-separator {
            margin-bottom: 0;
        }
    </style>
</head>

<body>


@php
    $super = '';
    if ($sp->sp_type === 'SP-1'){
        $super = 'Pertama';
    }elseif ($sp->sp_type === 'SP-2'){
        $super = 'Kedua';
    }else{
        $super = 'Ketiga';
    }
@endphp

<div class="wrapper">
    <div class="kop-header">
        <img class="kop-image-header"
             src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-header.png'))) }}"/>
    </div>
    <div class="heading-toolbar">
        <p style="font-size: 26px"><u>Surat Peringatan {{ $super }} ({{ $sp->sp_type }})</u></p>
        <p style="font-size: 17px">Nomor : {{ $sp->sp_number }}</p>
    </div>
    <p style="font-size: 13px; margin: 10px 40px 0 30px;">Surat peringatan ini ditujukan kepada : </p>
    <div style="margin: 0 30px 0 40px">
        <div class="heading-separator table-heading-container">
            <table class="table-heading">
                <tbody>
                <tr>
                    <td class="table-data-heading" style="font-size: 13px;">
                        Nama
                    </td>
                    <td class="table-data-heading" style="font-size: 13px; text-align: left;">
                        :
                    </td>
                    <td class="table-data-heading" style="font-size: 13px;width: 100%">
                        {{ $sp->user->name }}
                    </td>
                </tr>
                <tr>
                    <td class="table-data-heading" style="font-size: 13px;">
                        <p class="heading-text">NIK</p>
                    </td>
                    <td class="table-data-heading" style="font-size: 13px; width: 1px; text-align: left;">
                        :
                    </td>
                    <td class="table-data-heading" style="font-size: 13px; width: 100%">
                        {{ $sp->user->nip }}
                    </td>
                </tr>
                <tr>
                    <td class="table-data-heading" style="font-size: 13px;width: 90px">
                        <p class="heading-text">Jabatan</p>
                    </td>
                    <td class="table-data-heading" style="font-size: 13px; width: 1px; text-align: left;">
                        :
                    </td>
                    <td class="table-data-heading" style="font-size: 13px; width: 100%">
                        {{ $sp?->user?->roles?->first()?->name  }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="clearfix"></div>

    <div style="margin: 0 30px 0 40px; font-size: 13px">
        <p>{!! $sp->description !!}</p>
    </div>

    <br>

    @if($punishedBy->hasAnyRole(['Manager Keuangan', 'Direktur', 'Manager Operasional']))
        <div style="margin-right: 30px;float: right">
            <table>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">Yang Memberi Sanksi:</p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">
                            <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::size(100)->generate(url('/manage-users/sp/export-pdf/' . $sp->id))) !!} "
                                    width="100px" height="70px">
                        </p>
                    </th>
                    <th style="text-align: center; padding: 8px;">
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px 8px 0 8px;">
                        <p style="font-size: 12px; margin: 0; text-decoration: underline">
                            {{ $punishedBy?->name }}
                        </p>
                    </th>
                </tr>
                <tr style="padding: 0">
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">{{ $punishedBy?->roles[0]?->name }}</p>
                    </th>
                </tr>
            </table>
        </div>

    @endif

    @if($punishedBy->hasAnyRole(['Manager Cabang']))
        <div class="heading-separator table-heading-container">
            <table>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">Yang Memberi Sanksi:</p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">
                            <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::size(100)->generate(url('/manage-users/sp/export-pdf/' . $sp->id))) !!} "
                                    width="100px" height="70px">
                        </p>
                    </th>
                    <th style="text-align: center; padding: 8px;">
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px 8px 0 8px;">
                        <p style="font-size: 12px; margin: 0; text-decoration: underline">
                            {{ $punishedBy?->name }}
                        </p>
                    </th>
                </tr>
                <tr style="padding: 0">
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">{{ $punishedBy?->roles[0]?->name }}</p>
                    </th>
                </tr>
            </table>
        </div>
        <div class="heading-separator table-heading-container">
            <table>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">Yang Mengetahui:</p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">
                            <img
                                    src="data:image/png;base64, {!! base64_encode(QrCode::size(100)->generate(url('/manage-users/sp/export-pdf/' . $sp->id))) !!} "
                                    width="100px" height="70px">
                        </p>
                    </th>
                    <th style="text-align: center; padding: 8px;">
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px 8px 0 8px;">
                        <p style="font-size: 12px; margin: 0; text-decoration: underline">
                            {{ $operationalManager?->name }}
                        </p>
                    </th>
                </tr>
                <tr style="padding: 0">
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">{{ $operationalManager?->roles[0]?->name }}</p>
                    </th>
                </tr>
            </table>
        </div>
    @endif

    <div class="kop-footer">
        <img class="kop-image-footer"
             src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"/>
    </div>
</div>
</body>
</html>
