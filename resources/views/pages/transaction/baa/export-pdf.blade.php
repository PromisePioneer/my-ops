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
        margin: 4cm 0.5cm 2cm;
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
        <h2 class="text-uppercase text-decoration-underline fs-5">BERITA ACARA AKTIVASI</h2>
        <p class="fs-5">Nomor : {{ $baa->baa_number }}</p>
    </div>


    <p class="text-justify ms-5 mb-2 fs-8">
        Pada hari ini {{ formatDate($baa->date) }} yang bertanda tangan dibawah ini:
    </p>

    <div style="padding-left: 2rem; padding-right: 15rem" class="mb-2 fs-9">
        <table class="table">
            <tr>
                <td class="text-start w-3px">Nama</td>
                <td class="text-center">:</td>
                <td>{{ $baa->fab->fabPic->name }}</td>
            </tr>
            <tr>
                <td class="text-start">Jabatan</td>
                <td class="text-center">:</td>
                <td>{{ $baa->fab->fabPic->roles[0]?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-start">Perusahaan</td>
                <td class="text-center">:</td>
                <td>PT MAYATAMA SOLUSINDO</td>
            </tr>
        </table>
    </div>

    <p class="text-justify ms-5 mb-2 fs-9">
        Selanjutnya disebut "<b>MYFIBER</b>"
    </p>


    <div style="padding-left: 2rem; padding-right: 15rem" class="mb-2 fs-9">
        <table class="table">
            <tr>
                <td class="w-3px text-start">Nama</td>
                <td class="text-center">:</td>
                <td>{{ $baa->fab->po->contact->pic_name }}</td>
            </tr>
            <tr>
                <td class="text-start">Perusahaan</td>
                <td class="text-center">:</td>
                <td>PT MAYATAMA SOLUSINDO</td>
            </tr>
        </table>
    </div>

    <p class="text-justify ms-5 mb-2 fs-9">
        Selanjutnya disebut "<b>Pelanggan</b>"
    </p>


    <p class="text-justify ms-5 mb-2 fs-9">
        Pelanggan dan MYFIBER secara bersama-sama selanjutnya disebut juga “<b>Para Pihak</b>” dengan ini
        menyatakan bahwa sebagai berikut :
    </p>


    <div style="padding-left: 5rem; padding-right: 5rem">
        <table class="table custom-bordered table-bordered fs-9 ">
            <thead>
            <tr>
                <th class="text-center w-50 py-4 ">Deskripsi</th>
                <th class="text-center py-4">Data</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="py-4 fw-bolder px-2">Nomor PO</td>
                <td class="py-4 px-2">{{ $baa->po_number }}</td>
            </tr>
            <tr>
                <td class="py-4 fw-bolder px-2">Nama Pelanggan</td>
                <td class="py-4 px-2">{{ $baa->fab->po->contact->company_name }}</td>
            </tr>
            <tr>
                <td class="py-4 fw-bolder px-2">Alamat</td>
                <td class="py-4 px-2">{{ $baa->fab->po->contact->complete_address ?? '-' }}</td>
            </tr>
            <tr>
                <td class="py-4 fw-bolder px-2">Lokasi Pekerjaan</td>
                <td class="py-4 px-2">{{ $baa->work_location }}</td>
            </tr>
            </tbody>
        </table>
    </div>


    <p class="text-justify lh-lg ms-10 mb-2 fs-9">
        Pada tanggal tersebut di bawah Layanan Dedicated telah selesai dipasang dan di uji dengan hasil
        baik, dan oleh karenanya terhitung sejak tanggal tersebut :
    </p>

    <ol class="text-justify lh-lg ms-10 mb-2 fs-9">
        <li>Layanan tersebut sudah dapat digunakan/dioperasikan.</li>
        <li>Seluruh syarat dan ketentuan tersebut diatas berlaku dan mengikat Para Pihak</li>
    </ol>

    <p class="text-justify lh-lg ms-10 mb-7 fs-9" style="padding-right:40px">
        Demikian Berita Acara ini dibuat dan ditandatangani oleh Para Pihak dalam rangkap 2 (dua) asli yang
        sama bunyinya, mempunyai kekuatan hukum yang sama dan mengikat Para Pihak pada tanggal
        ditandatangani BAA.
    </p>


    <div class="d-flex align-items-center justify-content-around fs-9">
        <div class="text-center">
            <p class="m-1">MYFIBER</p>
            <p class="mb-20"><b>PT MAYATAMA SOLUSINDO</b></p>
            <p class="text-decoration-underline"><b>{{ $baa->fab->fabPic?->name }}</b></p>
            <p>{{ $baa->fab->picName->roles[0]?->name ?? '' }}</p>
        </div>

        <div class="text-center">
            <p class="m-1">PELANGGAN</p>
            <p class="mb-20"><b>{{ $baa->fab->po->contact->company_name }}</b></p>
            <p class="text-decoration-underline"><b>{{ $baa->fab->po->contact->pic_name }}</b></p>
        </div>
    </div>

</main>

</body>
</html>
