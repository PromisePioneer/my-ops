@php use Carbon\Carbon;use SimpleSoftwareIO\QrCode\Facades\QrCode; @endphp
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Keputusan {{ $sk->user->name  }}</title>

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
            /*border: 1px solid;*/
            border: none !important;
            margin-bottom: 100px;
            width: 50%;
            text-align: left;
        }
    </style>
</head>

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

<main>
    <div class="wrapper">
        <div style="text-align: center; margin-bottom: 30px">
            <p style="font-size: 20px; font-weight: bold"><u>Surat Keputusan ({{ $sk->sk_type }})</u></p>
            <p style="font-size: 17px; ">Nomor : {{ $sk->sk_number }}</p>
        </div>


        <p style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; line-height: 1.5">
            Dengan ini, kami menyatakan bahwa berdasarkan pertimbangan matang dan hasil evaluasi kinerja, serta
            untuk memenuhi kebutuhan perusahaan, kami secara resmi
            menetapkan jabatan bagi:
        </p>

        <table class="table-heading" style="width: 100%; border-collapse: collapse; font-size: 11px;">
            <tbody>
            <tr>
                <td style="width: 5%; padding: 5px;"><b>Nama</b></td>
                <td style="width: 1%; padding: 5px;">:</td>
                <td style="width: 20%; padding: 5px;"><b>{{ $sk->user->name }}</b></td>
            </tr>
            <tr>
                <td style="width: 5%; padding: 5px;"><b>Jabatan Lama</b></td>
                <td style="width: 1%; padding: 5px;">:</td>
                <td style="width: 20%; padding: 5px;"><b>{{ $sk->oldRole?->name ?? '-' }}
                        Cab {{ $sk->oldBranch?->name }}</b>
                </td>
            </tr>
            <tr>
                <td style="width: 5%; padding: 5px;"><b>Jabatan Baru</b></td>
                <td style="width: 1%; padding: 5px;">:</td>
                <td style="width: 20%; padding: 5px;"><b>{{ $sk->newRole?->name }} Cab {{  $sk->newBranch?->name }}</b>
                </td>
            </tr>
            </tbody>
        </table>

        <p style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; line-height: 1.5">
            Penetapan jabatan ini berlaku mulai tanggal
            <b>{{ Carbon::parse($sk->date)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y') }}</b>.
            Surat keputusan ini berlaku efektif sejak tanggal ditetapkan dan menggantikan semua keputusan sebelumnya
            terkait dengan penugasan jabatan. Kami percaya bahwa yang bersangkutan akan melaksanakan tugas barunya
            dengan penuh dedikasi dan integritas. Atas perhatian dan kerjasama yang baik, kami ucapkan terima kasih.
        </p>

        <p style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px ">

        </p>


        <div style="margin-right: 30px;float: right; margin-top: 30px">
            <table>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">Hormat Kami,</p>
                    </th>
                    <th style="text-align: center; padding: 8px;"></th>
                </tr>
                <tr>
                    <th style="text-align: center; padding: 8px;">
                        <p style="font-size: 12px; margin: 0;">
                            <img
                                src="data:image/svg+xml;base64, {!! base64_encode(QrCode::size(100)->generate(url('/manage-users/sk/export-pdf/' . $sk->id))) !!} "
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
    </div>
</main>


</body>
</html>
