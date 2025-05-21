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
        <p style="font-size: 17px; ">Nomor : </p>
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
    <ol class="fs-9">
        <li>
            <p class="fs-9">
                Pihak pertama telah menyerahkan kepada pihak kedua barang inventaris berupa :
            </p>
            <table>
                <thead>
                <tr>
                    <th class="vertical w-100px"><b>Nama</b></th>
                    <th class="vertical  w-10px"><b>KODE / SN</b></th>
                    <th class="vertical  w-10px"><b>Jumlah</b></th>
                </tr>
                </thead>
                <tr>
                    <td class="vertical"><b>Jumlah</b></td>
                    <td class="vertical"><b>:</b></td>
            </table>
        </li>
    </ol>
</div>


</body>
</html>
