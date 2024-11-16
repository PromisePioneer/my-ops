<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Penawaran #{{ $offeringLetter->offering_number }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
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

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1 * var(--bs-gutter-y));
            margin-right: calc(-.5 * var(--bs-gutter-x));
            margin-left: calc(-.5 * var(--bs-gutter-x))
        }

        .col-md-3 {
            flex: 0 0 auto;
            width: 25%
        }

        .ms-4 {
            margin-left: 1rem !important
        }

        .mb-4 {
            margin-bottom: 1rem !important
        }

        .fw-bolder {
            font-weight: 700 !important
        }

        .text-hover-primary {
            transition: color .2s ease
        }

        .text-wrap {
            white-space: normal !important
        }

        .fs-6 {
            font-size: 1rem !important
        }


        .fs-9 {
            font-size: .75rem !important
        }


        .fs-10 {
            font-size: .5rem !important
        }

        .col-md-8 {
            flex: 0 0 auto;
            width: 66.66666667%
        }

        .d-flex {
            display: flex !important
        }

        .align-items-center {
            align-items: center !important
        }

        .separator {
            display: block;
            height: 0;
            border-bottom: 1px solid var(--bs-border-color)
        }

        .ms-10 {
            margin-left: 2.5rem !important
        }


        .mb-3 {
            margin-bottom: .75rem !important
        }

        .mb-6 {
            margin-bottom: 1.5rem !important
        }

        .mb-2 {
            margin-bottom: .5rem !important
        }

        .fw-bold {
            font-weight: 600 !important
        }

        .fs-7 {
            font-size: .95rem !important
        }

        .table {
            --bs-table-color-type: initial;
            --bs-table-bg-type: initial;
            --bs-table-color-state: initial;
            --bs-table-bg-state: initial;
            --bs-table-color: var(--bs-body-color);
            --bs-table-bg: transparent;
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-accent-bg: transparent;
            --bs-table-striped-color: var(--bs-body-color);
            --bs-table-striped-bg: rgba(var(--bs-gray-100-rgb), 0.75);
            --bs-table-active-color: var(--bs-body-color);
            --bs-table-active-bg: var(--bs-gray-100);
            --bs-table-hover-color: var(--bs-body-color);
            --bs-table-hover-bg: var(--bs-gray-100);
            width: 100%;
            margin-bottom: 1rem;
            vertical-align: top;
            border-color: var(--bs-table-border-color)
        }

        .border-bottom {
            border-bottom: 1px solid #F1F1F4 !important;
        }

        .border-top {
            border-top: 1px solid #F1F1F4 !important;
        }

        .border-black {
            --bs-border-opacity: 1;
            border-color: rgba(0, 0, 0, 1) !important
        }

        html {
            -webkit-print-color-adjust: exact;
        }

        .custom-bordered, .custom-bordered th, .custom-bordered td {
            border-bottom: 1px solid black;
            border-top: 1px solid black;
            border-spacing: 0;
            border-collapse: collapse;
        }


        .text-center {
            text-align: center !important
        }


        .table {
            --bs-table-color-type: initial;
            --bs-table-bg-type: initial;
            --bs-table-color-state: initial;
            --bs-table-bg-state: initial;
            --bs-table-color: var(--bs-body-color);
            --bs-table-bg: transparent;
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-accent-bg: transparent;
            --bs-table-striped-color: var(--bs-body-color);
            --bs-table-striped-bg: rgba(var(--bs-gray-100-rgb), 0.75);
            --bs-table-active-color: var(--bs-body-color);
            --bs-table-active-bg: var(--bs-gray-100);
            --bs-table-hover-color: var(--bs-body-color);
            --bs-table-hover-bg: var(--bs-gray-100);
            width: 100%;
            margin-bottom: 1rem;
            vertical-align: top;
            border-color: var(--bs-table-border-color)
        }


        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        .fs-5 {
            font-size: 1.15rem !important
        }

        .p-1 {
            padding: .25rem !important
        }

        .text-end {
            text-align: right !important
        }

        .text-gray-800 {
            color: #252F4A !important
        }

        .py-1 {
            padding-top: .25rem !important;
            padding-bottom: .25rem !important
        }

        .mb-1 {
            margin-bottom: .25rem !important
        }

        .p-2 {
            padding: .5rem !important
        }

        .mb-20 {
            margin-bottom: 5rem !important
        }

        .mb-15 {
            margin-bottom: 2.75rem !important
        }

        .py-3 {
            padding-top: .75rem !important;
            padding-bottom: .75rem !important
        }

        .py-2 {
            padding-top: .5rem !important;
            padding-bottom: .5rem !important
        }

        .d-none {
            display: none;
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
    <div class="row">
        <div class="col-md-3 ms-4">
            <p class="mb-4 fw-bolder text-hover-primary text-wrap fs-9">
                Yth, Bapak/Ibu {{ $offeringLetter->contact->pic_name }},<br>
                <span>{{ $offeringLetterCompanyName }}</span>
            </p>

            <p class="mb-4 text-hover-primary text-wrap fs-9">
                {{ $offeringLetter->contact?->complete_address ?? 'Ditempat' }}
            </p>
        </div>
        <div class="col-md-8 ms-10 fs-9">
            <p class=" d-flex align-items-center" style="line-height: 2.0">
                Dengan hormat,
                <br>
                Kami dari PT. Mayatama
                Solusindo bermaksud menawarkan harga layanan dedicated
                untuk {{ $offeringLetterCompanyName }}, berikut adalah harga
                terbaik
                yang kami
                tawarkan :
            </p>
        </div>

        <div class="row fs-9 ">
            <div class="col-md-3 ms-4">
                <div class="separator mb-1"
                     style="border: 1px solid #7dbbf5;"></div>

                <div class="mb-1">
                    <div class="d-flex align-items-center mb-1 fs-9">
                        {{ \App\Helper\formatDate($offeringLetter->date) }}
                    </div>
                </div>

                <div class="separator mb-6" style="border: 1px solid #7dbbf5;"></div>

                <div class="mb-6 fs-9">
                    <div class="fw-bold fs-9">Nomor:</div>
                    <div class="fs-9">
                        {{ $offeringLetter->offering_number }}
                    </div>
                </div>
                <div class="mb-6 fs-9">
                    <div class="fw-bold">Perihal:</div>
                    <div>{{ $offeringLetter->regarding }}</div>
                </div>
            </div>
            <div class="col-md-8 ms-10 fs-9">
                <table class="table">
                    <thead>
                    <tr class="fw-bold custom-bordered"
                        style="background-color: #7dbbf5; border-top: 1px solid black; border-bottom: 1px solid black">
                        <th class="text-center py-2"
                            style="background-color: #7dbbf5; border-top: 1px solid black; border-bottom: 1px solid black">
                            No
                        </th>
                        <th class="text-center">Layanan</th>
                        <th class="text-center">Kapasitas / Jumlah</th>
                        <th class="text-center">Harga / Bulan</th>
                    </tr>
                    </thead>
                    <tbody class="border-bottom border-black">
                    @foreach($offeringLetterProducts as $product)
                        <tr class="text-end border-bottom border-black">
                            <td class="text-center py-2">{{ $loop->iteration }}</td>
                            <td class="text-center py-2">
                                {{ $product->serviceCategory->name }}
                            </td>
                            <td class="text-center py-2"> {{ $product->capacity }} {{ $product->unitType->name }}</td>
                            <td class="text-center py-2">
                                {{ number_format($product->price, false, '.', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="border-bottom border-black">
                    <tr class="border-bottom border-black p-2">
                        <td colspan="3" class="text-end text-gray-800 py-2">
                            PPN
                        </td>
                        <td class="text-center fw-bold text-gray-800 py-2">  {{ number_format($totalPPN, false,'.', '.') }}</td>
                    </tr>
                    <tr class="p-1 border-bottom border-black">
                        <td colspan="3" class="text-end fw-bold text-gray-800 py-2">
                            Total
                        </td>
                        <td class="text-center fw-bold text-gray-800 py-2">{{ number_format($total, false,'.', '.') }}</td>
                    </tr>
                    </tfoot>
                </table>

                <p class="fs-6 d-flex align-items-center mb-3 mt-10 fs-9">
                    Adapun syarat dan ketentuan layanan yang kami berikan antara lain :
                </p>
                <ul class="fa-ul px-3 mb-10 fs-6 mb-15 fs-9">
                    <li><i class="fa-li fa fa-check" style="color: #00b0f0"></i> SLA 99,5%</li>
                    <li><i class="fa-li fa fa-check" style="color: #00b0f0"></i> Support Pelayanan 7 x
                        24 jam, online maupun onsite.
                    </li>
                    <li><i class="fa-li fa fa-check" style="color: #00b0f0"></i>Masa berlaku penawaran 1
                        bulan
                    </li>
                    <li>
                        <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
                        Minimum kontrak 1 tahun dan otomatis diperpanjang apabila tidak ada
                        permintaan
                        berhenti berlangganan
                    </li>
                    @foreach($offeringLetterServiceDescription as $desc)
                        <li
                            class="{{ empty($desc->skl?->name) ? 'd-none' : 'fs-6' }}"><i
                                class="fa-li fa fa-check"
                                style="color: #00b0f0"></i> {{ $desc?->skl?->name }}</li>
                    @endforeach
                </ul>

                <div class="ms-4 mb-4 flex-column">
                    <div class="fw-bold fs-9 mb-20">
                        PT. Mayatama Solusindo
                    </div>
                    <div class="fs-9 fw-bold">
                        {{ $offeringLetter->user->roles[0]?->name ?? '' }}
                    </div>
                    <div class="fs-9 fw-bold">
                        {{ $offeringLetter->user->name }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8"></div>
        </div>
    </div>
</div>


<footer>
    <img
        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/kop-footer.png'))) }}"
        width="100%" height="100%"/>
</footer>
</body>
</html>
