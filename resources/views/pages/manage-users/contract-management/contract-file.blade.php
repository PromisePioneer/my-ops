@php use SimpleSoftwareIO\QrCode\Facades\QrCode; @endphp
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Kontrak {{ $contract->user->name  }}</title>

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
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-header.png'))) }}"
         width="100%" height="100%"/>
</header>

<footer>
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"
         width="100%" height="100%"/>
</footer>

<main>
    <div class="wrapper">
        <div style="text-align: center; margin-bottom: 30px">
            <p style="font-size: 20px; font-weight: bold"><u>PERJANJIAN KERJA WAKTU TERTENTU</u></p>
            <p style="font-size: 17px; ">Nomor : {{ $contract->contract_number }}</p>
        </div>
        <div style="margin: 10px 40px 0 30px;">
            <p style="font-size: 11px; ">Yang bertanda tangan di bawah ini: </p>
        </div>
        <div style="margin: 0 30px;">
            @if($contract->user?->branch?->code === null || $contract->user?->branch?->code === 100)
                <table class="table-heading" style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <tbody>
                    <tr>
                        <td style="width: 5%; padding: 5px;">Nama</td>
                        <td style="width: 1%; padding: 5px;">:</td>
                        <td style="width: 20%; padding: 5px;">{{ $directorRole?->name }}</td>
                    </tr>
                    <tr>
                        <td style="width: 5%; padding: 5px;">Jabatan</td>
                        <td style="width: 1%; padding: 5px;">:</td>
                        <td style="width: 20%; padding: 5px;">{{ $directorRole?->roles[0]?->name }}</td>
                    </tr>
                    <tr>
                        <td style="width: 5%; padding: 5px;">Alamat</td>
                        <td style="width: 1%; padding: 5px;">:</td>
                        <td style="width: 20%; padding: 5px;">
                            {{ $directorRole?->identityInformation?->home_address }}
                        </td>
                    </tr>
                    </tbody>
                </table>

                {{--            @else--}}
                {{--                <table class="table-heading" style="width: 100%; border-collapse: collapse; font-size: 11px;">--}}
                {{--                    <tbody>--}}
                {{--                    <tr>--}}
                {{--                        <td style="width: 5%; padding: 5px;">Nama</td>--}}
                {{--                        <td style="width: 1%; padding: 5px;">:</td>--}}
                {{--                        <td style="width: 20%; padding: 5px;">{{ $branchManagerRole?->name }}</td>--}}
                {{--                    </tr>--}}
                {{--                    <tr>--}}
                {{--                        <td style="width: 5%; padding: 5px;">Jabatan</td>--}}
                {{--                        <td style="width: 1%; padding: 5px;">:</td>--}}
                {{--                        <td style="width: 20%; padding: 5px;">{{ $branchManagerRole?->roles[0]?->name }}</td>--}}
                {{--                    </tr>--}}
                {{--                    <tr>--}}
                {{--                        <td style="width: 5%; padding: 5px;">Alamat</td>--}}
                {{--                        <td style="width: 1%; padding: 5px;">:</td>--}}
                {{--                        <td style="width: 20%; padding: 5px;">--}}
                {{--                            {{ $branchManagerRole?->identityInformation?->home_address }}--}}
                {{--                        </td>--}}
                {{--                    </tr>--}}
                {{--                    </tbody>--}}
                {{--                </table>--}}

            @endif

            <p style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px ">
                Dalam hal ini bertindak atas nama PT. Mayatama Solusindo yang berkedudukan di Jl. Sultan Hasanuddin No.
                8A,
                Kecamatan Dumai Kota, Kel. Rimba Sekampung, Kota Dumai Provinsi Riau dan selanjutnya disebut<b>PIHAK
                    PERTAMA</b>.
            </p>

            <table class="table-heading" style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <tbody>
                <tr>
                    <td style="width: 5%; padding: 5px;">Nama</td>
                    <td style="width: 1%; padding: 5px;">:</td>
                    <td style="width: 20%; padding: 5px;">{{ $contract->user->name }}</td>
                </tr>
                <tr>
                    <td style="width: 5%; padding: 5px;">Tempat, Tgl Lahir</td>
                    <td style="width: 1%; padding: 5px;">:</td>
                    <td style="width: 20%; padding: 5px;">19 Maret 1999</td>
                </tr>
                <tr>
                    <td style="width: 5%; padding: 5px;">Pendidikan terakhir</td>
                    <td style="width: 1%; padding: 5px;">:</td>
                    <td style="width: 20%; padding: 5px;">
                        {{ $contract->user->education?->level }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%; padding: 5px;">Agama</td>
                    <td style="width: 1%; padding: 5px;">:</td>
                    <td style="width: 20%; padding: 5px;">
                        {{ $contract->user->identityInformation?->religion }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%; padding: 5px;">No. KTP/SIM</td>
                    <td style="width: 1%; padding: 5px;">:</td>
                    <td style="width: 20%; padding: 5px;">
                        {{ $contract->user->identityInformation?->nik }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%; padding: 5px;">Telepon</td>
                    <td style="width: 1%; padding: 5px;">:</td>
                    <td style="width: 20%; padding: 5px;">
                        {{ $contract->user->identityInformation?->phone_number }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%; padding: 5px;">Alamat</td>
                    <td style="width: 1%; padding: 5px;">:</td>
                    <td style="width: 20%; padding: 5px;">
                        {{ $contract->user->identityInformation?->home_address }}
                    </td>
                </tr>
                </tbody>
            </table>


            <p style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px">
                Dalam hal ini bertindak untuk dan atas nama diri sendiri dan selanjutnya disebut <b>PIHAK KEDUA</b>.
            </p>


            <p style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px">
                Untuk dapat menciptakan hubungan industrialis yang harmonis yang berkeadilan antara kedua belah pihak,
                maka
                para pihak sepakat untuk membuat Perjanjian Kerja dengan ketentuan sebagai berikut :
            </p>

            <div style="text-align: center; margin-top: 75px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 1</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>RUANG LINGKUP PEKERJAAN</u></p>
            </div>


            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            <b>PIHAK PERTAMA</b> menyatakan menerima <b>PIHAK KEDUA</b> sebagai Karyawan Kontrak /
                            Perjanjian Kerja Waktu Tertentu (PKWT) di PT. Mayatama Solusindo dan <b>PIHAK KEDUA</b>
                            dengan
                            ini menyatakan kesediaannya.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK PERTAMA</b> dengan ini setuju mempekerjakan <b>PIHAK KEDUA</b>, melakukan tugas –
                            tugas
                            dan
                            tanggung jawab untuk kepentingan <b>PIHAK PERTAMA</b> dengan status dan kondisi sebagai
                            berikut
                            :
                        </p>

                        <table class="table-heading"
                               style="width: 100%; border-collapse: collapse; font-size: 11px; padding-left: 15px">
                            <tbody>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Tempat Penerimaan</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">YOGA</td>
                            </tr>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Lokasi kerja / Proyek</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">19 Maret 1999</td>
                            </tr>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Direktorat / Divisi / Dept.</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">
                                    S1
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Management Jabatan / Pekerjaan</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">
                                    Islam
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </li>
                    <li>
                        <p>
                            Dalam rangka pendayagunaan sumber daya manusia dalam memenuhi kepentingan operasional,
                            <b>PIHAK PERTAMA</b> selanjutnya berwenang mengangkat, menempatkan dan atau mengalih
                            tugaskan
                            <b>PIHAK KEDUA</b> dibagian manapun didalam Perusahaan atau anak Perusahaan Milik
                            <b>PIHAK PERTAMA</b>.
                        </p>
                    </li>
                    <li>
                        <p>
                            Tugas dan tanggung jawab <b>PIHAK KEDUA</b> akan diatur dalam Jobdesk yang ditetapkan oleh
                            Department masing-masing dengan berpedoman pada Standar Operasional Prosedur (SOP) dan
                            Peraturan
                            Perusahaan.
                        </p>
                    </li>
                    <li>
                        <p>
                            Tugas dan tanggung jawab <b>PIHAK KEDUA</b> akan diatur dalam Jobdesk yang ditetapkan oleh
                            Department masing-masing dengan berpedoman pada Standar Operasional Prosedur (SOP) dan
                            Peraturan Perusahaan.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> sebagai karyawan bersedia ditempatkan dalam wilayah kerja <b>PIHAK
                                PERTAMA</b> di
                            Negara Republik Indonesia.
                        </p>
                    </li>
                </ol>
            </div>

            <div style="text-align: center; margin-top: 75px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 2</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>MASA BERLAKU PERJANJIAN KERJA</u></p>
            </div>


            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>Perjanjian Kerja ini adalah bentuk hubungan kerja antara <b>PIHAK PERTAMA</b> dengan <b>PIHAK
                                KEDUA</b> untuk jangka waktu tertentu selama terhitung mulai tanggal 21 September 2022
                            Sampai dengan 21 September 2023. </p>
                    </li>
                    <li>
                        <p>Perjanjian Kerja ini hanya dapat diperpanjang dan/atau dapat dilakukan perubahan (addendum)
                            atas dasar persetujuan kedua belah pihak.</p>
                    </li>
                    <li>
                        <p>Selama jangka waktu tersebut masing-masing pihak dapat memutuskan hubungan kerja dengan
                            pemberitahuan secara tertulis minimal 30 (Tiga Puluh) hari kerja sesuai dengan ketentuan
                            yang ada pada Peraturan Perusahaan.</p>
                    </li>
                    <li>
                        <p>Jika setelah berakhirnya perjanjian kerja ini ternyata <b>PIHAK PERTAMA</b> dan/atau <b>PIHAK
                                KEDUA</b>
                            tidak bersedia untuk memperpanjang kontrak, maka perjanjian kerja kontrak akan berakhir
                            bersamaan dengan berakhirnya waktu perjanjian ini.</p>
                    </li>
                    <li>
                        <p>Setelah Perjanjian Kerja ini berakhir <b>PIHAK KEDUA</b> menyelesaikan kewajibannya sesuai
                            Peraturan Perusahaan.</p>
                    </li>
                    <li>
                        <p>Perjanjian Kerja ini adalah bentuk hubungan kerja antara <b>PIHAK PERTAMA</b> dengan <b>PIHAK
                                KEDUA</b>
                            untuk jangka waktu tertentu tunduk pada Peraturan Perundang-undangan yang berlaku tentang
                            pengalihan perlindungan hak – hak bagi pekerja yang obyek kerjanya tetap ada (sama) kepada
                            perusahaan alih daya</p>
                    </li>
                </ol>
            </div>


            <div style="text-align: center; margin-top: 75px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 3</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>TATA TERTIB PERUSAHAAN</u></p>
            </div>


            <p style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px">
                <b><b>PIHAK KEDUA</b> </b> bersedia mematuhi serta mentaati seluruh tata tertib yang telah ditetapkan
                <b>PIHAK
                    PERTAMA</b> yang tertuang dalam BAB X Pasal 40 sampai Pasal 46 Peraturan Perusahaan, Surat Keputusan
                Direksi, serta seluruh tata tertib yang diberlakukan secara lisan atas Keputusan Direksi.
            </p>
        </div>


        <div style="text-align: center; margin-top: 75px">
            <p style="font-size: 11px; font-weight: bold"><u>PASAL 4</u></p>
            <p style="font-size: 11px; font-weight: bold"><u>EVALUASI DAN PENILAIAN KINERJA</u></p>
        </div>

        <div style="text-align: center; margin-top: 20px; ">
            <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                <li>
                    <p>Sesuai Peraturan Perusahaan BAB III Pasal 13, <b>PIHAK PERTAMA</b> akan melakukan evaluasi dan
                        penilaian
                        kepada <b>PIHAK KEDUA</b> dalam jangka waktu tertentu untuk melihat hasil dari kinerja selama
                        menjalankan tugas. <b>PIHAK PERTAMA</b> berhak melakukan promosi maupun demosi kepada <b>PIHAK
                            KEDUA</b>
                        berdasarkan hasil penilaian.</p>
                </li>
                <li>
                    <p><b>PIHAK PERTAMA</b> dapat memberikan kesempatan kepada <b>PIHAK KEDUA</b> untuk mendapatkan
                        promosi
                        kenaikan gaji, jabatan, hingga status Karyawan Tetap berdasarkan hasil penilaian kinerja,
                        kompetensi, dengan memperhatikan kebutuhan perusahaan serta syarat administrasi.</p>
                </li>
                <li>
                    <p><b>PIHAK KEDUA</b> akan mendapatkan demosi penurunan gaji, jabatan, hingga sanksi oleh <b>PIHAK
                            PERTAMA</b> apabila dinilai tidak memenuhi standar kerja, tidak kompeten atau melakukan
                        perbuatan yang melanggar aturan disiplin kerja.</p>
                </li>

            </ol>
        </div>

        <div style="text-align: center; margin-top: 75px">
            <p style="font-size: 11px; font-weight: bold"><u>PASAL 5</u></p>
            <p style="font-size: 11px; font-weight: bold"><u>PENDAPATAN DAN PAJAK PENGHASILAN</u></p>
        </div>

        <div style="text-align: center; margin-top: 20px; ">
            <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                <li>
                    <p><b>PIHAK KEDUA</b> mendapatkan upah/gaji setiap bulannya sebagaimana disebutkan dalam Kontrak
                        ini, yang
                        besarannya ditentukan tersendiri dalam SKP Manager HRD.</p>
                </li>
                <li>
                    <p>Upah tidak dibayar apabila pekerja/buruh tidak melakukan pekerjaan tanpa keterangan mengacu pada
                        Pasal 93 ayat 1 Undang-Undang No. 13 Tahun 2003 dan Pasal 40 ayat 1 PP No. 36 Tahun 2021.</p>
                </li>
                <li>
                    <p>Apabila <b>PIHAK KEDUA</b> dalam keadaan tidak mampu melaksanakan pekerjaannya dikarenakan sakit
                        atau
                        kondisi medis tertentu berdasarkan keterangan dokter, maka upah akan dibayarkan <b>PIHAK
                            PERTAMA</b>
                        dengan skala menurun sampai dengan Masa Pemutusan Hubungan Kerja dengan merujuk kepada Peraturan
                        Perundang-undangan yang berlaku.</p>
                </li>
                <li>
                    <p>Pajak Penghasilan (Pph) Pasal 21 ditanggung oleh <b>PIHAK KEDUA</b>.</p>
                </li>
            </ol>


            <div style="text-align: center; margin-top: 75px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 6</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>TUNJANGAN HARI RAYA</u></p>
            </div>


            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            Pembayaran Tunjangan Hari Raya Keagamaan kepada karyawan dilakukan selambat - selambatnya 7
                            (tujuh) hari sebelum Hari Raya Keagamaan. Pemberiannya disesuaikan dengan Hari Raya
                            Keagamaan masing-masing karyawan.
                        </p>
                    </li>
                    <li>
                        <p>
                            Perusahaan wajib memberi THR kepada karyawan tidak tetap. Hanya saja, jumlah THR-nya
                            berbeda-beda sesuai masa bakti mereka. Karyawan tidak tetap yang telah selama 12 bulan atau
                            lebih berhak menerima THR sebesar satu bulan upah.
                        </p>
                    </li>
                    <li>
                        <p>
                            Sementara itu, karyawan yang masih bekerja kurang dari 12 bulan menerima THR secara
                            proporsional. Perhitungannya sebagai berikut: masa kerja (dalam hitungan bulan)/12 bulan x
                            satu bulan upah.
                        </p>
                    </li>
                    <li>
                        <p>
                            Pemberian THR dihitung sesuai dengan ketentuan Peraturan Perundang-undangan yang berlaku.
                        </p>
                    </li>
                </ol>
            </div>

            <div style="text-align: center; margin-top: 95px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 7</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>HAK CUTI</u></p>
            </div>


            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            Cuti tahunan diberikan kepada <b>PIHAK KEDUA</b> setelah 12 bulan bekerja selama terus
                            menerus
                            sebanyak
                            12 (dua belas) bulan dalam 1 (satu) tahun yang diatur oleh <b>PIHAK PERTAMA</b> berdasarkan
                            kebutuhan,
                            mengacu kepada Peraturan Perundang-undangan yang berlaku.
                        </p>
                    </li>
                    <li>
                        <p>
                            Berdasarkan keperluan tugas dan pekerjaan, <b>PIHAK PERTAMA</b> dapat menunda permohonan
                            cuti yang
                            diajukan <b>PIHAK KEDUA</b>.
                        </p>
                    </li>
                    <li>
                        <p>
                            Cuti hamil dan melahirkan, sesuai namanya, jenis cuti ini adalah hak bagi karyawan perempuan
                            yang sedang mengandung dan akan melahirkan.
                            Hal tersebut sudah dituang dalam UU No. 13 Tahun 2003, di mana pekerja perempuan yang hamil
                            berhak menerima jatah cuti selama 1,5 bulan sebelum melahirkan dan 1,5 bulan setelah
                            melahirkan. Namun, pada beberapa perusahaan cuti hamil dan melahirkan sudah diatur secara
                            akumulatif dalam waktu 3 bulan.
                        </p>
                    </li>
                    <li>
                        <p>
                            Cuti sakit, jenis cuti satu ini merupakan waktu rehat yang diperoleh pekerja saat sedang
                            sakit dan tidak mampu melakukan tugasnya. Kebijakannya pun sudah dipaparkan dalam pasal 93
                            ayat (2) huruf a Undang-undang Nomor 13 tahun 2003 tentang Ketenagakerjaan (UU 13/2003). Di
                            dalamnya, dijelaskan bahwa perusahaan wajib membayar upah pekerja yang sakit. Dalam kata
                            lain, perusahaan memberikan kesempatan bagi pekerja untuk beristirahat selama sedang tidak
                            bugar.
                            Istirahat sakit diberikan berdasarkan Surat Keterangan Dokter terhadap karyawan yang
                            terganggu kesehatannya atau penyakitnya dinyatakan berbahaya bagi kesehatan orang lain
                            dengan maksimal 2 hari dalam sebulan.
                        </p>
                    </li>
                    <li>
                        <p>Berdasarkan Pasal 93 Ayat (2) dan (4) UU Ketenagakerjaan disebutkan bahwa bahwa hak cuti
                            dengan alasan penting memiliki ketentuan sebagai berikut:
                        </p>
                        <ol>
                            <li>Karyawan menikah 3 hari</li>
                            <li> Karyawan menikahkan anaknya 2 hari</li>
                            <li> Mengkhitankan anak 2 hari</li>
                            <li> Membaptis anak 2 hari</li>
                            <li>Istri melahirkan atau keguguran kandungan 2 hari</li>
                            <li> Suami/istri, orang tua/mertua atau anak atau menantu meninggal dunia 2 hari</li>
                            <li> Anggota keluarga dalam satu rumah meninggal dunia 1 hari</li>
                        </ol>
                    </li>
                </ol>
            </div>


            <div style="text-align: center; margin-top: 75px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 8</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>HAK DAN KOMPENSASI</u></p>
            </div>


            <p style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px">
                Jika Perjanjian Kerja berakhir sesuai Pasal 2, <b>PIHAK PERTAMA</b> berkewajiban memberikan Kompensasi
                sesuai
                Peraturan yang berlaku dengan ketentuan sebagai berikut :
            </p>

            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> yang telah bekerja selama 12 bulan secara terus menerus, diberikan
                            kompensasi
                            sebesar satu bulan upah.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> yang telah bekerja selama satu bulan atau lebih tetapi kurang dari 12
                            bulan,
                            dihitung secara proporsional. Perhitungannya adalah masa kerja kali satu bulan upah.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> yang telah bekerja lebih dari 12 bulan, dihitung secara proporsional
                            dengan
                            perhitungan masa kerja kali satu bulan Upah.
                        </p>
                    </li>
                    <li>
                        <p>Upah yang digunakan sebagai dasar perhitungan pembayaran uang kompensasi terdiri atas upah
                            pokok.</p>
                    </li>
                </ol>
            </div>


            <div style="text-align: center; margin-top: 75px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 9</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>HARI, JAM KERJA DAN LEMBUR</u></p>
            </div>

            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            Jam dan Hari kerja sesuai dengan jadwal kerja yang ditetapkan oleh <b>PIHAK PERTAMA</b>
                            (Perusahaan) mengacu kepada Peraturan Perundang-undangan yang berlaku.
                        </p>

                        <table class="table-heading"
                               style="width: 100%; border-collapse: collapse; font-size: 11px; padding-left: 15px">
                            <tbody>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Senin-Kamis</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">08:00 – 17:00 WIB.</td>
                            </tr>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Istirahat</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">12:00 – 13:00 WIB</td>
                            </tr>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Jumat</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">
                                    08:00 – 17:00 WIB
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Istirahat</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">
                                    11:30 – 13:00 WIB
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Sabtu</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">
                                    08:00 – 17:00 WIB
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Istirahat</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">
                                    12:00 – 13:00 WIB
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 10%; padding: 5px;">Minggu</td>
                                <td style="width: 1%; padding: 5px;">:</td>
                                <td style="width: 20%; padding: 5px;">
                                    Libur Kecuali Lembur
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> berada ditempat kerja 15 (lima belas) menit sebelum tugas dimulai dan
                            meninggalkan tempat tugas 15 (lima belas) menit setelah tugas selesai, waktu yang dimaksud
                            digunakan untuk kesiapan serah terima tugas (bagi yang dapat Shift).
                        </p>
                    </li>
                    <li>
                        <p>
                            Bagi Karyawan yang berhak lembur (karyawan tanpa tunjangan jabatan atau dibawah level
                            Supervisor) maka bekerja melebihi jam kerja kumulatif 40 jam seminggu dihitung sebagai kerja
                            lembur dimana perhitungannya mengacu Undang-Undang No. 11 Tahun 2020 tentang Cipta Kerja
                            terkait Klaster Ketenagakerjaan dan Peraturan Pemerintah No. 35 tahun 2021 terkait Waktu
                            Kerja & Waktu Istirahat.
                        </p>
                    </li>
                </ol>
            </div>


            <div style="text-align: center; margin-top: 75px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 10</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>TUGAS DAN KEWAJIBAN <b>PIHAK KEDUA</b></u></p>
            </div>


            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p><b>PIHAK KEDUA</b> wajib :</p>
                        <ol type="a">
                            <li>
                                <p>
                                    Melaksanakan pekerjaan dengan sebaik-baiknya seperti yang telah ditetapkan oleh
                                    <b>PIHAK PERTAMA</b>, atau Pejabat/Petugas yang diberi wewenang oleh <b>PIHAK
                                        PERTAMA</b>.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Peralihan pelaksanaan tanggung jawab pekerjaan <b>PIHAK KEDUA</b> kepada Pihak Lain
                                    wajib
                                    mendapatkan persetujuan dari <b>PIHAK PERTAMA</b>;
                                </p>
                            </li>
                            <li>
                                <p>
                                    Mematuhi peraturan dan tata tertib dan disiplin yang berlaku ditempat kerja;
                                </p>
                            </li>
                            <li>
                                <p>
                                    <b>PIHAK KEDUA</b> wajib melaksanakan tugas dan kewajibannya dengan penuh disiplin
                                    dan
                                    mematuhi ketentuan-ketentuan yang tercantum dalam Peraturan Perusahaan dan ketentuan
                                    lainnya yang diterbitkan oleh Perusahaan dan/atau yang berlaku di lokasi kerja.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Menjaga nama baik <b>PIHAK PERTAMA</b> baik didalam maupun diluar tempat kerja;
                                </p>
                            </li>
                            <li>
                                <p>
                                    Bersikap sopan didalam maupun diluar Perusahaan dan taat terhadap segala
                                    peraturan-peraturan dan ketentuan-ketentuan yang berlaku ditempat kerja;
                                </p>
                            </li>

                        </ol>
                        <p><b>PIHAK KEDUA</b> tidak dibenarkan untuk :</p>
                        <ol type="a">
                            <li>
                                <p>
                                    Bekerja atau menjadi karyawan pada Perusahaan lain kecuali dalam satu grup
                                    Perusahaan.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Berusaha mencari keuntungan bagi dirinya dengan menyalah gunakan jabatan, fasilitas,
                                    data-data atau kedudukan ditempat kerja.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Merusak, menggunakan atau membawa keluar tanpa izin barang-barang milik PIHAK
                                    PERTAMA
                                    seperti alat-alat dan barang-barang lainnya. Kerusakan dan atau kerugian yang
                                    diderita <b>PIHAK PERTAMA</b> sebagai akibat dari perbuatan-perbuatan tersebut
                                    dibebankan
                                    kepada <b>PIHAK KEDUA</b> dengan cara memotong upah.
                                </p>
                            </li>
                        </ol>
                        <p>
                            <b>PIHAK KEDUA</b> diharuskan memperoleh izin terlebih dahulu dari <b>PIHAK PERTAMA</b> atau
                            petugas yang
                            ditunjuk oleh <b>PIHAK PERTAMA</b> apabila akan melakukan hal-hal tersebut dibawah ini:
                        </p>
                        <ol type="a">
                            <li>
                                <p>
                                    Masuk kerja terlambat
                                </p>
                            </li>
                            <li>
                                <p>
                                    Pulang lebih dahulu daripada waktu berakhirnya jam-jam kerja.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Meninggalkan tempat kerja pada waktu jam kerja.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Tidak masuk kerja
                                </p>
                            </li>
                        </ol>
                    </li>
                </ol>
            </div>


            <div style="text-align: center; margin-top: 130px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 11</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>TUGAS DAN KEWAJIBAN <b>PIHAK PERTAMA</b></u></p>
            </div>


            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>Kewajiban <b>PIHAK PERTAMA</b> adalah:</p>
                        <ol type="a">
                            <li>
                                <p>
                                    Mempekerjakan dan menempatkan <b>PIHAK KEDUA</b> sesuai dengan Perjanjian Kerja ini.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Membayar UPAH <b>PIHAK KEDUA</b> sesuai dengan Perjanjian Kerja ini.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Menjaga keselamatan lingkungan kerja.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Mentaati serta memenuhi kewajiban lainnya yg tercantum dalam perjanjian kerja ini.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Mentaati serta memenuhi kewajiban lainnya yg tercantum dalam perjanjian kerja ini.
                                </p>
                            </li>
                        </ol>
                    </li>
                </ol>
            </div>


            <div style="text-align: center; margin-top: 95px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 12</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>FASILITAS KERJA</u></p>
            </div>


            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> dalam menjalankan pekerjaan selama masa kontrak berhak menerima fasilitas
                            berupa
                            Perlengkapan dan Peralatan Kerja / Alat Pelindung Diri (APD), didaftarkan ke Badan
                            Penyelenggara Jaminan Sosial (BPJS) Ketenagakerjaan dan Kesehatan, Akomodasi Perjalanan
                            Dinas, dan Fasilitas lain yang ditentukan tersendiri oleh <b>PIHAK PERTAMA</b> melalui Surat
                            Keputusan Direksi sesuai status dan jabatan <b>PIHAK KEDUA</b>.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> diikutsertakan dalam program BPJS Ketenagakerjaan sesuai dengan Peraturan
                            Perundang-undangan yang berlaku, dan <b>PIHAK KEDUA</b> bersedia dipotong pendapatannya
                            sesuai
                            dengan ketentuan yang berlaku untuk iuran BPJS Ketenagakerjaan dan Perusahaan menanggung
                            iuran sesuai Peraturan Perundang-undangan yang berlaku.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK PERTAMA</b> menyediakan Fasilitas Kerja yang tertuang dalam BAB VII Pasal 31 sampai
                            dengan
                            Pasal 33 Peraturan Perusahaan kepada <b>PIHAK KEDUA</b>.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> bersedia mematuhi segala ketentuan yang tertuang dalam BAB VII Pasal 31
                            sampai
                            dengan Pasal 33 Peraturan Perusahaan.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> yang ditugaskan untuk melakukan perjalanan dinas keluar lokasi kerja
                            berhak
                            mendapatkan fasilitas berupa :
                        </p>
                        <ol>
                            <li>
                                <p>
                                    Uang Transportasi yang jumlahnya disesuaikan dengan kebutuhan perjalanan.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Uang Penginapan/Hotel yang jumlahnya disesuaikan dengan kebutuhan.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Uang Makan sebesar Rp. 30.000,- per hari selama melakukan perjalanan dinas.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Uang Akomodasi lainnya didasarkan pada bon/kwitansi sebenarnya yang telah disetujui
                                    oleh <b>PIHAK PERTAMA</b> selama menjalankan tugas perjalanan dinas.
                                </p>
                            </li>
                        </ol>
                    </li>
                    <li>
                        <p>
                            Untuk memelihara keselamatan kerja, <b>PIHAK PERTAMA</b> menyediakan alat-alat keselamatan
                            kerja
                            bagi <b>PIHAK KEDUA</b> sesuai bidang tugas masing-masing.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> diwajibkan memakai alat-alat pelindung keselamatan kerja sesuai dengan
                            sifat
                            pekerjaan masing-masing dan wajib mentaati peraturan-peraturan serta syarat-syarat
                            keselamatan kerja.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> diwajibkan memelihara alat-alat keselamatan kerja yang disediakan oleh
                            PIHAK
                            PERTAMA dan menjaga lingkungan kerja yang sehat.
                        </p>
                    </li>
                    <li>
                        <p>
                            Apabila alat-alat kerja tersebut hilang atau rusak karena kesalahan <b>PIHAK KEDUA</b>, <b>PIHAK
                                KEDUA</b>
                            wajib memberikan ganti rugi kepada <b>PIHAK PERTAMA</b>.
                        </p>
                    </li>

                </ol>
            </div>


            <div style="text-align: center; margin-top: 95px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 13 </u></p>
                <p style="font-size: 11px; font-weight: bold"><u>KESELAMATAN KERJA</u></p>
            </div>


            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            Untuk memelihara keselamatan kerja, <b>PIHAK PERTAMA</b> menyediakan alat-alat keselamatan
                            kerja
                            bagi <b>PIHAK KEDUA</b> sesuai bidang tugas masing-masing.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> diwajibkan memakai alat-alat pelindung keselamatan kerja sesuai dengan
                            sifat
                            pekerjaan masing-masing dan wajib mentaati peraturan-peraturan serta syarat-syarat
                            keselamatan kerja.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> diwajibkan memelihara alat-alat keselamatan kerja yang disediakan oleh
                            PIHAK
                            PERTAMA dan menjaga lingkungan kerja yang sehat.
                        </p>
                    </li>
                    <li>
                        <p>
                            Apabila alat-alat kerja tersebut hilang atau rusak karena kesalahan <b>PIHAK KEDUA</b>, <b>PIHAK
                                KEDUA</b>
                            wajib memberikan ganti rugi kepada PIHAK PERTAM
                        </p>
                    </li>
                </ol>
            </div>


            <div style="text-align: center; margin-top: 95px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 14</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>KERJA RANGKAP</u></p>
            </div>

            <div style="text-align: center; margin-top: 20px; ">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            Selama masa berlakunya perjanjian kerja ini <b>PIHAK KEDUA</b> tidak dibenarkan melakukan
                            kerja
                            rangkap di perusahaan lain manapun juga dengan mengemukakan dalih atau alasan apa pun juga.
                        </p>
                    </li>
                    <li>
                        <p>
                            Pelanggaran atas Ayat 1 (satu) diatas oleh <b>PIHAK KEDUA</b>, maka <b>PIHAK PERTAMA</b>
                            dapat
                            menjatuhkan
                            sanksi sesuai Pasal 17 Perjanjian ini disesuaikan dengan berat/ringannya dampak bagi PIHAK
                            PERTAMA.
                        </p>
                    </li>
                </ol>
            </div>


            <div style="text-align: center; margin-top: 95px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 15</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>PEMUTUSAN HUBUNGAN KERJA ( PHK )</u></p>
            </div>


            <p style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px">
                Putusnya hubungan kerja dapat terjadi oleh macam - macam sebab, antara lain :
            </p>

            <div style="text-align: center;">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            Karena pelanggaran peraturan tata tertib kerja dan / atau perbuatan tindak pidana / hukum
                            Negara.
                        </p>
                    </li>
                    <li>
                        <p>
                            Karena melakukan pelanggaran berat.
                        </p>
                    </li>
                    <li>
                        <p>
                            Karena menderita sakit berkepanjangan dan / atau ketidak mampuan bekerja karena kesehatan.
                        </p>
                    </li>
                    <li>
                        <p>
                            Karena tidak mampu mencapai standar prestasi kerja yang ditentukan.
                        </p>
                    </li>
                    <li>
                        <p>
                            Karena alasan mendesak.
                        </p>
                    </li>
                    <li>
                        <p>
                            Karena mencapai usia pensiun.
                        </p>
                    </li>
                    <li>
                        <p>
                            Karena alasan lainnya, yakni :
                        </p>
                        <ol type="a">
                            <li>
                                <p>
                                    Tidak memenuhi persyaratan kerja dalam masa percobaan.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Mengundurkan diri.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Meninggal dunia.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Berakhirnya masa Kontrak Kerja atau Perjanjian Kerja Waktu Tertentu.
                                </p>
                            </li>
                        </ol>
                    </li>
                    <li>
                        <p>
                            Karena pekerja/buruh tidak dapat melakukan pekerjaan selama 6 (enam) bulan akibat ditahan
                            pihak yang berwajib karena diduga melakukan tindak pidana;
                        </p>
                    </li>
                    <li>
                        <p>
                            Perusahaan melakukan penggabungan, peleburan, pengambil alihan, atau pemisahan perusahaan
                            dan pekerja/buruh tidak bersedia melanjutkan hubungan kerja atau pengusaha tidak bersedia
                            menerima pekerja/buruh.
                        </p>
                    </li>
                    <li>
                        <p>
                            Perusahaan melakukan efisiensi diikuti dengan penutupan perusahaan atau tidak diikuti dengan
                            penutupan perusahaan yang disebabkan perusahaan mengalami kerugian.
                        </p>
                    </li>
                    <li>
                        <p>
                            Perusahaan tutup yang disebabkan karena perusahaan mengalami kerugian secara terus menerus
                            selama 2 (dua) tahun.
                        </p>
                    </li>
                    <li>
                        <p>
                            Perusahaan tutup yang disebabkan keadaan memaksa (force majeur).
                        </p>
                    </li>
                    <li>
                        <p>
                            Perusahaan dalam keadaan penundaan kewajiban pembayaran utang.
                        </p>
                    </li>
                    <li>
                        <p>
                            Perusahaan pailit.
                        </p>
                    </li>
                    <li>
                        <p>
                            Adanya permohonan pemutusan hubungan kerja yang diajukan oleh pekerja/buruh dengan alasan
                            pengusaha melakukan perbuatan sebagai berikut:
                        </p>
                        <ol>
                            <li>
                                <p>
                                    menganiaya, menghina secara kasar atau mengancam pekerja/ buruh
                                </p>
                            </li>
                            <li>
                                <p>
                                    membujuk dan/atau menyuruh pekerja/buruh untuk melakukan perbuatan yang bertentangan
                                    dengan peraturan perundang-undangan
                                </p>
                            </li>
                            <li>
                                <p>
                                    tidak membayar upah tepat pada waktu yang telah ditentukan selama 3 (tiga) bulan
                                    berturut-turut atau lebih, meskipun pengusaha membayar upah secara tepat waktu
                                    sesudah itu.
                                </p>
                            </li>
                            <li>
                                <p>
                                    tidak melakukan kewajiban yang telah dijanjikan kepada pekerja/ buruh
                                </p>
                            </li>
                            <li>
                                <p>
                                    memerintahkan pekerja/buruh untuk melaksanakan pekerjaan di luar yang diperjanjikan.
                                </p>
                            </li>
                            <li>
                                <p>
                                    memberikan pekerjaan yang membahayakan jiwa, keselamatan, kesehatan, dan kesusilaan
                                    pekerja/buruh sedangkan pekerjaan tersebut tidak dicantumkan pada perjanjian kerja.
                                </p>
                            </li>
                        </ol>
                    </li>
                    <li>
                        <p>
                            Adanya putusan lembaga penyelesaian perselisihan hubungan industrial yang menyatakan
                            pengusaha tidak melakukan perbuatan terhadap permohonan yang diajukan oleh pekerja/buruh dan
                            pengusaha memutuskan untuk melakukan pemutusan hubungan kerja.
                        </p>
                    </li>
                    <li>
                        <p>
                            Pekerja/buruh mangkir selama 5 (lima) hari kerja atau lebih berturut-turut tanpa keterangan
                            secara tertulis yang dilengkapi dengan bukti yang sah dan telah dipanggil oleh pengusaha 2
                            (dua) kali secara patut dan tertulis;
                        </p>
                    </li>
                </ol>
            </div>


            <div style="text-align: center; margin-top: 95px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 16</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>PENGUNDURAN DIRI</u></p>
            </div>


            <div style="text-align: center;">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            Jika <b>PIHAK KEDUA</b> mengundurkan diri secara baik-baik, maka <b>PIHAK KEDUA</b> berhak
                            menerima upah
                            gaji, tunjangan, dan lembur sesuai dengan jumlah hari kerja yang telah dijalaninya pada
                            bulan berjalan.
                        </p>
                    </li>
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> berhak menerima Kompensasi sesuai dengan peraturan Perundang-undangan.
                        </p>
                    </li>

                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> yang mengakhiri hubungan kerja diwajibkan membayar ganti rugi kepada
                            PIHAK
                            PERTAMA sebesar upah pekerja/buruh sampai batas waktu berakhirnya jangka waktu perjanjian
                            kerja.
                        </p>
                    </li>

                    <li>
                        <p>
                            Pengunduran diri secara baik-baik diperlihatkan dengan cara-cara sebagai berikut:
                        </p>
                        <ol>
                            <li>
                                <p>
                                    <b>PIHAK KEDUA</b> telah mengajukan surat permohonan pengunduran diri sesuai Pasal 2
                                    ayat 3
                                    Perjanjian ini.
                                </p>
                            </li>
                            <li>
                                <p>
                                    <b>PIHAK KEDUA</b> tetap melaksanakan tugas dan kewajibannya serta melakukan serah
                                    terima
                                    tugas kepada penggantinya (apabila ada) hingga batas waktu pengunduran dirinya
                                    berlaku.
                                </p>
                            </li>
                            <li>
                                <p>
                                    <b>PIHAK KEDUA</b> telah menyerahkan barang-barang yang dipercayakan kepadanya dan
                                    juga
                                    telah menyelesaikan admnistrasi keuangan yang harus diselesaikannya.
                                </p>
                            </li>
                        </ol>
                    </li>
                    <li>
                        <p>
                            16.5 <b>PIHAK PERTAMA</b> dengan kebijakannya dapat meminta <b>PIHAK KEDUA</b> untuk
                            meninggalkan
                            perusahaan lebih awal dengan pembayaran upah penuh selama 1 (satu) bulan.
                        </p>
                    </li>
                </ol>
            </div>


            <div style="text-align: center; margin-top: 95px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 17</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>PELANGGARAN BERIKUT SANKSI</u></p>
            </div>


            <div style="text-align: center;">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            <b>PIHAK PERTAMA</b> dapat melakukan dan menjatuhkan sanksi kepada <b>PIHAK KEDUA</b>
                            bilamana
                            <b>PIHAK KEDUA</b>
                            melakukan pelanggaran tata tertib atau perbuatan-perbuatan tercela. Sanksi diberikan berupa:
                        </p>
                        <ul>
                            <li>
                                <p>
                                    Peringatan Lisan atau Teguran Lisan. <br>
                                    Pemberian peringatan lisan ini harus didokumentasikan/dicatat oleh atasan langsung
                                    karyawan. Apabila peringatan lisan belum menghasilkan suatu perbaikan di dalam
                                    tingkah laku serta prestasi kerja karyawan, atau apabila terjadi suatu pelanggaran
                                    lain maka surat peringatan tertulis harus diberikan kepada karyawan.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Surat Peringatan I
                                </p>
                                <ul>
                                    <li>
                                        <p>Penundaan kenaikan Gaji dan/atau Jabatan selama 6 Bulan.</p>
                                    </li>
                                    <li>
                                        <p>Tidak mendapatkan bonus dan/atau tunjangan tidak tetap selama 6 Bulan.</p>
                                    </li>
                                    <li>
                                        <p>Sanksi lain yang harus dijalani sesuai dengan pelanggaran yang dilakukan.</p>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <p>
                                    Surat Peringatan II
                                </p>
                                <ul>
                                    <li>
                                        <p>Penundaan kenaikan Gaji dan/atau Jabatan selama 12 Bulan.</p>
                                    </li>
                                    <li>
                                        <p>Tidak mendapatkan bonus dan/atau tunjangan tidak tetap selama 12 Bulan.</p>
                                    </li>
                                    <li>
                                        <p>Sanksi lain yang harus dijalani sesuai dengan pelanggaran yang dilakukan.</p>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <p>
                                    Surat Peringatan III
                                </p>
                                <ul>
                                    <li>
                                        <p>Tidak mendapatkan bonus dan/atau tunjangan tidak tetap selama 12 Bulan.</p>
                                    </li>
                                    <li>
                                        <p>Penurunan status, gaji, atau jabatan (Demosi)</p>
                                    </li>
                                    <li>
                                        <p>Mutasi</p>
                                    </li>
                                    <li>
                                        <p>Pemutusan Hubungan Kerja.</p>
                                    </li>
                                    <li>
                                        <p>Penundaan kenaikan Gaji dan/atau Jabatan hingga waktu yang tidak
                                            ditentukan.</p>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <p>
                            Karyawan yang mendapatkan Surat Teguran, Surat Peringatan Pertama (SP I), Surat Peringatan
                            Kedua (SP II), atau Surat Peringatan Ketiga (SP III) mendapatkan konsekuensi berupa
                            penurunan penilaian kinerjanya dan dapat juga berdampak pada penundaan kenaikan upah,
                            pencabutan / penurunan pangkat / jabatan, pembebanan denda / ganti rugi yang langsung
                            dipotong dari upah, dan / atau pencabutan fasilitas - fasilitas tertentu sesuai dengan
                            tingkat dan jenis pelanggaran yang dilakukan.
                        </p>
                    </li>
                </ol>
            </div>

            <div style="text-align: center; margin-top: 95px">
                <p style="font-size: 11px; font-weight: bold"><u>PASAL 18</u></p>
                <p style="font-size: 11px; font-weight: bold"><u>HAK BELA DIRI</u></p>
            </div>


            <div style="text-align: center;">
                <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                    <li>
                        <p>
                            <b>PIHAK KEDUA</b> mempunyai hak bela diri atas sanksi-sanksi yang dijatuhkan oleh <b>PIHAK
                                PERTAMA</b>,
                            bilamana sanksi yang dijatuhkan tidak tetap apabila tidak benar menurutnya sebagaimana
                            situasi kejadian di lapangan;
                        </p>
                    </li>
                    <li>
                        <p>
                            Hak beladiri baru dapat dilaksanakan bilamana sanksi telah dijatuhkan.
                        </p>
                    </li>
                    <li>
                        <p>
                            Hak beladiri dapat disampaikan secara lisan dan secara tertulis.
                        </p>
                        <ol type="a">
                            <li>
                                <p>
                                    Hak Beladiri Secara Lisan <br>
                                    Hak beladiri ini disampaikan kepada <b>PIHAK PERTAMA</b> dengan mengambil waktu
                                    setelah
                                    tenang dan ditempat/ ruang kerja atasan atau ruang yang ditetapkan untuk itu.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Hak Beladiri Secara Tertulis <br>
                                    Disampaikan secara tertulis atas keberatan-keberatan yang diajukan <b>PIHAK
                                        KEDUA</b>
                                    berdasarkan fakta dilapangan dan disampaikan kepada <b>PIHAK PERTAMA</b> secara
                                    berjenjang
                                    sampai pada jenjang tertinggi Perusahaan.
                                    Hak beladiri disampaikan dengan tetap memperhatikan azas musyawarah dan mufakat.
                                </p>
                            </li>
                        </ol>
                    </li>
                </ol>


                <div style="text-align: center; margin-top: 95px">
                    <p style="font-size: 11px; font-weight: bold"><u>PASAL 19</u></p>
                    <p style="font-size: 11px; font-weight: bold"><u>KEADAAN DARURAT (FORCE MAJEUR)</u></p>
                </div>


                <p style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px">
                    Perjanjian kerja ini batal dengan sendirinya jika karena keadaan atau situasi yang memaksa, seperti:
                    bencana alam, pemberontakan, perang, huru-hara, kerusuhan, Peraturan Pemerintah atau apapun yang
                    mengakibatkan perjanjian kerja ini tidak mungkin lagi untuk diwujudkan.
                </p>

                <div style="text-align: center; margin-top: 95px">
                    <p style="font-size: 11px; font-weight: bold"><u>PASAL 20</u></p>
                    <p style="font-size: 11px; font-weight: bold"><u>PENYELESAIAN PERSELISIHAN</u></p>
                </div>


                <div style="text-align: center;">
                    <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                        <li>
                            <p>
                                Penyelesaian perselisihan hubungan industrial antara pengusaha dan pekerja sangat
                                diperlukan demi terciptanya hubungan industrial yang harmonis dan kondusif antara kedua
                                belah pihak.
                            </p>
                        </li>
                        <li>
                            <p>
                                Aturan Ketenagakerjaan menegaskan penyelesaian perselisihan hubungan industrial wajib
                                dilaksanakan oleh pengusaha dan pekerja/buruh atau serikat pekerja/serikat buruh melalui
                                perundingan bipartit secara musyawarah untuk mufakat. Namun dalam hal penyelesaian
                                secara musyawarah untuk mufakat tidak tercapai, maka pengusaha dan pekerja/ buruh atau
                                serikat pekerja/serikat buruh menyelesaikan perselisihan hubungan industrial melalui
                                prosedur penyelesaian perselisihan hubungan industrial yang diatur dengan undang-undang
                                yakni Undang-undang Nomor 2 Tahun 2004 tentang Penyelesaian Hubungan Industrial (UU
                                2/2004).
                            </p>
                        </li>
                        <li>
                            <p>
                                Prosedur yang disediakan antara lain melalui mediasi hubungan industrial atau konsiliasi
                                hubungan industrial atau arbitrase hubungan industrial. Bila masih juga gagal, maka
                                perselisihan hubungan industrial dapat dimintakan untuk diselesaikan pada Pengadilan
                                Hubungan Industrial yang ada pada setiap Pengadilan Negeri Kabupaten/Kota yang berada di
                                setiap Ibukota Provinsi, yang daerah hukumnya meliputi tempat kerja pekerja.
                            </p>
                        </li>
                    </ol>
                </div>

                <div style="text-align: center; margin-top: 95px">
                    <p style="font-size: 11px; font-weight: bold"><u>PASAL 21</u></p>
                    <p style="font-size: 11px; font-weight: bold"><u>BERAKHIRNYA PERJANJIAN KERJA</u></p>
                </div>


                <div style="text-align: center;">
                    <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                        <li>
                            <p>
                                Hubungan kerja antara karyawan PKWT dan pengusaha berakhir dengan sendirinya atau putus
                                demi hukum apabila terpenuhi salah satu kondisi berikut:
                            </p>
                            <ol type="a">
                                <li>
                                    <p>
                                        angka waktu perjanjian yang tercantum dalam PKWT telah selesai; atau pekerjaan
                                        yang diperjanjikan selesai lebih cepat dari jangka waktu PKWT.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Hubungan kerja PKWT juga dapat berakhir melalui penghentian kontrak oleh salah
                                        satu pihak, yakni pengusaha memutus kontrak atau karyawan mengundurkan diri
                                        (resign).
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Pada saat berakhirnya hubungan kerja PKWT, karyawan tidak memperoleh pesangon,
                                        tetapi berhak atas uang kompensasi.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Pengusaha memutus kontrak apabila <b>PIHAK KEDUA</b> dinilai tidak memenuhi
                                        standar
                                        kerja, tidak kompeten atau melakukan perbuatan yang melanggar aturan disiplin
                                        kerja.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Perjanjian kerja ini akan berakhir dengan sendirinya jika <b>PIHAK KEDUA</b>
                                        meninggal
                                        dunia.
                                    </p>
                                </li>
                            </ol>
                        </li>
                    </ol>
                </div>


                <div style="text-align: center; margin-top: 75px">
                    <p style="font-size: 11px; font-weight: bold"><u>PASAL 22</u></p>
                    <p style="font-size: 11px; font-weight: bold"><u>PENUTUP</u></p>
                </div>


                <div style="text-align: center;">
                    <ol style="font-size: 11px; text-align: justify;  text-justify: inter-word; margin-top: 11px; padding: 0; line-height: 2">
                        <li>
                            Demikianlah Perjanjian Kerja ini dibuat dalam rangkap 2 (dua) dan setelah dibaca, dimengerti
                            dan ditandatangani oleh kedua belah pihak diatas materai secukupnya, maka apabila dikemudian
                            hari terjadi suatu sengketa, perbedaan dan/atau pertentangan, kedua belah pihak sepakat
                            untuk menyelesaikannya secara musyawarah.
                        </li>
                        <li>
                            Perjanjian Kerja ini berlaku dan mengikat sesuai dengan Pasal 2 ayat 3 dengan ketentuan bila
                            ada kekeliruan atau kekurangan, kedua belah pihak sepakat untuk diadakan perubahan
                            dikemudian hari.
                        </li>
                    </ol>
                </div>


            </div>


        </div>
        <div style="margin-top: 30px"></div>
        <div style="text-align: center;">

            @if($contract->user?->branch?->code === null || $contract->user?->branch?->code === 100)
                <div style="display: inline-block; vertical-align: top; margin-right: 30px;">
                    <table style="border-collapse: collapse; margin: 0;">
                        <tr>
                            <th style="padding: 8px; text-align: center;">
                                <p style="margin: 0; font-size: 12px;">PIHAK PERTAMA</p>
                            </th>
                        </tr>
                        <tr>
                            <th style="text-align: center; padding-bottom: 75px;">
                                <p style="font-size: 12px; margin: 0;"></p>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding: 8px; text-align: center;">
                                <p style="margin: 0; font-size: 12px; text-decoration: underline;">{{ $directorRole->name }}</p>
                            </th>
                        </tr>
                    </table>
                </div>
                {{--            @else--}}
                {{--                <div style="display: inline-block; vertical-align: top; margin-right: 30px;">--}}
                {{--                    <table style="border-collapse: collapse; margin: 0;">--}}
                {{--                        <tr>--}}
                {{--                            <th style="padding: 8px; text-align: center;">--}}
                {{--                                <p style="margin: 0; font-size: 12px;">PIHAK PERTAMA</p>--}}
                {{--                            </th>--}}
                {{--                        </tr>--}}
                {{--                        <tr>--}}
                {{--                            <th style="text-align: center; padding-bottom: 75px;">--}}
                {{--                                <p style="font-size: 12px; margin: 0;"></p>--}}
                {{--                            </th>--}}
                {{--                        </tr>--}}
                {{--                        <tr>--}}
                {{--                            <th style="padding: 8px; text-align: center;">--}}
                {{--                                <p style="margin: 0; font-size: 12px; text-decoration: underline;">{{ $branchManagerRole->name }}</p>--}}
                {{--                            </th>--}}
                {{--                        </tr>--}}
                {{--                    </table>--}}
                {{--                </div>--}}
            @endif

            <div style="display: inline-block; vertical-align: top; margin-left: 200px;">
                <table style="border-collapse: collapse; margin: 0;">
                    <tr>
                        <th style="padding: 8px; text-align: center;">
                            <p style="margin: 0; font-size: 12px;">PIHAK KEDUA</p>
                        </th>
                    </tr>
                    <tr>
                        <th style="text-align: center; padding-bottom: 75px;">
                            <p style="font-size: 12px; margin: 0;"></p>
                        </th>
                    </tr>
                    <tr>
                        <th style="padding: 8px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; text-decoration: underline;">{{ $contract->user->name }}</p>
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</main>


</body>
</html>
