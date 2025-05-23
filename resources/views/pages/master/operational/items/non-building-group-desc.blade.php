@extends('layouts.template')
@section('page-title', 'Jenis Harta dalam Penyusutan Fiskal')
@section('content')

    <div class="card card-xl-stretch mb-5 mb-xl-8">
        <div class="card-header border-0 pt-6 border border-gray-200">
            <div class="card-title">
                <a onclick="window.close();" class="btn btn-light-danger btn-sm">
                    <x-icons.back/>
                    Kembali
                </a>

            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">

                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <h1>Jenis Harta dalam Penyusutan Fiskal</h1>
            <p class="fs-6">
                Jenis harta astau aset berwujud terbagi menjadi beberapa kelompok tergantung jenis usahanya. Berikut
                pengelompokan atas jenis harta berwujud dalam penyusutan fiskal:
            </p>

            <h2>A. Jenis Harta Berwujud Kelompok I</h2>
            <p class="fs-6 mb-4">
                Berikut jenis harta penyusutan fiskal atau jenis-jenis harta berwujud yang masuk kelompok I berdasarkan
                Peraturan Menteri Keuangan Nomor 96/PMK.03/ Tahun 2009 tentang jenis-jenis harta yang termasuk dalam
                kelompok Harta Berwujud bukan bangunan untuk keperluan penyusutan.
            </p>

            <table class="table table-bordered fs-6">
                <thead>
                <tr>
                    <th>Jenis Harta</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <ol class="lh-xl">
                            <li> Mebel dan peralatan dari kayu atau rotan termasuk meja, bangku, kursi, lemari dan
                                sejenisnya yang bukan bagian dari bangunan.
                            </li>
                            <li>
                                Mesin kantor untuk menunjang pekerjaan seperti mesin tik, mesin hitung, duplikator,
                                mesin fotokopi, mesin akunting atau pembukuan, komputer, printer dan scanner
                            </li>
                            <li>
                                Perlengkapan lain seperti amplifier, tape cassette, video recorder, televisi dan
                                sejenisnya.
                            </li>
                            <li>
                                Sepeda motor, sepeda kayuh dan becak
                            </li>
                            <li>
                                Peralatan khusus untuk menunjang industri atau jasa yang dijalankan pemilik usaha.
                            </li>
                            <li>
                                Peralatan dapur untuk memasak, makan dan minum karyawan.
                            </li>
                            <li>
                                Dies, Jigs dan mould
                            </li>
                            <li>
                                Alat-alat komunikasi seperti telepon, fax, ponsel inventaris kantor dan sejenisnya.
                            </li>
                            <li>
                                Alat yang digerakkan oleh tenaga manusia, bukan oleh tenaga mesin. Contohnya cangkul,
                                garu dan peralatan lainnya yang dijalankan oleh tenaga manusia.
                            </li>
                            <li>
                                Mesin ringan yang dapat dipindahkan seperti hotel, pemecah kulit, penyosoh, pengering,
                                pallet dan sejenisnya.
                            </li>
                            <li>
                                Taksi, bus dan truk yang digunakan sebagai angkutan umum.
                            </li>
                            <li>
                                Flash memory tester, mesin tulis, biporar test system, elimination (PE8-1), pose
                                checker.
                            </li>
                            <li>
                                Anchor, anchor chain, polyester rope, steel buoys, steel wire ropes, morning
                                accessories.
                            </li>
                            <li>
                                Base Station Controller.
                            </li>
                        </ol>
                    </td>
                </tr>
                </tbody>
            </table>


            <h1>
                B. Jenis Harta Berwujud Kelompok II
            </h1>
            <p class="fs-6">
                Berikut ini jenis-jenis harta berwujud yang masuk kelompok II, berdasarkan PMK No. 96 Tahun 2009:
            </p>


            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Jenis Harta</th>
                </tr>
                </thead>
                <tbody class="fw-bold">
                <tr>
                    <td>
                        <ol class="lh-xl">
                            <li>Mebel dan peralatan dari kayu atau rotan termasuk meja, bangku, kursi, lemari dan
                                sejenisnya yang bukan bagian dari bangunan. Alat pendingin udara seperti AC, kipas angin
                                dan sejenisnya
                            </li>
                            <li>
                                Mobil, bus, truk, perahu speed boat dan sejenisnya
                            </li>
                            <li>
                                Container dan sejenisnya
                            </li>
                            <li>
                                Mesin pertanian atau perkebunan seperti traktor dan mesin bajak, penggaruk, penanaman,
                                penebaran benih dan sejenisnya.
                            </li>
                            <li>
                                Mesin yang mengolah atau menghasilkan atau memproduksi bahan atau barang pertanian,
                                perkebunan, peternakan dan perikanan
                            </li>
                            <li>
                                Mesin yang mengolah produk asal hewan, unggas dan perikanan. Contohnya, pabrik susu dan
                                pengalengan ikan.
                            </li>
                            <li>
                                Mesin yang mengolah produk nabati misalnya mesin minyak kelapa, margarin, penggilingan
                                kopi, kembang gula, mesin pengolah biji-bijian seperti penggilingan beras, gandum dan
                                tapioka.
                            </li>
                            <li>
                                Mesin yang menghasilkan atau memproduksi minuman dan bahan-bahan minuman berbagai jenis.
                            </li>
                            <li>
                                Mesin yang menghasilkan atau memproduksi mesin ringan. Contohnya mesin jahit dan pompa
                                air.
                            </li>
                            <li>
                                Mesin dan peralatan penebang kayu
                            </li>
                            <li>
                                Mesin yang mengolah atau menghasilkan atau memproduksi bahan atau barang kehutanan.
                            </li>
                            <li>
                                Peralatan yang dipergunakan seperti truk berat, dump truk, crane buldozer dan
                                sejenisnya.
                            </li>
                            <li>
                                Kapal penumpang, kapal barang, kapal khusus yang dibuat untuk pengangkutan barang
                                tertentu, misalnya gandum, batu-batuan, bijih tambang dan sejenisnya. Termasuk pula
                                kapal pendingin, kapal tangki, kapal penangkap ikan dan sejenisnya yang memiliki berat
                                sampai 100 DWT.Truk kerja untuk pengangkutan dan bongkar muat, truk peron, truk
                                ngangkang dan
                                sejenisnya.
                            </li>
                            <li>
                                Kapal yang dibuat khusus untuk menghela atau mendorong kapal-kapal suar, kapal pemadam
                                kebakaran, kapal keruk, keran terapung dan sejenisnya yang mempunyai berat sampai 100
                                DWT
                            </li>
                            <li>
                                Perahu layar yang menggunakan atau tanpa motor yang mempunya berat sampai 250 DWT
                            </li>
                            <li>
                                Kapal balon.
                            </li>
                            <li>
                                Perangkat pesawat telepon
                            </li>
                            <li>
                                Pesawat telegraf termasuk pesawat pengiriman dan penerimaan radio telegraf dan radio
                                telepon.
                            </li>
                            <li>
                                Auto frame loader, automatic logic handler, baking oven, ball shear tester, bipolar test
                                handler (automatic), cleaning machine, coating machine, curing oven, cutting press,
                                dambar cut machine, dicer, die bonder, die shear test, dynamic burn-in system oven,
                                dynamic test handler, eliminator (PGE-01), full automatic handler, full automatic mark,
                                hand maker, individual mark, inserter remover machine, laser marker (FUM A-01), logic
                                test system, marker (mark), memory test system, molding, mounter, MPS automatic, MPS
                                manual, O/S tester manual, pass oven, pose checker, re-form machine, SMD stocker, taping
                                machine, tie bar cut press, trimming atau forming machine, wire bonder, wire pull
                                tester.
                            </li>
                            <li>
                                Spooling machines, metocean data collector
                            </li>
                            <li>
                                Mobile switching center, home location register, visitor location register,
                                authentication center, equipment identity register, intelligent network service control
                                point, intelligent network service management point, radio base station, transceiver
                                unit, terminal SDH/mini link, antena.
                            </li>
                        </ol>
                    </td>
                </tr>
                </tbody>
            </table>

            <h1>
                C. Jenis Harta Berwujud Kelompok III
            </h1>

            <p class="fs-6">
                Berikut adalah jenis-jenis Harta Berwujud atau jenis harta penyusutan fiskal yang termasuk dalam
                kelompok III:
            </p>


            <table class="table table-bordered fs-6">
                <thead>
                <tr>
                    <th>Jenis Harta</th>
                </tr>
                </thead>
                <tbody class="fw-bold">
                <tr>
                    <td>
                        <ol class="lh-xl">
                            <li>Mesin – mesin yang dipakai dalam bidang pertambangan, termasuk mesin-mesin yang mengolah
                                produk pelikan.
                            </li>
                            <li>
                                Mesin yang mengolah atau menghasilkan produk-produk tekstil. Contohnya kain katun,
                                sutra, serat-serat buatan, wol, bulu hewan, lena rami, permadani, kain-kain bulu dan
                                tule).
                            </li>
                            <li>
                                Mesin preparation, bleaching, dyeing, printing, finishing, texturing, packaging dan
                                sejenisnya.
                            </li>
                            <li>
                                Mesin yang mengolah atau menghasilkan produk-produk kayu, barang-barang dari jerami,
                                rumput dan bahan-bahan anyaman lainnya.
                            </li>
                            <li>
                                Mesin dan peralatan penggergajian kayu
                            </li>
                            <li>
                                Mesin peralatan yang mengolah atau menghasilkan produk industri kimia dan industri
                                yang ada hubungannya dengan industri kimia.
                            </li>
                            <li>
                                Mesin yang mengolah atau menghasilkan produk industri lainnya. Seperti damar tiruan,
                                bahan plastik, ester dan eter dari selulosa, karet sintetis, karet tiruan, kulit samak,
                                jangat dan kulit mentah.
                            </li>
                            <li>
                                Mesin yang menghasilkan atau memproduksi mesin menengah dan berat. Misalnya mesin mobil
                                dan mesin kapal.
                            </li>
                            <li>
                                Kapal penumpang, kapal barang, kapal khusus yang dibuat untuk pengangkutan
                                baran-barang tertentu, kapal pendingin, kapal tangki, kapal penangkap ikan dan
                                sejenisnya yang mempunyai berat di atas 100 DWT sampai seribu DWT
                            </li>
                            <li> Kapal yang dibuat khusus untuk mendorong kapal lain, kapal suar, kapal pemadam
                                kebakaran, kapal keruk, keran terapung dan sejenisnya yang memiliki berat di atas 100
                                DWT sampai seribu DWT
                            </li>
                            <li>
                                Dok terapung
                            </li>
                            <li>
                                Perahu layar pakai atau tanpa motor yang mempunyai berat di atas 250 DWT.
                            </li>
                            <li>
                                Pesawat terbang dan helikopter berbagai jenis
                            </li>
                            <li>Perangkat radio navigasi, radar dan kendali jarak jauh</li>
                        </ol>

                    </td>
                </tr>
                </tbody>
            </table>


            <h1>
                D. Jenis Harta Berwujud Kelompok IV
            </h1>

            <p class="fs-6">
                Berikut ini jenis-jenis Harta Berwujud atau jenis harta penyusutan fiskal yang masuk kelompok IV:
            </p>


            <table class="table table-bordered fs-6">
                <thead>
                <tr>
                    <th>Jenis Harta</th>
                </tr>
                </thead>
                <tbody class="fw-bold">
                <tr>
                    <td>
                        <ol class="lh-xl">
                            <li>Mesin berat untuk konstruksi</li>
                            <li>
                                Lokomotif uang pada tender untuk rel
                            </li>
                            <li>
                                Lokomotif listrik atas rel, dijalankan dengan baterai atau dengan tenaga listrik dari
                                sumber luar.
                            </li>
                            <li> Lokomotif atas rel lainnya.</li>
                            <li>
                                Kereta, gerbong, penumpang dan barang, termasuk kontainer khusus yang dibuat dan
                                dilengkapi untuk ditarik dengan salah satu alat atau beberapa alat pengangkut
                            </li>
                            <li>
                                Kapal penumpang, kapal barang, kapal khusus yang dibuat untuk pengangkutan barang
                                tertentu, yang beratnya di atas seribu DWT.
                            </li>
                            <li>
                                Kapal yang dibuat khusus untuk menghela atau mendorong kapal, kapal suar, kapal pemadam
                                kebakaran, kapal keruk, keran-keran terapung dan sebagainya yang mempunyai berat di atas
                                seribu DWT.
                            </li>
                            <li>
                                G. Dok – dok terapung.
                            </li>
                        </ol>

                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
