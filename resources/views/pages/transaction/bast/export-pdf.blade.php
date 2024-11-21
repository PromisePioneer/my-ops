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


        @page {
            margin: 0 0;
        }

        body {
            margin: 4.5cm 0.7cm 2cm;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            font-family: Poppins, Helvetica, sans-serif;
            font-size: 62.5%;
            box-sizing: border-box;
        }


        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 4.5cm;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5cm;
        }

        .wrapper {
            margin-top: 10px !important;
            position: relative;
        }

        .text-center {
            text-align: center;
        }

        .mb-15 {
            margin-bottom: 1.75rem !important
        }

        .ms-5 {
            margin-left: 1.25rem !important
        }

        .mb-4 {
            margin-bottom: 1rem !important
        }

        .min-w-100px {
            min-width: 100px !important
        }

        .min-w-1px {
            min-width: 1px !important
        }

        .px-10 {
            padding-right: 2.5rem !important;
            padding-left: 2.5rem !important
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            vertical-align: top;
        }

        .custom-bordered, .custom-bordered th .custom-bordered tr, .custom-bordered td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .custom-bordered table .custom-bordered th .custom-bordered tr .custom-bordered .td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .table-bordered > :not(caption) > * {
            border-width: 1px 0;
            border-style: solid;
            border-color: black;
        }

        .table-bordered > :not(caption) > * > * {
            border-width: 0 1px;
            border-style: solid;
            border-color: black;
        }

        .py-1 {
            padding-top: .3rem !important;
            padding-bottom: .3rem !important
        }

        .px-1 {
            padding-right: .25rem !important;
            padding-left: .25rem !important
        }

        .d-flex {
            display: flex !important
        }

        .justify-content-between {
            justify-content: space-between !important
        }

        .border-bottom {
            border-bottom: 1px solid black !important
        }

        .text-uppercase {
            text-transform: uppercase !important
        }

        .fw-bolder {
            font-weight: 700 !important
        }

        .justify-content-around {
            justify-content: space-around !important
        }

        .mb-1 {
            margin-bottom: 0.25rem !important;
        }

    </style>


</head>

<body>

@php
    use function App\Helper\formatDate;
@endphp


<header>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-header.png'))) }}"
        width="100%" height="100%"/>
</header>

