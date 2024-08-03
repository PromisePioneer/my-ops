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
            margin: 20px 20px 0px 20px;
        }

        .wrapper {
            position: relative;
            min-height: 100%;
            margin-bottom: -100px; /* Adjust based on footer height */
        }

        .heading-toolbar {
            margin-top: 40px;
            text-align: center;
            font-size: 15px;
        }

        .heading-text {
            margin-top: 40px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }

        .secondary-text {
            margin-top: 20px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }

        .third-text {
            margin-top: 20px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: justify;
            padding: 0 25px 0 25px;
        }


        .foreword {
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }


        .product-table, .product-table-tr, .product-table-th, .product-table-td {
            border: 1px solid;
            padding: 10px;
        }

        .bg-primary {
            background-color: rgb(0, 158, 247);
        }


        .kop-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }


    </style>


</head>

<body>

@php
    $date = \Carbon\Carbon::parse($bast->date)->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
@endphp

<div class="wrapper">
    <div class="kop-header">
        <img class="kop-image-header"
             src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-headers.png'))) }}"/>
    </div>


    <div class="heading-toolbar">
        <h3>BERITA ACARA SERAH TERIMA (“BAST”)</h3>
        <h3>{{ $bast->bast_number }}</h3>

    </div>

    <div class="heading-text">
        <p> Pada hari ini {{ $date->format('l') }} Tanggal {{ $date->format('j') }}
            Bulan {{ $date->format('F') }} Tahun {{ $date->format('Y') }}. Kami yang bertanda
            tangan di bawah ini menyatakan :</p>
    </div>


    <div style="text-align: center;">
        <div class="first-party-information" style="display: inline-block; text-align: left;">
            <table
                style="width: auto; margin-left: auto; margin-right: auto; text-align: left; border-collapse: collapse;">
                <tr>
                    <th style="text-align: left; padding: 2px;">
                        <p style="font-size: 12px; margin: 0;">Nama</p>
                    </th>
                    <th style="text-align: center; padding: 2px;">:</th>
                    <th style="text-align: left; padding: 2px;">
                        <p style="font-size: 12px; margin: 0;">{{ $bast->first_party_identity_name }}</p>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: right; padding: 2px;">
                        <p style="font-size: 12px; margin: 0;">Jabatan</p>
                    </th>
                    <th style="text-align: left; padding: 2px;">:</th>
                    <th style="text-align: left; padding: 2px;">
                        <p style="font-size: 12px; margin: 0;">{{ $bast->first_party_position }}</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div class="secondary-text">
        <p>Dalam hal ini bertindak untuk dan atas nama PT. Mayatama Solusindo yang Selanjutnya
            disebut &nbsp; <b>PIHAK PERTAMA</b></p>
    </div>

    <div style="text-align: center;">
        <div class="first-party-information" style="display: inline-block; text-align: left;">
            <table
                style="width: auto; margin-left: auto; margin-right: auto; text-align: left; border-collapse: collapse;">
                <tr>
                    <th style="text-align: left; padding: 2px;">
                        <p style="font-size: 12px; margin: 0;">Nama</p>
                    </th>
                    <th style="text-align: center; padding: 2px;">:</th>
                    <th style="text-align: left; padding: 2px;">
                        <p style="font-size: 12px; margin: 0;">{{ $bast->contact->full_name }}</p>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 2px;">
                        <p style="font-size: 12px; margin: 0;">Perusahaan</p>
                    </th>
                    <th style="text-align: left; padding: 2px;">:</th>
                    <th style="text-align: left; padding: 2px;">
                        <p style="font-size: 12px; margin: 0;">{{ $bast->contact->company_name }}</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div class="secondary-text">
        <p>Dalam hal ini bertindak untuk dan atas nama {{ $bast->contact->company_name }} yang Selanjutnya
            disebut &nbsp; <b>PIHAK KEDUA</b></p>
    </div>


    <div class="third-text">
        <p>{{ $bast->objective }}</p>
    </div>


    <div class="container">
        <table class="product-table">
            <thead>
            <tr class="bg-primary product-table-tr">
                <th class="product-table-th">No</th>
                <th class="product-table-th">Barang</th>
                <th class="product-table-th">Qty</th>
                <th class="product-table-th">SN / Kode</th>
                <th class="product-table-th">Keterangan</th>
            </tr>
            </thead>
            <tbody>
            @forelse($bastProducts as $product)
                <tr class="product-table-tr">
                    <td class="product-table-td">{{ $loop->iteration }}</td>
                    <td class="product-table-td">{{ $product->product_name }}</td>
                    <td class="product-table-td">{{ $product->qty }} Mbps</td>
                    <td class="product-table-td">{{ $product->serial_number }}</td>
                    <td class="product-table-td"> {{ $product->description }}</td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" class="product-table-td" style="text-align: center">Data Barang Kosong</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="third-text">
        <p>Demikianlah berita acara serah terima barang ini di perbuat oleh kedua belah pihak, sejak penandatanganan
            berita acara ini, maka barang tersebut, menjadi tanggung jawab <b>PIHAK KEDUA</b>.</p>
    </div>


    <div style="text-align: center; margin-top: 90px">
        <div class="signature-table" style="display: inline-block; text-align: left; margin-right: 100px;">
            <table style="width: auto; margin-left: auto; margin-right: auto; text-align: left; border-collapse: collapse;">
                <tr>
                    <th style="text-align: right; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">Yang Menyerahkan:</p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;"></p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;"></p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;"></p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px 8px 0 8px;">
                        <p style="font-size: 12px; margin: 0; text-decoration: underline">{{ $bast->first_party_identity_name }}</p>
                    </th>
                </tr>
                <tr style="padding: 0">
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0; ">{{ $bast->first_party_position }}</p>
                    </th>
                </tr>
            </table>
        </div>


        <div class="signature-table" style="display: inline-block; text-align: left; margin-left: 100px;">
            <table style="width: auto; margin-left: auto; margin-right: auto; text-align: left; border-collapse: collapse;">
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">Yang Menerima:</p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;"></p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;"></p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;"></p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px 8px 0 8px;">
                        <p style="font-size: 12px; margin: 0; text-decoration: underline">{{ $bast->contact->full_name }}</p>
                    </th>
                </tr>
                <tr style="padding: 0">
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">{{ $bast->contact->company_name }}</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>


    <div class="kop-footer">
        <img class="kop-image-footer"
             src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"/>
    </div>
</div>
</body>
</html>
