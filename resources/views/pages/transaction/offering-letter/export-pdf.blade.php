<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Penawaran #{{ $offeringLetter->offering_number }}</title>

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
            margin: 4cm 1cm 2cm;
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
            height: 5cm;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5cm;
        }

        .wrapper {
            margin-top: 70px;
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .row {
            display: flex;
            align-items: center;
        }


        .attachment {
            float: left;
            width: 27%;
            padding-right: 30px;
        }

        .foreword {
            position: relative;
            z-index: 9999;
        }

        .text-hover-primary {
            transition: color .2s ease
        }

        .fw-bolder {
            font-weight: bolder;
        }

        .fw-bold {
            font-weight: 700
        }

        .text-gray-800 {
            color: #252F4A;
        }

        .separator {
            display: block;
            height: 0;
            border-bottom: 1px solid #7dbbf5
        }

        .mb-20 {
            margin-bottom: 5.4rem !important
        }


        .mb-1 {
            margin-bottom: 0.25rem;
        }

        .mb-3 {
            margin-bottom: 1rem
        }

        .fs-6 {
            font-size: 0.8rem
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .text-justify {
            text-align: justify;
            line-height: 1.7;
        }

        .clearfix {
            content: "";
            clear: both;
        }

        .content {
            float: right;
            width: 70%;
        }


        .table {
            width: 100%;
            margin-bottom: 1rem;
            vertical-align: top;
        }

        .border-bottom {
            border-bottom: 1px solid #F1F1F4;
        }

        .mb-11 {
            margin-bottom: 1rem
        }

        .border-black {
            opacity: 1;
            border-color: rgba(0, 0, 0, 1);
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .py-10 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .test {
            margin: 0;
            width: 100%;
            height: 88px;
            box-sizing: border-box;
        }

        .mb-10 {
            margin-bottom: 2.5rem
        }


        .sincerely {
            padding-left: 30px;
        }

        .content-height {
            margin: 0;
            width: 100%;
            height: 89px;
            box-sizing: border-box;
        }
    </style>


</head>

<body>

<header>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-header.png'))) }}"
        width="100%" height="100%"/>
</header>

<div class="wrapper">
    <div class="container">
        <div class="attachment">
            <div class="foreword text-hover-primary fw-bolder fs-6">
                Yth, Bapak/Ibu {{ $offeringLetter->contact->pic_name }},
                <br>
                <span>{{ $offeringLetterCompanyName }}</span>
            </div>

            <div class="test">
                <div class="fs-6 mb-3">
                    {{ $offeringLetter->contact?->complete_address ?? 'Ditempat' }}
                </div>
            </div>
            <div class="separator mb-1" style="border-bottom-color: #7dbbf5"></div>
            <div class="mb-1">
                <div class="fs-6 d-flex align-items-center">
                    {{ \App\Helper\formatDate($offeringLetter->date) }}
                </div>
            </div>
            <div class="separator mb-3" style="border-bottom-color: #7dbbf5"></div>

            <div class="mb-6">
                <div class="fw-bold fs-6">Nomor:</div>
                <div class="fs-6">
                    001/SPH/MYT-SM5/X/2024
                </div>
            </div>

            <div class="mb-6">
                <div class="fw-bold fs-6">Perihal:</div>
                <div class="fs-6">{{ $offeringLetter->regarding }}</div>
            </div>
        </div>
        <div class="content">
            <div class="content-height">
                <p class="text-justify fs-6">
                    Dengan hormat,
                    <br>
                    Kami dari PT. Mayatama
                    Solusindo bermaksud menawarkan harga layanan dedicated
                    untuk {{ $offeringLetterCompanyName }}, berikut adalah harga
                    terbaik
                    yang kami
                    tawarkan:
                </p>
            </div>


            <br>
            <br>
            <br>
            <br>

            <table class="table">
                <thead>
                <tr class="border-bottom border-black fw-bold">
                    <th class="text-center fs-6">No</th>
                    <th class="text-center fs-6">Layanan</th>
                    <th class="text-center fs-6">Kapasitas / Jumlah</th>
                    <th class="text-center fs-6">Harga / Bulan</th>
                </tr>
                </thead>
                <tbody class="border-bottom border-black">
                @foreach($offeringLetterServices as $service)
                    <tr class="fs-5 text-end border-bottom border-black py-10">
                        <td class="text-center py-10 fs-6">{{ $loop->iteration }}</td>
                        <td class="text-center py-10 fs-6">
                            {{ $service->serviceCategory->name }}
                        </td>
                        <td class="text-center fs-6">{{ $service->capacity }} {{ $service->unitType->name }}</td>
                        <td class="text-center fs-6">
                            {{ number_format($service->price, false, '.', '.') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr class="border-bottom border-black p-1">
                    <td colspan="3" class="text-end fw-bold fs-6">
                        PPN
                    </td>
                    <td class="text-center fs-6"> {{ number_format($totalPPN, false,'.', '.') }}</td>
                </tr>
                <tr class="p-1">
                    <td colspan="3" class="text-end fw-bold fs-6">
                        Total
                    </td>
                    <td class="text-center fs-6">{{ number_format($total, false,'.', '.') }}</td>
                </tr>
                </tfoot>
            </table>


            <p class="mb-1 fs-6">
                Adapun syarat dan ketentuan layanan yang kami berikan antara lain :
            </p>


            <ul class="mb-10 fs-6">
                <li>SLA 99,5%</li>
                <li>Support Pelayanan 7 x 24 jam, online maupun onsite.</li>
                <li>Masa berlaku penawaran 1 bulan</li>
                <li>
                    Minimum kontrak 1 tahun dan otomatis diperpanjang apabila tidak ada
                    permintaan
                    berhenti berlangganan
                </li>
                @foreach($offeringLetterServiceDescription as $desc)
                    <li>{{ $desc->skl->name }}</li>
                @endforeach
            </ul>

            <p class="sincerely fs-6 fw-bold">Hormat Kami</p>
            <br>
            <br>
            <br>
            <p class="sincerely fw-bold fs-6">{{ $offeringLetter->user->roles[0]?->name ?? '' }}</p>
            <p class="sincerely fw-bold fs-6">{{ $offeringLetter->user->name }}</p>

        </div>

        <div class="clearfix"></div>
    </div>
</div>


<footer>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"
        width="100%" height="100%"/>
</footer>
</body>
</html>