<div class="wrapper">
    <div class="text-center mb-15">
        <h2><u>BERITA ACARA SERAH TERIMA (“BAST”)</u></h2>
        <h2>{{ $bast->bast_number }}</h2>
    </div>

    <div>
        <p>Pada hari ini {{ formatDate($bast->date) }} kami yang bertandatangan dibawah ini:</p>
    </div>

    <div class="ms-5 mb-4">
        <table>
            <tr>
                <td class="min-w-100px">Nama</td>
                <td class="min-w-1px">:</td>
                <td class="px-10">{{ $bast->baa->fab->fabPic->name }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td class="px-10">{{ $bast->baa->fab->fabPic->roles[0]?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Perusahaan</td>
                <td>:</td>
                <td class="px-10">PT Mayatama Solusindo</td>
            </tr>
        </table>
    </div>

    <p>Selanjutnya disebut “<b>MYFIBER</b>”.</p>

    <div class="ms-5 mb-4">
        <table>
            <tr>
                <td class="min-w-100px">Nama</td>
                <td class="min-w-1px">:</td>
                <td class="px-10">{{ $bast->baa->fab->po->contact->pic_name }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td class="px-10">{{ $bast->baa->fab->po->contact->pic_position }}</td>
            </tr>
            <tr>
                <td>Perusahaan</td>
                <td>:</td>
                <td class="px-10">{{ $bast->baa->fab->po->contact->company_name }}</td>
            </tr>
        </table>
    </div>

    <div class="mb-1">
        <p>Selanjutnya disebut “<b>PELANGGAN</b>”.</p>
    </div>

    <p class="mb-4">
        <b>PELANGGAN</b> dan <b>MYFIBER</b> secara bersama-sama selanjutnya disebut juga “Para Pihak”,
        dengan ini
        menerangkan bahwa pekerjaan sebagai berikut:
    </p>

    <div>
        <table class="ms-5 table custom-bordered table-bordered" style="width: 700px">
            <thead>
            <tr class="py-1">
                <th class="w-10px py-1">No</th>
                <th class="text-center py-1">Keterangan</th>
                <th class="text-center py-1">Data</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center py-1">1</td>
                <td class="py-1 px-1"><b>Tanggal PO</b></td>
                <td class="py-1 px-1">{{ formatDate($bast->baa->fab->po->date) }}</td>
            </tr>
            <tr>
                <td class="text-center py-1">2</td>
                <td class="py-1 px-1"><b>Nama Pekerjaan</b></td>
                <td class="py-1 px-1">-</td>
            </tr>
            <tr>
                <td class="text-center py-1">3</td>
                <td class="py-1 px-1"><b>Nomor PO</b></td>
                <td class="py-1 px-1">{{ $bast->baa->fab->po->po_number }}</td>
            </tr>
            <tr>
                <td class="text-center py-1">4</td>
                <td class="py-1 px-1"><b>Nilai PO</b></td>
                <td class="py-0 px-1">
                    <div class="border-bottom border-black">
                        @foreach($getPoItem as $poItem)
                            <div class="d-flex justify-content-between">
                                <div>{{ $poItem->item }}</div>
                                <div>Rp.{{ number_format($poItem->price) }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>PPN</div>
                        <div>Rp.{{ number_format($totalPPN) }}</div>
                    </div>
                    <div
                        class="d-flex align-items-center justify-content-between border-bottom border-black">
                        <div>Total</div>
                        <div>Rp.{{ number_format($getPoItem->sum('price')) }}</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-black fw-bolder text-uppercase">Total Keseluruhan</div>
                        <div class="text-black fw-bolder text-uppercase">
                            Rp.{{ number_format($total)  }}
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-center px-1">5</td>
                <td class="px-1"><b>Cara Pembayaran</b></td>
                <td class="px-1">
                    <p class="p-0 m-0">{{ $companyProfile->bank }}</p>
                    <p class="p-0 m-0">A/C No: {{ $companyProfile->bank_account_number }}</p>
                    <p class="p-0 m-0">Nama Akun: {{ $companyProfile->bank_account_name }}</p>
                    <p>
                        Atau rekening bank sebagaimana ditentukan di dalam tagihan (invoice)
                        MAYATAMA.
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <p class="mb-20" style="line-height: 1.5; margin-bottom: .3rem">
        Pada tanggal tersebut perkerjaan sudah selesai dikerjakan dan ditest dengan hasil baik. Demikian
        Berita Acara Serah Terima ini dibuat dan ditandatangani oleh <b>PARA PIHAK</b> dalam rangkap 2
        (dua)
        asli yang sama bunyinya, mempunyai kekuatan hukum yang sama dan mengikat <b>PARA PIHAK</b> pada
        tanggal
        ditanda tanganinya BAST ini.
    </p>


    <div class="d-flex align-items-center justify-content-around">
        <div class="text-center">
            <div><b>MY FIBER</b></div>
            <div style="margin-bottom: 5rem"><b>PT Mayatama Solusindo</b></div>
            <div>{{ $bast->baa->fab->fabPic->name }}</div>
            <div>{{ $bast->baa->fab->fabPic->roles[0]?->name ?? '-' }}</div>
        </div>
        <div class="text-center">
            <div><b>PELANGGAN</b></div>
            <div style="margin-bottom: 5rem"><b>{{ $bast->baa->fab->po->contact->company_name }}</b>
            </div>
            <div>{{ $bast->baa->fab->po->contact->pic_name }}</div>
            <div>{{ $bast->baa->fab->po->contact->pic_position }}</div>
        </div>
    </div>
</div>


<footer>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"
        width="100%" height="100%"/>
</footer>
</body>
</html>
