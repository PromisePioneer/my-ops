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
        margin: 4cm 0.6cm 2cm;
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
        margin-top: 170px;
        position: relative;
    }


    .d-flex {
        display: flex !important;
    }

    .align-items-start {
        align-items: flex-start !important
    }

    .justify-content-between {
        justify-content: space-between !important
    }

    .text-justify {
        text-align: justify;
        line-height: 1.7;
    }

    .flex-column {
        flex-direction: column !important
    }


    .mt-n3 {
        margin-top: -.75rem !important
    }

    .border {
        border: 1px solid black !important
    }

    .border-bottom {
        border-bottom: 1px solid black;
    }


    .border-top {
        border-top: 1px solid black;
    }

    .border-3 {
        border-width: 3px !important
    }

    .p-1 {
        padding: .25rem !important
    }

    .text-center {
        text-align: center;
    }

    .text-white {
        color: #ffffff !important;
    }

    .fw-bolder {
        font-weight: 700 !important
    }

    .text-uppercase {
        text-transform: uppercase !important
    }

    .mt-10 {
        margin-top: 2.5rem !important
    }

    .mt-1 {
        margin-top: 0.5rem;
    }

    .mb-10 {
        margin-bottom: 2.5rem !important
    }

    .row {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 0;
        display: flex;
        flex-wrap: wrap;
        margin-top: calc(-1 * var(--bs-gutter-y));
        margin-right: calc(-.5 * var(--bs-gutter-x));
        margin-left: calc(-.5 * var(--bs-gutter-x))
    }


    .ms-n2 {
        margin-left: -.5rem !important
    }

    .mb-4 {
        margin-bottom: 1rem !important
    }

    .col-lg-4 {
        flex: 0 0 auto;
        width: 33.33333333%
    }

    .fs-9 {
        font-size: .50rem !important
    }

    .text-danger {
        opacity: 1;
        color: rgba(248, 40, 90, 1) !important
    }

    .col-lg-6 {
        flex: 0 0 auto;
        width: 50%
    }

    .col-lg-12 {
        flex: 0 0 auto;
        width: 100%
    }

    .col-md-5 {
        flex: 0 0 auto;
        width: 41.66666667%
    }

    .mt-2 {
        margin-top: .5rem !important
    }

    .form-check-input[type=checkbox] {
        border-radius: .4em;
        border: 1px solid #000000;
    }

    .fw-bold {
        font-weight: 600 !important
    }

    .ms-1 {
        margin-left: .25rem !important
    }

    .align-items-center {
        align-items: center !important
    }


    .p-1 {
        padding: .25rem !important
    }

    .mb-1 {
        margin-bottom: .25rem !important
    }

    .w-150px {
        width: 140px !important
    }

    .p-4 {
        padding: 1rem !important
    }

    .p-3 {
        padding: .75rem !important
    }

    .p-2 {
        padding: .7rem !important
    }

    .w-200px {
        width: 200px !important
    }

    .me-3 {
        margin-right: .75rem !important
    }

    .me-4 {
        margin-right: 1rem !important
    }

    .w-150px {
        width: 150px !important
    }

    .me-2 {
        margin-right: .5rem !important
    }

    .justify-content-center {
        justify-content: center !important
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch
    }

    .px-10 {
        padding-right: 2.5rem !important;
        padding-left: 2.5rem !important
    }

    .table {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .text-center {
        text-align: center !important
    }


    .ms-5 {
        margin-left: 1.25rem !important
    }

    .text-start {
        text-align: left !important
    }

    .py-1 {
        padding-top: .25rem !important;
        padding-bottom: .25rem !important
    }

    .text-end {
        text-align: right !important
    }


    .px-10 {
        padding-right: 2.5rem !important;
        padding-left: 2.5rem !important
    }


    .ms-2 {
        margin-left: .5rem !important
    }


    .custom-bordered, .custom-bordered th .custom-bordered tr, .custom-bordered td {
        border-left: none;
        border-right: none;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        border-collapse: collapse;

    }

    .mt-5 {
        margin-top: 1.25rem !important
    }

    .mb-5 {
        margin-bottom: 1.25rem !important
    }

    .px-3 {
        padding-right: .75rem !important;
        padding-left: .75rem !important
    }

    .mb-10 {
        margin-bottom: 2.5rem !important
    }

    .px-4 {
        padding-right: 1rem !important;
        padding-left: 1rem !important
    }

    .px-9 {
        padding-right: 3.25rem !important;
        padding-left: 3.25rem !important
    }

    .w-400px {
        width: 400px !important
    }

    .px-1 {
        padding-right: .25rem !important;
        padding-left: .25rem !important
    }

    .ms-4 {
        margin-left: 1rem !important
    }

    .m-0 {
        margin: 0 !important
    }


    .mt-0 {
        margin-top: 0 !important
    }


    .mb-3 {
        margin-bottom: .75rem !important
    }


    .justify-content-around {
        justify-content: space-around !important
    }

    .mb-20 {
        margin-bottom: 5rem !important
    }

    .mb-10 {
        margin-bottom: 2.5rem !important
    }

    .w-1px {
        width: 1px !important
    }

    .margin-after-page-break {
        margin-top: 170px;
    }

    .form-check-input:disabled {
        pointer-events: none;
        filter: none;
        opacity: .5
    }

    .form-check-input[type=checkbox]:indeterminate {
        background-color: #0d6efd;
        border-color: #000000;
        --bs-form-check-bg-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10h8'/%3e%3c/svg%3e")
    }

    .form-check-input:checked[type=checkbox] {
        --bs-form-check-bg-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e")
    }

    .form-check-input:checked {
        background-color: #7dbbf5;
        border-color: #7dbbf5
    }

    .form-check-input:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .25)
    }

    .form-check-reverse .form-check-input {
        float: right;
        margin-right: -1.5em;
        margin-left: 0
    }

    .form-check-input {
        --bs-form-check-bg: var(--bs-body-bg);
        flex-shrink: 0;
        width: 1em;
        height: 1em;
        margin-top: .25em;
        vertical-align: top;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: var(--bs-form-check-bg);
        background-image: var(--bs-form-check-bg-image);
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        border: var(--bs-border-width) solid var(--bs-border-color);
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
        print-color-adjust: exact
    }

    .form-check-input[type=checkbox] {
        border-radius: .25em
    }

    .form-check-input:active {
        filter: brightness(90%)
    }

    .form-check .form-check-input {
        float: left;
        margin-left: -1.5em
    }

    .mt-0 {
        margin-top: 0 !important
    }

    .mt-5 {
        margin-top: 1.25rem !important
    }

    .w20px {
        width: 20px;
    }

    .text-black {
        color: rgba(0, 0, 0, 1) !important
    }

    .text-danger {
        color: var() !important
    }


    .mt-4 {
        margin-top: 1rem !important
    }

    .mt-3 {
        margin-top: .75rem !important
    }

    .mb-3 {
        margin-bottom: .75rem !important;
    }


    .py-10 {
        padding-top: .3rem !important;
        padding-bottom: .3rem !important
    }

    .mb-2 {
        margin-bottom: .5rem !important
    }

    .ms-2 {
        margin-left: .5rem !important
    }

    .ms-3 {
        margin-left: .75rem !important
    }

    .ms-4 {
        margin-left: 1rem !important
    }

    .m-0 {
        margin: 0 !important
    }

    .border-1 {
        border-width: 1px !important
    }

    .p-custom {
        padding: 0.1rem;
    }

    .px-7 {
        padding-right: 1.75rem !important;
        padding-left: 1.75rem !important
    }

    .px-8 {
        padding-right: 2rem !important;
        padding-left: 2rem !important
    }

    .mb-6 {
        margin-bottom: 1.5rem !important
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
    <div class="d-flex align-items-start justify-content-between">
        <div style="line-height: 1">
            <b>PT. MAYATAMA SOLUSINDO</b>
            <p class="text-justify m-0 p-0">
                JL. Sultan Hasanuddin No. 8A
                Kel. Rimba Sekampung
                Kec. Dumai Kota 28822
                -
                Dumai, Riau Indonesia </p>
            +62-853-6579-9998
            https://www.mayatama.id
        </div>
        <div class="d-flex flex-column mt-n3">
            <table>
                <thead>
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td>
                        <div class="border border-1 p-custom">
                            {{ $fab->fab_number }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>
                        <div class="border border-1 border-black p-custom">
                            {{ formatDate($fab->date) }}
                        </div>
                    </td>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="text-center text-white text-black fw-bolder text-uppercase mt-1 mb-2"
         style="background-color: #7dbbf5">
        FORMULIR BERLANGGANAN
    </div>
    <div class="row ms-1 mb-1">
        <div class="col-lg-4">
            <p class="fw-bolder" style="color: #172d69">
                <i class="bi bi-square-fill fs-9 text-danger"></i> Jenis Layanan :
            </p>
        </div>
        <div class="col-lg-6">
            <div class="row ms-2">
                @foreach($serviceCategories as $service)
                    <div class="col-md-5 align-items-center">
                        <input style="
                                  accent-color: #00b0f0 !important;"
                               type="checkbox"
                               class="form-check-input"
                               value="{{ $service->id }}"
                               {{ in_array($service->id, $test) ? 'checked' : '' }}  onclick="return false;"
                        />
                        <label class="fw-bold">
                            <span>{{ $service->name }}</span>
                        </label>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <div class="row ms-1">
        <p class="fw-bolder" style="color: #172d69">
            <i class="bi bi-square-fill fs-9 text-danger"></i> Data Perusahaan :
        </p>
    </div>
    <div class="row ms-4 mb-1 align-items-center">
        <div class="col-lg-4">
            Nama Perusahaan :
        </div>
        <div class="col-lg-6">
            <div class="border border-1 border-black p-1 w-250px">
                {{ $fabCompanyName }}
            </div>
        </div>
    </div>
    <div class="row ms-4  align-self-start">
        <div class="col-lg-4">
            Alamat :
        </div>
        <div class="col-lg-6">
            <div
                class="{{ $fab->po->contact->complete_address ? 'border border-1 mb-1 border-black w-100' : 'border border-1 border-black w-100 p-2 mb-1' }}">
                {{ $fab->po->contact->complete_address ?? '' }}
            </div>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    Telepon
                </div>
                <div class="me-3">
                    <div
                        class="{{ $fab->po->contact->phone_number ? 'border border-1 border-black w-150px p-1 mb-1' : 'border border-1 border-black w-150px p-2 mb-1' }}">
                        {{ $fab->po->contact->phone_number }}
                    </div>
                </div>
                <div class="me-3">
                    Fax
                </div>
                <div class="me-2">
                    <div
                        class="{{ $fab->po->contact->fax ? 'border border-1 border-black w-150px p-1 mb-1' : 'border border-1 border-black w-150px p-2 mb-1' }}">
                        {{ $fab->po->contact->fax }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row ms-4 mb-1 align-items-center">
        <div class="col-lg-4">
            <p>NPWP Perusahaan :</p>
        </div>
        <div class="col-lg-6">
            <div
                class="{{ $fab->po->contact->npwp ? 'border border-1 border-black p-1 w-100' : 'border border-1 border-black w-100 p-2' }}">
                {{ $fab->po->contact->npwp }}
            </div>
        </div>
    </div>
    <div class="row ms-4 mb-1 align-items-center">
        <div class="col-lg-4">
            <p>PIC :</p>
        </div>
        <div class="col-lg-6">
            <div
                class="{{ $fab->po->contact->pic_name ? 'border border-1 border-black w-100 p-1' : 'border border-1 border-black w-100 p-2' }}">
                {{ $fab->po->contact->pic_name }}
            </div>
        </div>
    </div>
    <div class="row ms-4 mb-1 align-items-center">
        <div class="col-lg-4">
            <p>No.KTP/SIM/PASSPORT :</p>
        </div>
        <div class="col-lg-6">
            <div
                class="{{ $fab->po->contact->identity_number ? 'border border-1 border-black w-100 mb-1 p-1' : 'border border-1 border-black w-100 p-2 mb-1' }}">
                {{ $fab->po->contact->identity_number }}
            </div>
        </div>
    </div>
    <div class="row ms-1 mb-2 align-items-center">
        <p class="fw-bolder" style="color: #172d69">
            <i class="bi bi-square-fill fs-9 text-danger"></i>
            Keterangan :
        </p>
    </div>
    <div class="row justify-content-center px-9">
        <table class="table text-center custom-bordered">
            <thead>
            <tr style="background-color: #7dbbf5">
                <th class="text-black w-1px py-1">No</th>
                <th class="text-black w-30px">Deskripsi</th>
                <th class="text-black w-20px">Kapasitas</th>
                <th class="text-black w-20px">Harga</th>
            </tr>
            </thead>
            <tbody>
            @foreach($fabHasServiceCategories as $serviceCategories)
                <tr class="">
                    <td class="text-center p-1">{{ $loop->iteration }}</td>
                    <td class="text-center p-1">{{ $serviceCategories->service->name }}</td>
                    <td class="text-center p-1">{{ $serviceCategories->capacity .' '.  $serviceCategories->unitType->name }}</td>
                    <td class="text-center p-1">{{ number_format($serviceCategories->price) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr class="ms-5 py-1">
                <td colspan="3" class="text-end p-1">PPN</td>
                <td class="py-3">{{ number_format($totalPPN, 2) }}</td>
            </tr>
            <tr class="ms-5">
                <td colspan="3" class="text-end fw-bolder  p-1">Total</td>
                <td>{{ number_format($total, 2) }}</td>
            </tr>
            </tfoot>
        </table>
    </div>
    <ul class="fa-ul px-8">
        <li>
            <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
            SLA 99,5%
        </li>
        <li>
            <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
            Support Pelayanan 7 x 24 jam, online maupun onsite.
        </li>
        <li>
            <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
            Masa berlaku penawaran 1 bulan
        </li>
        <li>
            <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
            Minimum kontrak 1 tahun dan otomatis diperpanjang apabila tidak ada permintaan berhenti
            berlangganan
        </li>

        @if(!empty($fabHasSKL))
            @foreach($fabHasSKL as $skl)
                <li>
                    <i class="fa-li fa fa-check" style="color: #00b0f0"></i>
                    {{ $skl->skl?->name }}
                </li>
            @endforeach
        @endif
    </ul>

    <div class="ms-1 fw-bolder mt-3">
        <p style="color: #172d69" class="m-0 mt-2">
            <i class="bi bi-square-fill fs-9 text-danger"></i> Tata Cara Pembayaran
        </p>
    </div>

    <div>
        <div class="row ms-1 ms-4">
            <p class="fw-bold">Pembayaran dapat dilakukan paling lambat pada tanggal 30 setiap bulannya,
                dengan cara
                transfer
                ke rekening di :</p>
            <table class="ms-3 w-400px">
                <tr>
                    <td class="text-start">Bank</td>
                    <td>:</td>
                    <td>{{ $companyProfile->bank }}</td>
                </tr>
                <tr>
                    <td class="text-start">Nomor Rekening</td>
                    <td>:</td>
                    <td>{{ $companyProfile->bank_account_number }}</td>
                </tr>
                <tr>
                    <td class="text-start">Atas Nama</td>
                    <td>:</td>
                    <td>{{ $companyProfile->name }}</td>
                </tr>
            </table>
        </div>
        <p class="row ms-1 ms-4 mt-0 mb-6">Atau Sesuai yang tercantum dalam Kontrak</p>
        <div class="d-flex justify-content-around align-items-center">
            <div class="text-center">
                <p class="fw-bolder" style="margin-bottom: 60px">{{ $fabCompanyName }}</p>
                <p class="fw-bolder">{{ $fab->po->contact->pic_name }}</p>
                <p class="fw-bolder">{{ $fab->po->contact->pic_position }}</p>
            </div>
            <div class="text-center">
                <p class="fw-bolder" style="margin-bottom: 60px">PT Mayatama Solusindo</p>
                <p class="fw-bolder">{{ $fab->user->name }}</p>
                <p class="fw-bolder">{{ $fab->user->roles[0]?->name ?? '' }}</p>
            </div>
        </div>
    </div>
</main>

</body>
</html>
