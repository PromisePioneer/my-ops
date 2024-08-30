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


        @page {
            margin: 0 0;
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

<header>
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-header.png'))) }}"
         width="100%" height="100%"/>
</header>

<div class="wrapper">
    <div class="heading-toolbar">
        <p style="font-size: 26px"><u>Surat Peringatan {{ $super }} ({{ $sp->sp_type }})</u></p>
        <p style="font-size: 17px; ">Nomor : {{ $sp->sp_number }}</p>
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

    @if($sp->sp_type === 'SP-1' || $sp->sp_type === 'SP-2')
        <div style="margin: 0 30px 0 40px; font-size: 13px">
            Sehubungan sikap indisipliner dan pelanggaran terhadap tata tertib perusahaan yang saudara
            lakukan, yaitu sebagai berikut :
            <div style="padding: 5px 0 3px 0"></div>
            <ol>
                @foreach($spReasonList as $spReason)
                    <li>{{ $spReason->list_of_reason }}</li>
                @endforeach
            </ol>

            <div style="padding: 5px 0 3px 0"></div>
            <p>
                Maka dengan ini saudara dikenakan. Adapun ketentuan
                <span style="color: red">{{ $sp->sp_type }}</span> yang telah ditetapkan oleh manajemen untuk saudara
                adalah sebagai
                berikut:
            </p>
            <ol style="line-height: 1.7em">
                <li>Surat Peringatan Pertama berlaku untuk 6 (enam) bulan kedepan sejak diterbitkan.</li>
                <li>Jika didapati saudara kembali melakukan tindakan indispliner dan/atau pelanggaran tata tertib,
                    sehingga saudara dianggap meremehkan peraturan dan peringatan yang berlaku, maka
                    perusahaan akan memberikan Surat Peringatan Kedua/Ketiga hingga pemutusan hubungan
                    kerja sesuai kualifikasi pada peraturan perusahaan yang berlaku.
                </li>
                <li>
                    Sanksi yang diberikan kepada saudara yaitu:
                    <ol type="a">
                        <li>Tidak mendapatkan bonus selama 6 (enam) bulan.</li>
                        <li>Penundaan kenaikan gaji selama 6 (enam) bulan.</li>
                        <li> Berjanji untuk tidak mengulangi kesalahan yang telah dilakukan sesuai yang disebutkan di
                            Surat Peringatan ini.
                        </li>
                        <li>Berpotensi dilakukannya Demosi dan/atau Mutasi hingga Penurunan Gaji apabila kerap
                            mengulangi kesalahan.
                        </li>
                    </ol>
                </li>
            </ol>

            <div style="padding: 5px 0 3px 0"></div>

            <p>
                Demikian Surat Peringatan ini dibuat agar dapat diperhatikan dan ditaati oleh yang
                bersangkutan.
            </p>

            @endif
        </div>



        @if($sp->sp_type === 'SP-3')
            <div style="margin: 0 30px 0 40px; font-size: 13px">
                Sehubungan sikap indisipliner dan pelanggaran terhadap tata tertib perusahaan yang saudara
                lakukan, yaitu sebagai berikut :
                <div style="padding: 5px 0 3px 0"></div>
                <ol>
                    @foreach($spReasonList as $spReason)
                        <li>{{ $spReason->list_of_reason }}</li>
                    @endforeach
                </ol>
                <div style="padding: 5px 0 3px 0"></div>

                <p> Kami ingin mengingatkan Anda bahwa tindakan pelanggaran terhadap kebijakan perusahaan dapat
                    berdampak serius tidak hanya pada kinerja Anda sendiri tetapi juga pada citra perusahaan secara
                    keseluruhan.</p>
                <br>
                <p>
                    Sehubungan dengan hal ini, kami sangat menyesalkan bahwa upaya-upaya untuk memperbaiki
                    perilaku Anda belum memberikan hasil yang diharapkan. Oleh karena itu, dengan penuh penyesalan,
                    kami harus memberikan sanksi terberat yang telah disepakati oleh perusahaan untuk pelanggaran
                    yang telah terjadi.
                </p>
                <br>
                <p>
                    Dengan ini, kami menyampaikan bahwa sanksi yang diberlakukan atas pelanggaran-pelanggaran yang
                    telah terjadi adalah pengunduran diri dari jabatan Anda di perusahaan ini. Anda diharapkan untuk
                    mengajukan pengunduran diri secara tertulis dalam waktu <b>7 (tujuh) hari</b> kerja sejak tanggal
                    penerimaan surat ini. Demikian Surat Peringatan ini dibuat dan agar dapat dilaksanakan.
                </p>
            </div>
        @endif

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
            <div class="clearfix"></div>
        @endif

        <footer>
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"
                 width="100%" height="100%"/>
        </footer>
</div>
</body>
</html>
