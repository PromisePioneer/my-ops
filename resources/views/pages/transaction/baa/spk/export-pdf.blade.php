@php use function App\Helper\formatDate; @endphp
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>

<style>

    *, *::before, *::after {
        box-sizing: border-box;
    }


    * {
        margin: 0;
    }

    html {
        -webkit-print-color-adjust: exact;
    }


    @page {
        margin: 0 0;
    }


    body {
        margin: 4cm 1cm 2cm;
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
        margin-top: 10px;
        position: relative;
    }

    .text-center {
        text-align: center !important
    }

    .mb-19 {
        margin-bottom: 4.75rem !important
    }

    .mb-20 {
        margin-bottom: 5rem !important
    }

    .mb-2 {
        margin-bottom: .5rem !important
    }

    .text-uppercase {
        text-transform: uppercase !important
    }

    .text-decoration-underline {
        text-decoration: underline !important
    }

    .fs-5 {
        font-size: 1.15rem !important
    }

    .text-justify {
        text-align: justify;
    }

    .ms-10 {
        margin-left: 2.5rem !important
    }

    .mb-4 {
        margin-bottom: 1rem !important
    }


    .mb-3 {
        margin-bottom: .75rem !important
    }


    .table {
        width: 100%;
        margin-bottom: 1rem;
        vertical-align: top;
    }

    .w-3px {
        width: 3px !important
    }

    .text-start {
        text-align: left !important
    }

    .border-top {
        border-top: 1px solid black;
    }

    .border-bottom {
        border-bottom: 1px solid black;
    }

    .custom-bordered table .custom-bordered th .custom-bordered tr .custom-bordered .td {
        border: 1px solid black;
        border-collapse: collapse;

    }

    .lh-lg {
        line-height: 1.75 !important
    }

    .d-flex {
        display: flex;
    }

    .align-items-center {
        align-items: center !important
    }

    .justify-content-around {
        justify-content: space-around !important
    }

    .custom-bordered, .custom-bordered th .custom-bordered tr, .custom-bordered td {
        border: 1px solid black;
        border-collapse: collapse;
    }


    .table {
        width: 100%;
        margin-bottom: 1rem;
        vertical-align: top;
    }

    .py-4 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important
    }

    .mb-5 {
        margin-bottom: 1.25rem !important
    }

    .mb-7 {
        margin-bottom: 1.75rem !important
    }

    .w-50 {
        width: 50% !important
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


    .fw-bolder {
        font-weight: 700 !important
    }

    .fs-6 {
        font-size: 1.075rem !important
    }

    .fs-8 {
        font-size: .85rem !important
    }

    .ms-5 {
        margin-left: 1.25rem !important
    }


    .px-2 {
        padding-right: .5rem !important;
        padding-left: .5rem !important
    }

    .fs-9 {
        font-size: .75rem !important
    }

</style>
<body>
<header>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-header.png'))) }}"
        width="100%" height="100%"/>
</header>

<footer>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"
        width="100%" height="100%"/>
</footer>


<main class="wrapper">
    <div class="text-center mb-4">
        <h2 class="text-uppercase text-decoration-underline fs-5">SURAT PERINTAH KERJA</h2>
        <p class="fs-5">Nomor : {{ $spk->spk_number }}</p>
    </div>


    <p class="text-justify mb-2 fs-9">
        Kegiatan : {{ $spk->name }}
    </p>

    <p class="text-justify mb-2 fs-9">
        Yang bertanda tangan di bawah ini:
    </p>


    <div style="padding-left: 1rem; padding-right: 15rem" class="mb-2 fs-9">
        <table class="table">
            <tr>
                <td class="text-start w-3px">Nama</td>
                <td class="text-center">:</td>
                <td>{{ $spk->spkFrom->name }}</td>
            </tr>
            <tr>
                <td class="text-start">Jabatan</td>
                <td class="text-center">:</td>
                <td>{{ $spk->spkFrom->roles[0]?->name ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <p class="text-justify mb-2 fs-9">
        Selanjutnya disebut sebagai "<b>PIHAK PERTAMA</b>"
    </p>


    <p class="text-justify mb-5 fs-9">
        Berdasarkan surat perintah kerja Nomor {{ $spk->spk_number }} tanggal {{ formatDate($spk->date) }}, bersama ini
        <br>
        memerintahkan:
    </p>


    <div style="padding-left: 1rem; padding-right: 15rem" class="mb-2 fs-9">
        <table class="table">
            <tr>
                <td class="w-3px text-start">Nama</td>
                <td class="text-center">:</td>
                <td>{{ $spk->spkTo->name }}</td>
            </tr>
            <tr>
                <td class="text-start">Jabatan</td>
                <td class="text-center">:</td>
                <td>{{ $spk->spkTo->roles[0]?->name ?? '-' }}</td>
            </tr>
        </table>
    </div>


    <p class="text-justify mb-4 fs-9">
        Menjalankan pekerjaan yang bertindak untuk dan atas nama <b>PT Mayatama Solusindo</b>
        untuk selanjutnya disebut "<b>PIHAK KEDUA</b>".
    </p>


    <div style="padding-left: 5rem; padding-right: 5rem" class="mb-4">
        <table class="table custom-bordered table-bordered fs-9 ">
            <thead>
            <tr>
                <th class="text-center w-50 py-4 ">Deskripsi</th>
                <th class="text-center py-4">Data</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="py-4 fw-bolder px-2">Macam Pekerjaan</td>
                <td class="py-4 px-2">{{ $spk->name }}</td>
            </tr>
            <tr>
                <td class="py-4 fw-bolder px-2">Tanggal</td>
                <td class="py-4 px-2">{{ formatDate($spk->start_date) }} s/d {{ formatDate($spk->end_date) }}</td>
            </tr>
            </tbody>
        </table>
    </div>


    <p class="text-justify lh-lg  mb-20 fs-9">
        Demikian Surat Perintah Kerja ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.
    </p>


    <div class="d-flex align-items-center justify-content-around fs-9">
        <div class="text-center">
            <p class="m-1 mb-20">Yang Memberi Perintah</p>
            <p><b>{{ $spk->spkFrom->name ?? '' }}</b></p>
            <p><b>{{ $spk->spkFrom->roles[0]?->name ?? '-' }}</b></p>
        </div>

        <div class="text-center">
            <p class="m-1 mb-20">Yang Menerima Perintah</p>
            <p><b>{{ $spk->spkTo->name ?? '' }}</b></p>
            <p><b>{{ $spk->spkTo->roles[0]?->name ?? '-' }}</b></p>
        </div>
    </div>

</main>

</body>
</html>
