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


    @page {
        margin: 0 0;
    }


    body {
        margin: 4cm 0.5cm 2cm;
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
        margin-top: 180px;
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
        border: 2px solid #000000;
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
        margin-bottom: 1rem;
        vertical-align: top;
        border: 1px solid black;
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


    .custom-bordered, .custom-bordered th, .custom-bordered td {
        border: 1px solid black;
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
        padding-right: 2.25rem !important;
        padding-left: 2.25rem !important
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
    <div style="page-break-after: always">
        <div class="d-flex align-items-start justify-content-between">
            <div>
                <p class="text-justify">
                    <b>PT. MAYATAMA SOLUSINDO</b>
                    <br>
                    JL. Sultan Hasanuddin No. 8A Kel. Rimba Sekampung Kec. Dumai Kota 28822 - Dumai, Riau
                    Indonesia <br>
                    Mobile: +62-853-6579-9998 <br>
                    www.mayatama.id
                </p>
            </div>
            <div class="d-flex flex-column mt-n3">
                <table>
                    <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>:</th>
                        <th>
                            <div class="border border-3 p-1">
                                {{ $fab->fab_number }}
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <th>:</th>
                        <th>
                            <div class="border border-3 border-black p-1">
                                {{ \App\Helper\formatDate($fab->date) }}
                            </div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="text-center text-white fw-bolder text-uppercase mt-5 mb-5"
             style="background-color: #00b0f0">
            FORMULIR BERLANGGANAN
        </div>
        <div class="row ms-n2 mb-4">
            <div class="col-lg-4">
                <p class="fw-bolder" style="color: #172d69">
                    <i class="bi bi-square-fill fs-9 text-danger"></i> Layanan :
                </p>
            </div>
            <div class="col-lg-6">
                <div class="row ms-2">
                    @foreach($serviceCategories as $service)
                        <div class="col-md-5 mt-2">
                            <input style="
                                   accent-color: #00b0f0 !important;"
                                   type="checkbox"
                                   class="form-check-input"
                                   value="{{ $service->id }}"
                                   {{ in_array($service->id, $test) ? 'checked' : '' }}  onclick="return false;"/>
                            <label class="fw-bold">
                                <span>{{ $service->name }}</span>
                            </label>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="row ms-n2">
            <p class="fw-bolder" style="color: #172d69">
                <i class="bi bi-square-fill fs-9 text-danger"></i> Data Perusahaan :
            </p>
        </div>
        <div class="row ms-1 mb-1">
            <div class="col-lg-4">
                Nama Perusahaan :
            </div>
            <div class="col-lg-6">
                <div class="border border-3 border-black p-1 w-250px">
                    {{ $fabCompanyName }}
                </div>
            </div>
        </div>
        <div class="row ms-1 mb-1">
            <div class="col-lg-4 align-items-center">
                Alamat :
            </div>
            <div class="col-lg-6">
                <div
                    class="{{ $fab->contact->complete_address ? 'border border-3 border-black w-100 mb-1' : 'border border-3 border-black w-100 p-2 mb-1' }}">
                    {{ $fab->contact->complete_address ?? '' }}
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        Phone
                    </div>
                    <div class="me-3">
                        <div
                            class="{{ $fab->contact->phone_number ? 'border border-3 border-black w-150px p-1 mb-1' : 'border border-3 border-black mt-1 w-150px p-2 mb-1' }}">
                            {{ $fab->contact->phone_number }}
                        </div>
                    </div>
                    <div class="me-3">
                        Fax
                    </div>
                    <div class="me-2">
                        <div
                            class="{{ $fab->contact->fax ? 'border border-3 border-black w-150px p-1 mb-1' : 'border border-3 border-black mt-1 w-150px p-2 mb-1' }}">
                            {{ $fab->contact->fax }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ms-1 mb-1">
            <div class="col-lg-4">
                <p>NPWP Perusahaan :</p>
            </div>
            <div class="col-lg-6">
                <div
                    class="{{ $fab->contact->npwp ? 'border border-3 border-black w-100 mb-1' : 'border border-3 border-black  w-100 p-2 mb-1' }}">
                    {{ $fab->contact->npwp }}
                </div>
            </div>
        </div>
        <div class="row ms-1 mb-1">
            <div class="col-lg-4">
                <p>No.KTP/SIM/PASSPORT :</p>
            </div>
            <div class="col-lg-6">
                <div
                    class="{{ $fab->contact->identity_number ? 'border border-3 border-black w-100 mb-1 p-1' : 'border border-3 border-black  w-100 p-2 mb-1' }}">
                    {{ $fab->contact->identity_number }}
                </div>
            </div>
        </div>
        <div class="row ms-n2 mt-10 mb-4">
            <p class="fw-bolder" style="color: #172d69">
                <i class="bi bi-square-fill fs-9 text-danger"></i>
                Keterangan :
            </p>
        </div>
        <div class="row justify-content-center px-9">
            <table class="table text-center custom-bordered">
                <thead>
                <tr class="border border-black ms-5" style="background-color: #00b0f0">
                    <th class="text-white w-20px py-1">No</th>
                    <th class="text-white w-30px">Deskripsi</th>
                    <th class="text-white w-30px">Kapasitas</th>
                    <th class="text-white w-30px">Harga</th>
                </tr>
                </thead>
                <tbody>
                @foreach($fabHasServiceCategories as $serviceCategories)
                    <tr class="border border-black">
                        <td class="p-3">{{ $loop->iteration }}</td>
                        <td class="text-start p-3">{{ $serviceCategories->service->name }}</td>
                        <td class="p-3">{{ $serviceCategories->capacity .' '.  $serviceCategories->unitType->name }}</td>
                        <td class="p-3">{{ number_format($serviceCategories->price) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr class="border border-black ms-5 py-1">
                    <td colspan="3" class="text-end fw-bolder p-1">PPN</td>
                    <td class="py-3">{{ number_format($totalPPN, 2) }}</td>
                </tr>
                <tr class="border border-black ms-5">
                    <td colspan="3" class="text-end fw-bolder  p-1">Total</td>
                    <td>{{ number_format($total, 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
        <ul class="fa-ul px-4 mb-10">
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

        <div class="row ms-n2 fw-bolder">
            <p style="color: #172d69" class="m-0">
                <i class="bi bi-square-fill fs-9 text-danger"></i> Tata Cara Pembayaran
            </p>
        </div>
    </div>

    <div style="page-break-after: never; ">
        <div style="margin-top: 170px" class="row ms-1 ms-4">
            <p class="fw-bold mb-3">Pembayaran dapat dilakukan paling lambat pada tanggal 30 setiap bulannya,
                dengan cara
                transfer
                ke rekening di :</p>
            <table class="ms-3 mb-3 w-400px">
                <tr>
                    <td class="px-1 text-start">Bank</td>
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
        <p class="row ms-1 ms-4 mt-0 mb-10">Atau Sesuai yang tercantum dalam Kontrak</p>
        <div class="d-flex justify-content-around">
            <div class="text-center">
                <p class="fw-bolder" style="margin-bottom: 100px">{{ $fabCompanyName }}</p>
                <p class="fw-bolder">{{ $fab->contact->pic_name }}</p>
            </div>
            <div class="text-center">
                <p class="fw-bolder" style="margin-bottom: 100px">PT Mayatama Solusindo</p>
                <p class="fw-bolder">{{ $fab->user->name }}</p>
                <p class="fw-bolder">{{ $fab->user->roles[0]?->name ?? '' }}</p>
            </div>
        </div>
    </div>
</main>

</body>
</html>
