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
            margin-top: 150px !important;
            position: relative;
        }

        .text-center {
            text-align: center !important;
        }

        .fs-3 {
            font-size: calc(1.26rem + .12vw) !important
        }

        .fs-4 {
            font-size: 1.25rem !important
        }

        .mb-4 {
            margin-bottom: 1rem !important
        }

        .fs-9 {
            font-size: .75rem !important
        }

        .ms-5 {
            margin-left: 1.25rem !important
        }

        .mb-2 {
            margin-bottom: .5rem !important
        }

        .ms-n3 {
            margin-left: -.75rem !important
        }
    </style>
</head>

<body>


@php
    $super = '';
    if ($sp->sp_type === 'ST'){
        $super = 'Surat Teguran';
    }
    if ($sp->sp_type === 'SP-1'){
        $super = 'Pertama';
    }
    if($sp->sp_type === 'SP-2'){
        $super = 'Kedua';
    }
    if($sp->sp_type === 'SP-3'){
        $super = 'Ketiga';
    }
@endphp

<header>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-header.png'))) }}"
        width="100%" height="100%"/>
</header>

<div class="wrapper">
    <div class="text-center mb-4">
        <p class="fs-3"><u>{{ $sp->sp_type === "ST" ? "Surat Teguran" : "Surat Peringatan" }}
                ({{ $sp->sp_type === "ST" ? 'ST' : $sp->sp_type }})</u></p>
        <p class="fs-4">Nomor : {{ $sp->sp_number }}</p>
    </div>
    <p class="fs-9">Surat peringatan ini ditujukan kepada : </p>
    <table class="ms-10 fs-9 mb-4">
        <tbody>
        <tr>
            <td>
                Nama
            </td>
            <td>
                :
            </td>
            <td>
                {{ $sp->user->name }}
            </td>
        </tr>
        <tr>
            <td>
                <p>NIK</p>
            </td>
            <td>
                :
            </td>
            <td>
                {{ $sp->user->nip }}
            </td>
        </tr>
        <tr>
            <td>
                <p>Jabatan</p>
            </td>
            <td>
                :
            </td>
            <td>
                {{ $sp?->user?->roles?->first()?->name  }}
            </td>
        </tr>
        </tbody>
    </table>


    @if($sp->sp_type === 'ST')
        <div>
            <p class="fs-9 mb-2">
                Sehubungan sikap indisipliner dan pelanggaran terhadap tata tertib perusahaan yang saudara lakukan,
                yaitu sebagai berikut :
            </p>
            <ol class="fs-9 mb-2 ms-n3">
                @foreach($spReasonList as $spReason)
                    <li>{{ $spReason->list_of_reason }}</li>
                @endforeach
            </ol>
            <p class="fs-9 mb-2">
                Oleh karena itu, kami ingin mengingatkan Anda akan pentingnya mematuhi peraturan dan prosedur dalam
                melakukan pekerjaan. Kami berharap agar Anda: Meningkatkan ketelitian dalam melaksanakan pekerjaan
                sebagaimana diatur dalam SOP.
            </p>

            <p class="fs-9 mb-2">
                Surat ini juga dianggap sebagai peringatan resmi yang dicatat dalam catatan karyawan. Jika tindakan
                indisipliner ini terus berlanjut, tindakan lebih lanjut dapat diambil sesuai dengan kebijakan
                perusahaan. Demikian Surat Teguran ini dibuat agar tidak terjadi lagi kesalahan yang sama, dan agar
                peraturan perusahaan dapat lebih diperhatikan dan ditaati oleh yang bersangkutan.
            </p>

            <p class="fs-9 ">
                Demikian Surat Peringatan ini dibuat agar dapat diperhatikan dan ditaati oleh yang
                bersangkutan.
            </p>
            @endif
            @if($sp->sp_type === 'SP-1' || $sp->sp_type === 'SP-2')
                <div>
                    <p class="fs-9 mb-2">
                        Sehubungan sikap indisipliner dan pelanggaran terhadap tata tertib perusahaan yang saudara
                        lakukan, yaitu sebagai berikut :
                    </p>
                    <ol class="fs-9 mb-2 ms-n3">
                        @foreach($spReasonList as $spReason)
                            <li>{{ $spReason->list_of_reason }}</li>
                        @endforeach
                    </ol>
                    <p class="fs-9 mb-2">
                        Maka dengan ini saudara dikenakan. Adapun ketentuan
                        <span style="color: red">{{ $sp->sp_type }}</span> yang telah ditetapkan oleh manajemen untuk
                        saudara
                        adalah sebagai
                        berikut:
                    </p>
                    <ol style="line-height: 1.7em" class="fs-9 ms-n3 mb-4">
                        <li>Surat Peringatan Pertama berlaku untuk 6 (enam) bulan kedepan sejak diterbitkan.</li>
                        <li>Jika didapati saudara kembali melakukan tindakan indispliner dan/atau pelanggaran tata
                            tertib,
                            sehingga saudara dianggap meremehkan peraturan dan peringatan yang berlaku, maka
                            perusahaan akan memberikan Surat Peringatan Kedua/Ketiga hingga pemutusan hubungan
                            kerja sesuai kualifikasi pada peraturan perusahaan yang berlaku.
                        </li>
                        <li>
                            Sanksi yang diberikan kepada saudara yaitu:
                            <ol type="a">
                                <li>Tidak mendapatkan bonus selama 6 (enam) bulan.</li>
                                <li>Penundaan kenaikan gaji selama 6 (enam) bulan.</li>
                                <li> Berjanji untuk tidak mengulangi kesalahan yang telah dilakukan sesuai yang
                                    disebutkan di
                                    Surat Peringatan ini.
                                </li>
                                <li>Berpotensi dilakukannya Demosi dan/atau Mutasi hingga Penurunan Gaji apabila kerap
                                    mengulangi kesalahan.
                                </li>
                            </ol>
                        </li>
                    </ol>


                    <p class="fs-9 ">
                        Demikian Surat Peringatan ini dibuat agar dapat diperhatikan dan ditaati oleh yang
                        bersangkutan.
                    </p>
                    @endif
                </div>

                @if($sp->sp_type === 'SP-3')
                    <div class="fs-9 mb-2">
                        Sehubungan sikap indisipliner dan pelanggaran terhadap tata tertib perusahaan yang saudara
                        lakukan, yaitu sebagai berikut :
                        <div style="padding: 5px 0 3px 0"></div>
                        <ol class="fs-9 mb-2 ms-n3">
                            @foreach($spReasonList as $spReason)
                                <li>{{ $spReason->list_of_reason }}</li>
                            @endforeach
                        </ol>

                        <p class="fs-9 mb-2"> Kami ingin mengingatkan Anda bahwa tindakan pelanggaran terhadap kebijakan
                            perusahaan dapat
                            berdampak serius tidak hanya pada kinerja Anda sendiri tetapi juga pada citra perusahaan
                            secara
                            keseluruhan.</p>
                        <br>
                        <p class="fs-9 mb-2">
                            Sehubungan dengan hal ini, kami sangat menyesalkan bahwa upaya-upaya untuk memperbaiki
                            perilaku Anda belum memberikan hasil yang diharapkan. Oleh karena itu, dengan penuh
                            penyesalan,
                            kami harus memberikan sanksi terberat yang telah disepakati oleh perusahaan untuk
                            pelanggaran
                            yang telah terjadi.
                        </p>
                        <br>
                        <p class="fs-9 mb-2">
                            Dengan ini, kami menyampaikan bahwa sanksi yang diberlakukan atas pelanggaran-pelanggaran
                            yang
                            telah terjadi adalah pengunduran diri dari jabatan Anda di perusahaan ini. Anda diharapkan
                            untuk
                            mengajukan pengunduran diri secara tertulis dalam waktu <b>7 (tujuh) hari</b> kerja sejak
                            tanggal
                            penerimaan surat ini. Demikian Surat Peringatan ini dibuat dan agar dapat dilaksanakan.
                        </p>
                    </div>
                @endif

                <br>
                @if($punishedBy->hasRole('Operational Manager'))
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
                                            src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate(url('/manage-users/sp/export-pdf/' . $sp->id))) !!} "
                                            width="100px" height="70px">
                                    </p>
                                </th>
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

                @if($punishedBy->hasAnyRole(['Branch Manager']))
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
                                            src="data:image/png;base64, {!! base64_encode(QrCode::size(10)->generate(url('/manage-users/sp/export-pdf/' . $sp->id))) !!} "
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
                    <img
                        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"
                        width="100%" height="100%"/>
                </footer>
        </div>
</body>
</html>
