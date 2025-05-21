@php use function App\Helper\formatDate; @endphp
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    *, *::before, *::after {
        box-sizing: border-box;
    }


    * {
        margin: 0;
    }


    @page {
        margin: 0; /* Remove default margin for the page */
    }

    /** Define now the real margins of every page in the PDF **/
    body {
        margin: 3cm 2cm 2cm;
        line-height: 1.5;
        -webkit-font-smoothing: antialiased;
        font-family: Poppins, Helvetica, sans-serif;
        font-size: 62.5%;
    }


    header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 3cm;
    }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 5cm;

    }

    .wrapper {
        position: relative;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .heading-separator {
        text-align: center;
        margin-bottom: 0;
    }

    .table-heading-container {
        float: left;
        display: inline-block;
    }

    .table-heading .table-data-heading {
        border: none !important;
        margin-bottom: 100px;
        width: 50%;
        text-align: left;
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

    .clearfix {
        clear: both;
        margin-bottom: 40px;
    }

    .page-break {
        page-break-after: always;
    }

    .table-heading .table-data-heading {
        border: none !important;
        margin-bottom: 100px;
        width: 50%;
        text-align: left;
    }


    .fs-9 {
        font-size: .75rem !important
    }

    .vertical {
        vertical-align: baseline;
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

    .min-w-10px {
        min-width: -10px !important
    }

    .w-100px {
        width: 100px !important
    }

    .w-1px {
        width: 1px !important
    }

    .w-10px {
        width: 10px !important
    }

    .mb-4 {
        margin-bottom: 1rem !important
    }


    .border {
        border: 1px solid black !important;
    }

    .border-black {
        --bs-border-opacity: 1;
        border-color: rgba(0, 0, 0, 1) !important
    }


    .custom-bordered, .custom-bordered th, .custom-bordered td {
        border: 1px solid black;
        border-spacing: 0;
        border-collapse: collapse;
    }


    .text-center {
        text-align: center !important
    }

    .mb-2 {
        margin-bottom: .5rem !important
    }


    .d-flex {
        display: flex !important
    }

    .align-items-center {
        align-items: center !important
    }

    .justify-content-around {
        justify-content: space-around !important
    }

    .flex-column {
        flex-direction: column !important
    }

    .img-fluid {
        max-width: 100%;
        height: auto
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

<div class="wrapper">
    <div style="text-align: center; margin-bottom: 30px">
        <p style="font-size: 20px; font-weight: bold"><u>BERITA ACARA SERAH TERIMA</u></p>
        <p style="font-size: 17px; ">
            <b>No : {{ $stockMutation->stock_mutation_number }}</b>
        </p>
    </div>

    <p class="fs-9 mb-4">
        Pada tanggal {{ formatDate($stockMutation->date) }} yang bertanda tangan di bawah ini adalah :
    </p>

    <div class="mb-4">
        <ol start="1">
            <li class="fs-9">
                <table>
                    <tr>
                        <td class="vertical w-100px"><b>Nama</b></td>
                        <td class="vertical  w-10px"><b>:</b></td>
                        <td class="vertical"><b>{{ $stockMutation->sender->name }}</b></td>
                    </tr>
                    <tr>
                        <td class="vertical"><b>Jabatan</b></td>
                        <td class="vertical"><b>:</b></td>
                        <td class="vertical">
                            <b>
                                {{ $stockMutation->sender->roles[0]->name }}
                                Cabang
                                {{ $stockMutation->oldBranch->name }}
                            </b>
                        </td>
                    </tr>
                </table>
            </li>
        </ol>
    </div>
    <p class="fs-9 mb-4">Selanjutnya disebut sebagai "<b>PIHAK PERTAMA</b>".</p>

    <div class="mb-4">
        <ol start="2">
            <li class="fs-9">
                <table>
                    <tr>
                        <td class="vertical w-100px"><b>Nama</b></td>
                        <td class="vertical  w-10px"><b>:</b></td>
                        <td class="vertical"><b>{{ $stockMutation->receiver->name }}</b></td>
                    </tr>
                    <tr>
                        <td class="vertical"><b>Jabatan</b></td>
                        <td class="vertical"><b>:</b></td>
                        <td class="vertical">
                            <b>
                                {{ $stockMutation->receiver->roles[0]->name }}
                                Cabang
                                {{ $stockMutation->newBranch->parent->name }}
                            </b>
                        </td>
                    </tr>
                </table>
            </li>
        </ol>
    </div>
    <p class="fs-9 mb-4">Selanjutnya disebut sebagai "<b>PIHAK KEDUA</b>".</p>
    <p class="fs-9 mb-4">Dengan ini kami menyatakan :</p>

    <p class="fs-9 mb-4">
        <b>PIHAK PERTAMA</b> telah menyerahkan kepada <b>PIHAK KEDUA</b> barang inventaris berupa :
    </p>
    <table class="table mb-4">
        <thead>
        <tr class="fw-bold custom-bordered">
            <th class="text-center py-2"><b>Nama</b></th>
            <th class="text-center"><b>KODE / SN</b></th>
            <th class="text-center"><b>Jumlah</b></th>
        </tr>
        </thead>
        <tbody class="border-bottom border-black custom-bordered">
        @foreach($stockMutation->stockMutationItems as $stock)
            <tr>
                <td class="text-center">{{ $stock->stock->item->name }}</td>
                <td class="text-center">{{ $stock->code ?? '-' }}</td>
                <td class="text-center">{{ $stock->qty }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="fs-9 mb-4">
        <p class="mb-2"> Dengan Ketentuan Sebagai Berikut :</p>
        <ol style="line-height: 1.5">
            <li><b>PIHAK KESATU</b> wajib mengeluarkan Aset tersebut berikut nilainya dari Daftar Aktiva.</li>
            <li>Berdasarkan poin pertama di atas maka <b>PIHAK KESATU</b> tidak bertanggung jawab dan tidak dibebankan
                lagi dengan
                berbagai kewajiban atas Aset yang dimaksud setelah ditanda tangani Berita Acara ini.
            </li>
            <li>
                <b>PIHAK KEDUA</b> wajib mencatat Nilai Perolehan dan Nilau Penyusutan dari Aset tersebut ke dalam
                Daftar Aktiva.
            </li>
            <li>
                Berdasarkan poin ketiga di atas, maka <b>PIHAK KEDUA</b> bertanggung jawab sepenuhnya atas Aset
                dimaksud
                setelah di tandatangani Berita Acara ini.
            </li>
        </ol>
    </div>


    <div class="d-flex align-items-center justify-content-around">
        <div class="d-flex flex-column text-center">
            <p class="mb-2"><b>PIHAK PERTAMA</b></p>
            <img
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $stockMutation->sender_signature))) }}"
                alt="" class="mb-2" width="100px" height="70px">
            <p class="fs-9">
                <b>{{ $stockMutation->sender->name }}</b>
            </p>
        </div>
        <div class="d-flex flex-column text-center">
            <p class="mb-2"><b>PIHAK KEDUA</b></p>
            @if(isset($stockMutation->receiver_signature))
                <img
                    src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $stockMutation->receiver_signature))) }}"
                    alt="" class="mb-2" width="100px" height="70px">
            @else
                <img
                    src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/etc/dummy.png'))) }}"
                    alt="" class="mb-2" width="100px" height="70px">
            @endif
            <p class="fs-9">
                <b>{{ $stockMutation->receiver->name }}</b>
            </p>
        </div>
    </div>


</div>


</body>
</html>
