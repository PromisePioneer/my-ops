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
        margin: 4cm 4cm 2cm;
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
        margin-top: 140px;
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

    .fs-7 {
        font-size: .95rem !important
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

    .separator {
        display: block;
        height: 0;
        width: auto;
        border-bottom: 1px solid #F1F1F4;
    }

    .border-1 {
        border-width: 1px !important
    }

    .mx-6 {
        margin-right: 5rem !important;
        margin-left: 5rem !important
    }

    .ms-10 {
        margin-left: 2.5rem !important
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
    <div class="text-center mb-2">
        <h2>KONTRAK BERLANGGANAN <i>(SUBSCRIPTION CONTRACT)</i></h2>
        <h2>PT. MAYATAMA SOLUSINDO</h2>
        <h2>&</h2>
        <h2>{{ $fab->contact->company_name }}</h2>
    </div>
    <div class="separator border border-1 border-black text-center mx-6"></div>
    <div class="text-center">
        <h2>No : {{ $fab->contract_number }}</h2>
    </div>
    <div class="text-center mb-4">
        <h2>FAB No : {{ $fab->fab_number }}</h2>
    </div>


    <p class="text-justify mb-2">
        Pada hari ini, {{ formatDate($fab->date) }}, yang bertandatangan
        dibawah ini :
    </p>

    <div class="ms-2 mb-4">
        <table>
            <tr>
                <td class="text-start">Nama</td>
                <td>:</td>
                <td>{{ $fab->fabPic->name }}</td>
            </tr>
            <tr>
                <td class="text-start">Jabatan</td>
                <td>:</td>
                <td>{{ $fab->fabPic?->roles[0]?->name ?? '' }}</td>
            </tr>
            <tr>
                <td class="text-start">Alamat</td>
                <td>:</td>
                <td>Jl. Sultan Hasanuddin No. 8A Dumai – Riau.</td>
            </tr>
        </table>

    </div>
    <p class="mb-2"> Dalam hal ini bertindak untuk dan atas nama <b>PT. MAYATAMA SOLUSINDO</b></p>


    <div class="ms-2 mb-2">
        <table>
            <tr>
                <td class="text-start">Nama</td>
                <td>:</td>
                <td>{{ $fab->contact->name }}</td>
            </tr>
            <tr>
                <td class="text-start">Alamat</td>
                <td>:</td>
                <td>{{ $fab->contact?->company_address ?? '-' }}</td>
            </tr>
        </table>
    </div>


    <p>Bertindak untuk dan atas nama {{ $fab->contact->company_name }} yang selanjutnya disebut <b>PELANGGAN</b>.</p>
    <br>
    <p>
        <b> PT. MAYATAMA SOLUSINDO</b>,
        penyelenggara jasa layanan internet dengan <b>Surat Izin Penyelenggaraan Jasa Akses
            Internet (ISP)</b> dari <b>Menteri Komunikasi dan Informatika Nomor : 591 Tahun 2017</b>
        yang berkedudukan di
        Jl. Sultan Hasanuddin No. 8A, Kel. Rimba Sekampung, Kec. Dumai Kota, Kota Dumai, Riau 28822
        dalam hal ini diwakili oleh {{ $fab->picName->roles[0]?->name ?? '' }}, yang selanjutnya dalam kontrak ini
        disebut <b>MYFIBER</b>.
    </p>
    <br>
    <p class="text-justify mb-4">
        Dengan memperhatikan permohonan berlangganan dari <b>PELANGGAN</b>, kedua belah pihak sepakat untuk mengikatkan
        diri
        dalam kontrak berlangganan pelayanan jasa <b>MYFIBER</b> sebagai operator/ISP/ Dedicated User dengan syarat
        sebagaimana
        tercantum dalam pasal-pasal sebagai berikut :
    </p>


    <div class="text-center mb-2">
        <h3><u>PASAL 1</u></h3>
        <h3>FASILITAS MYFIBER</h3>
    </div>

    <ol class="m-0 mb-2" style="margin: 0; padding: 0; line-height: 2">
        <li>
            <b>MYFIBER</b> sepakat untuk penyediaan internet sebagaimana tercantum dalam formulir berlangganan
            untuk dapat dipergunakan oleh <b>PELANGGAN</b>.
        </li>
        <li>Pelayanan jasa <b>MYFIBER</b> ini dapat dipergunakan oleh <b>PELANGGAN</b>
            selama 24 jam/hari (7 hari/minggu).
        </li>
        <li>
            Penempatan perangkat berikut segala fasilitas yang dibutuhkan untuk jalannya layanan
            <b>MYFIBER</b> menjadi tanggungjawab <b>MYFIBER</b>. <b>MYFIBER</b> bertanggung jawab atas keamanan
            perangkat tersebut dan <b>PELANGGAN</b> dilarang untuk memindahkan, mengalihkan, atau memperbaikinya tanpa
            izin tertulis dari <b>MYFIBER</b>.
        </li>
        <li>
            Terminal milik <b>PELANGGAN</b> dan perangkat antarmuka yang dihubungkan dengan perangkat/saluran
            <b>MYFIBER</b>harus mendapat persetujuan terlebih dahulu dari <b>MYFIBER</b>.
        </li>
        <li>
            Penyambungan layanan <b>MYFIBER</b> akan dilaksanakan sesuai permintaan <b>PELANGGAN</b> sebagaimana
            tercantum dalam formulir aplikasi berlangganan dan sesudah <b>PELANGGAN</b> menandatangani kontrak tersebut.
            Aktivasi dilakukan paling lambat 30 hari kerja sejak formulir berlangganan ditandatangani oleh
            <b>PELANGGAN</b> dan
            setelah <b>PELANGGAN</b> melakukan pembayaran biaya pemasangan 1 minggu setelah pengisian formulir aplikasi.
        </li>
    </ol>


    @pageBreak
    <div class="text-center mb-2" style="margin-top: 140px">
        <h3><u>PASAL 2</u></h3>
        <h3>AKTIVASI</h3>
    </div>

    <p class="text-justify mb-2">
        Kontrak berlangganan dan Penagihan berlaku sejak tanggal penandatanganan Berita Acara Serah Terima oleh kedua
        belah pihak yang menunjukkan bahwa pelayanan jasa <b>MYFIBER</b> telah layak dioperasikan oleh <b>PELANGGAN</b>.
    </p>

    <div class="text-center mb-2">
        <h3><u>PASAL 3</u></h3>
        <h3>JANGKA WAKTU BERLANGGANAN</h3>
    </div>

    <p class="text-justify mb-2">
        Kontrak ini berlaku terhitung sejak tanggal 02 Januari 2024 sampai 01 Januari 2025 untuk sewa cloud server (CPU
        4 Ghz, RAM 16GB, SSD1500GB, IP, NIC, OS, Linux,Firewall).Besarnya tarif pertahun adalah Rp. 6.000.000,- (belum
        termasuk PPN 11%).
    </p>

    <div class="text-center mb-2">
        <h3><u>PASAL 4</u></h3>
        <h3>PEMBAYARAN</h3>
    </div>

    <p class="mb-2">
        <b>PELANGGAN</b> wajib membayar biaya langganan tahunan setelah tanggal Berita Serah Terima Acara paling lama 1
        (satu)
        bulan setelah Berita Acara Aktivasi dan Kontrak berlangganan ditandatangani. <b>MYFIBER</b> akan menerima
        pembayaran
        atas jasa MYFIBER secara penuh dan <b>PELANGGAN</b> bertanggung jawab atas seluruh biaya yang timbul akibat
        transaksi
        pembayaran tagihan.
    </p>


    <div class="text-center mb-2">
        <h3><u>PASAL 5</u></h3>
        <h3>HAK DAN KEWAJIBAN</h3>
    </div>

    <ol class="m-0 mb-2" style="margin: 0; padding: 0; line-height: 2">
        <li>
            <b>PELANGGAN</b> wajib menyediakan dan melengkapi perangkat yang dibutuhkannya, sehingga pada saat aktivasi
            telah
            siap dan layak dioperasikan.
            <b>PELANGGAN</b> tidak diperkenankan memberi kesempatan kepada pihak ketiga untuk memanfaatkan fasilitas dan
            pelayanan <b>MYFIBER</b> tanpa ijin tertulis dari <b>MYFIBER</b>.
        </li>
        <li>
            <b>PELANGGAN</b> tidak diperkenankan mengadakan perubahan konfigurasi
            dan spesifikasi teknik peralatan atau
            menghubungkan dengan cara lain dalam bentuk apapun dengan jaringan <b>MYFIBER</b>, kecuali atas ijin
            tertulis
            dari <b>MYFIBER</b>.
        </li>
        <li>
            <b>PELANGGAN</b> tidak diperkenankan untuk menghubungkan jaringan <b>MYFIBER</b> dengan jaringan
            telekomunikasi
            umum (jaringan telepon, teleks, data).
        </li>
        <li>
            <b>PELANGGAN</b> mengizinkan <b>MYFIBER</b> atau wakilnya untuk setiap saat memasuki ruangan peralatan
            <b>PELANGGAN</b>
            guna
            keperluan pemeliharaan dan perbaikan, sesuai dengan peraturan yang berlaku.
        </li>
        <li>
            <b>MYFIBER</b> bertanggung jawab atas pemeliharaan dan perbaikan dari kerusakan atau gangguan pada saluran
            dan
            fasilitas <b>MYFIBER</b> paling lambat 1 x 24 jam sejak diterimanya pengaduan dari <b>PELANGGAN</b> (untuk
            wilayah
            Dumai)
            dan 2 x 24 jam (untuk wilayah diluar Dumai), kecuali <i>force majeure</i>. Apabila kerusakan atau gangguan
            tersebut
            disebabkan oleh kesalahan, kesengajaan atau kelalaian <b>PELANGGAN</b> maka <b>MYFIBER</b> berhak memungut
            biaya
            perbaikan.
        </li>
        <li>
            Apabila terjadi kerusakan atau gangguan yang bukan karena kesalahan <b>PELANGGAN</b> serta fasilitas <b>MYFIBER</b>
            tidak
            dapat dipergunakan dan juga bukan akibat force majeure selama lebih dari 1 jam terus menerus, maka <b>PELANGGAN</b>
            berhak menerima restitusi sesuai ketentuan point 8.
        </li>
        <li>
            <b>MYFIBER</b> tidak bertanggung jawab atas informasi-informasi yang disalurkan melalui jasa <b>MYFIBER</b>,
            termasuk
            kebenaran, kerahasiaan dan atau kualitas informasi tersebut.
        </li>
        <li>
            <b>MYFIBER</b> tidak bertanggung jawab atas kerugian-kerugian <b>PELANGGAN</b> atau pihak ketiga yang timbul
            berkaitan
            dengan penggunaan jasa <b>MYFIBER</b>.
        </li>
    </ol>

    @pageBreak

    <div class="text-center mb-2" style="margin-top: 140px">
        <h3><u>PASAL 6</u></h3>
        <h3>PEMBATALAN</h3>
    </div>


    <p class="text-justify mb-2">
        Apabila <b>PELANGGAN</b> membatalkan berlangganan jasa <b>MYFIBER</b> sebelum tanggal aktivasi dikenakan denda
        sebesar Rp. 1.500.000,-.
    </p>


    <div class="text-center mb-2">
        <h3><u>PASAL 7</u></h3>
        <h3>PERPINDAHAN DAN PENGALIHAN</h3>
    </div>


    <ol class="m-0 mb-2" style="margin: 0; padding: 0; line-height: 2">
        <li>
            <b>PELANGGAN</b> dapat meminta perpindahan perangkat <b>MYFIBER</b> serta perubahan kecepatan sepanjang
            teknis
            memungkinkan. Segala biaya yang timbul akibat perpindahan lokasi serta perubahan kecepatan tersebut menjadi
            tanggung jawab <b>PELANGGAN</b>. Permintaan pemindahan fasilitas jasa <b>MYFIBER</b> yang telah dipasang ke
            lokasi
            baru
            diperlakukan sebagai sambungan baru. Biaya berlangganan akan disesuaikan dengan perubahan kecepatan.
        </li>
        <li>
            Berdasarkan permintaan <b>PELANGGAN</b> secara tertulis, <b>MYFIBER</b> dapat menyetujui pengalihan hak dan
            kewajiban berlangganan fasilitas dan pelayanan jasa <b>MYFIBER</b> dari <b>PELANGGAN</b> kepada pihak
            ketiga, sepanjang tidak terdapat perubahan teknis dan pihak ketiga yang ditunjuk memenuhi ketentuan dan
            syarat syarat sesuai dengan peraturan yang berlaku. Segala biaya yang timbul akibat pengalihan hak ini
            menjadi tanggung jawab <b>PELANGGAN</b>.
        </li>
    </ol>

    <div class="text-center mb-2">
        <h3><u>PASAL 8</u></h3>
        <h3>FORCE MAJURE</h3>
    </div>


    <ol class="m-0 mb-2" style="margin: 0; padding: 0; line-height: 2">
        <li>
            Force Majeure adalah kejadian–kejadian diluar kekuasaan para pihak yang mengakibatkan terhentinya atau
            tertundanya pelaksanaan kontrak, seperti : petir, gempa bumi, taufan, kebakaran, ledakan, banjir, sabotase,
            kerusuhan, dan huru – hara, peraturan, dan atau larangan pemerintah yang tidak dapat dituntut.
        </li>
        <li>
            Setiap kejadian yang bersifat Force Majeure, harus diberitahukan kepada pihak lainnya, paling lambat 7
            (Tujuh) hari setelah kejadian tersebut berakhir.
        </li>
    </ol>


    <div class="text-center mb-4">
        <h3><u>PASAL 9</u></h3>
        <h3>HUKUM YANG BERLAKU</h3>
    </div>

    <ol class="m-0 mb-4" style="margin: 0; padding: 0; line-height: 2">
        <li>
            Kontrak ini tunduk kepada peraturan serta kebijaksanaan pemerintah lainnya mengenai Telekomunikasi yang
            berlaku di Indonesia.
        </li>
        <li>
            Pelaksanaan dan penafsiran ketentuan perjanjian ini tunduk pada ketentuan Hukum Perdata Indonesia.
        </li>
    </ol>


    <div class="text-center mb-2">
        <h3><u>PASAL 10</u></h3>
        <h3>PEMBERHENTIAN BERLANGGANAN</h3>
    </div>


    <ol class="m-0" style="margin: 0; padding: 0; line-height: 2">
        <li>
            <b>MYFIBER</b> akan menyampaikan pemberitahuan kepada <b>PELANGGAN</b> apabila terdapat perubahan atas
            Kontrak Berlangganan Jasa <b>MYFIBER</b> ini.
        </li>
        <li>
            Kontrak berlangganan ini dibuat rangkap 2 (dua), masing-masing sama bunyinya serta mempunyai kekuatan hukum
            yang sama setelah ditandatangani oleh kedua pihak.
        </li>
    </ol>


    <p class="text-justify mb-4">
        Demikian <b>KONTRAK BERLANGGANAN</b> ini dibuat dan ditanda-tangani oleh wakil-wakil masing-masing pihak pada
        hari dan tanggal sebagaimana tersebut pada awal <b>KONTRAK BERLANGGANAN</b> ini.
    </p>

    @pageBreak

    <div style="margin-top: 140px">
        <div class="d-flex justify-content-around align-items-center">
            <div class="text-center">
                <p class="fw-bolder" style="margin-bottom: 60px">{{ $fab->contact->company_name }}</p>
                <p class="fw-bolder">{{ $fab->contact->pic_name }}</p>
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
