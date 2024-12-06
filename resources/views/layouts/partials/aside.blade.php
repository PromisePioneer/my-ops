<div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
     data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <div class="aside-toolbar flex-column-auto" id="kt_aside_toolbar">
        <div class="aside-user d-flex align-items-sm-center justify-content-center py-5">
            @if(isset(Auth::user()->profile_pic) && Auth::user()->profile_pic)
                <div class="symbol symbol-50px">
                    <img src="{{ Storage::url(Auth::user()->profile_pic) }}" alt="">
                </div>
            @else
                <div class="symbol symbol-50px">
                    <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="">
                </div>
            @endif

            <div class="aside-user-info flex-row-fluid flex-wrap ms-5">
                <div class="d-flex">
                    <div class="flex-grow-1 me-2">
                        <a href="#"
                           class="text-white text-hover-primary fs-6 fw-bold">{{ Auth::user()->name }}</a>
                        <div class="d-flex align-items-center text-success fs-9">
                            <span class="bullet bullet-dot bg-success me-1"></span>online
                        </div>
                    </div>
                    <div class="me-n2">
                        <a href="#" class="btn btn-icon btn-sm btn-active-color-primary mt-n2"
                           data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
                           data-kt-menu-overflow="true">
                            <span class="svg-icon svg-icon-muted svg-icon-1">
                                <i class="bi bi-gear-fill"></i>
                            </span>
                        </a>
                        <div
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                            data-kt-menu="true">
                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">

                                    <div class="d-flex flex-column">
                                        <div
                                            class="fw-bolder d-flex align-items-center fs-5">{{ Auth::user()->name }}
                                            <span
                                                class="badge badge-light-primary fw-bolder fs-8 px-2 py-1 ms-2">{{ Auth::user()->roles[0]->name }}</span>
                                        </div>
                                        <a href="#"
                                           class="fw-bold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="{{ url('utility/user-profile/profile-detail') }}"
                                   class="menu-link px-5">
                                    Profil Saya
                                </a>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();"
                                   class="menu-link px-5">Keluar</a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      class="d-none">
                                    @csrf
                                </form>
                            </div>
                            <div class="separator my-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="aside-menu flex-column-fluid">
        <div class="hover-scroll-overlay-y mx-3 my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
             data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="{default: '#kt_aside_toolbar, #kt_aside_footer', lg: '#kt_header, #kt_aside_toolbar, #kt_aside_footer'}"
             data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="5px">
            <div
                class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true">

                <x-menu-sections>Dashboard</x-menu-sections>
                <x-single-menu-item :active="request()->segment(1) === 'home'"
                                    href="{{ url('home') }}">
                    @slot('parentIcon')
                        <i class="ki-duotone ki-element-11 fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    @endslot
                    @slot('menuTitle')
                        Dashboard
                    @endslot
                </x-single-menu-item>
                @canany(['Lihat Menu Supplier', 'Lihat Menu Kategori Barang', 'Lihat Menu Kode Joint Closure','Lihat Menu Cabang', 'Lihat Menu Kontak', 'Lihat Menu SKL', 'Lihat Menu Produk', 'Lihat Menu Kategori Layanan', 'Lihat Menu Departemen', 'Lihat Menu Jabatan', 'Lihat Menu Paket Broadband', 'Lihat Menu Data Perusahaan', 'Lihat Menu Area','Lihat Menu Akun', 'Lihat Menu Saldo Awal', 'Lihat Menu Pengaturan Pajak', 'Lihat Menu Aset'])
                    <x-menu-sections>Master Data</x-menu-sections>
                @endcanany
                @canany(['Lihat Menu Cabang', 'Lihat Menu Kontak', 'Lihat Menu SKL',
                 'Lihat Menu Produk', 'Lihat Menu Kategori Layanan', 'Lihat Menu Departemen',
                 'Lihat Menu Jabatan', 'Lihat Menu Paket Broadband', 'Lihat Menu Data Perusahaan',
                  'Lihat Menu Area', 'Lihat Menu Satuan'
                  ])
                    <x-dropdown-menu :active="request()->segment(1) === 'general-master-data'">
                        @slot('parentIcon')
                            <i class="ki-duotone ki-element-7 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        @endslot
                        @slot('menuTitle')
                            Master Umum
                        @endslot
                        @slot('menuItem')
                            @can('Lihat Menu Cabang')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'branch'"
                                    href="{{ url('general-master-data/branch') }}">
                                    Cabang
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Kontak')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'contact'"
                                    href="{{ url('general-master-data/contact') }}">
                                    Pelanggan
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu SKL')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'skl'"
                                    href="{{ url('general-master-data/skl') }}">
                                    Syarat Ketentuan Layanan
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Satuan')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'unit-types'"
                                    href="{{ url('general-master-data/unit-types/') }}">
                                    Satuan
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Produk')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'product'"
                                    href="{{ url('general-master-data/product') }}">
                                    Produk
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Kategori Layanan')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'service-categories'"
                                    href="{{ url('general-master-data/service-categories') }}">
                                    Kategori Layanan
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Departemen')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'department'"
                                    href="{{ url('general-master-data/department') }}">
                                    Department
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Jabatan')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'roles'"
                                    href="{{ url('general-master-data/roles') }}">
                                    Jabatan
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Paket Broadband')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'broadband-packet'"
                                    href="{{ url('general-master-data/broadband-packet') }}">
                                    Paket Broadband
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Data Perusahaan')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'companies'"
                                    href="{{ url('general-master-data/companies') }}">
                                    Data Perusahaan
                                </x-dropdown-menu-item>
                            @endcan
                            <x-dropdown-menu-item
                                :active="request()->segment(2) === 'area'"
                                href="{{ url('general-master-data/area') }}">
                                Area
                            </x-dropdown-menu-item>
                        @endslot
                    </x-dropdown-menu>
                @endcanany

                @canany('Lihat Menu Akun', 'Lihat Menu Saldo Awal', 'Lihat Menu Pengaturan Pajak', 'Lihat Menu Aset')
                    <x-dropdown-menu :active="request()->segment(1) === 'finances-master-data'">
                        @slot('parentIcon')
                            <i class="ki-duotone ki-element-7 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        @endslot
                        @slot('menuTitle')
                            Master Keuangan
                        @endslot
                        @slot('menuItem')
                            @can('Lihat Menu Akun')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'account'"
                                    href="{{ url('finances-master-data/account') }}">
                                    Daftar Akun
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Saldo Awal')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'initial-balances'"
                                    href="{{ url('finances-master-data/initial-balances') }}">
                                    Saldo Awal
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Pengaturan Pajak')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'tax-settings'"
                                    href="{{ url('finances-master-data/tax-settings') }}">
                                    Pengaturan Pajak
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Aset')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'assets'"
                                    href="{{ url('finances-master-data/assets') }}">
                                    Daftar Aset
                                </x-dropdown-menu-item>
                            @endcan
                        @endslot
                    </x-dropdown-menu>
                @endcanany
                @canany('Lihat Menu Supplier', 'Lihat Menu Kategori Barang', 'Lihat Menu Kode Joint Closure')
                    <x-dropdown-menu :active="request()->segment(1) === 'operational-master-data'">
                        @slot('parentIcon')
                            <i class="ki-duotone ki-element-7 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        @endslot
                        @slot('menuTitle')
                            Master Operasional
                        @endslot
                        @slot('menuItem')
                            @can('Lihat Menu Supplier')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'suppliers'"
                                    href="{{ url('operational-master-data/suppliers') }}">
                                    Supplier
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Kategori Barang')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'inventory-categories'"
                                    href="{{ url('operational-master-data/inventory-categories') }}">
                                    Kategori Barang
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Kode Joint Closure')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'joint-closures-code'"
                                    href="{{ url('operational-master-data/joint-closures-code') }}">
                                    Kode Joint Closure
                                </x-dropdown-menu-item>
                            @endcan
                        @endslot
                    </x-dropdown-menu>
                @endcanany
                @canany('Lihat Menu BoQ')
                    <x-menu-sections>Inventory</x-menu-sections>
                    <x-dropdown-menu :active="request()->is('inventory/*')">
                        @slot('parentIcon')
                            <i class="ki-duotone ki-dollar fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        @endslot
                        @slot('menuTitle')
                            Inventory Controller
                        @endslot
                        @slot('menuItem')
                            @can('Lihat Menu BoQ')
                                <x-dropdown-menu-item
                                    :active="request()->is('inventory/boq*')"
                                    href="{{ url('inventory/boq') }}">
                                    Bill Of Quantity
                                </x-dropdown-menu-item>
                            @endcan
                        @endslot
                    </x-dropdown-menu>
                @endcanany
                {{--                <x-dropdown-menu :active="request()->is('operational/*')">--}}
                {{--                    @slot('parentIcon')--}}
                {{--                        <i class="ki-duotone ki-dollar fs-2">--}}
                {{--                            <span class="path1"></span>--}}
                {{--                            <span class="path2"></span>--}}
                {{--                            <span class="path3"></span>--}}
                {{--                        </i>--}}
                {{--                    @endslot--}}
                {{--                    @slot('menuTitle')--}}
                {{--                        Data Aset Lapangan--}}
                {{--                    @endslot--}}
                {{--                    @slot('menuItem')--}}
                {{--                        <x-dropdown-menu-item--}}
                {{--                            :active="request()->is('operational/odp*')"--}}
                {{--                            href="{{ url('operational/odp') }}">--}}
                {{--                            ODP & Homepass--}}
                {{--                        </x-dropdown-menu-item>--}}
                {{--                        <x-dropdown-menu-item--}}
                {{--                            :active="request()->is('operational/fo-cables*')"--}}
                {{--                            href="{{ url('operational/fo-cables') }}">--}}
                {{--                            Kabel FO--}}
                {{--                        </x-dropdown-menu-item>--}}
                {{--                        <x-dropdown-menu-item--}}
                {{--                            :active="request()->is('operational/poles*')"--}}
                {{--                            href="{{ url('operational/poles') }}">--}}
                {{--                            Tiang--}}
                {{--                        </x-dropdown-menu-item>--}}
                {{--                        <x-dropdown-menu-item--}}
                {{--                            :active="request()->segment(2) === 'joint-closures'"--}}
                {{--                            href="{{ url('/operational/joint-closures') }}">--}}
                {{--                            Joint Closure--}}
                {{--                        </x-dropdown-menu-item>--}}
                {{--                        <x-dropdown-menu-item--}}
                {{--                            :active="request()->segment(2) === 'invoice'"--}}
                {{--                            href="{{ url('/income-transactions/invoice') }}">--}}
                {{--                            Aset Lapangan--}}
                {{--                        </x-dropdown-menu-item>--}}
                {{--                        <x-dropdown-menu-item--}}
                {{--                            :active="request()->segment(2) === 'invoice'"--}}
                {{--                            href="{{ url('/income-transactions/invoice') }}">--}}
                {{--                            Data Core--}}
                {{--                        </x-dropdown-menu-item>--}}

                {{--                        <x-dropdown-menu-item--}}
                {{--                            :active="request()->segment(2) === 'invoice'"--}}
                {{--                            href="{{ url('/income-transactions/invoice') }}">--}}
                {{--                            Coverage Area--}}
                {{--                        </x-dropdown-menu-item>--}}
                {{--                    @endslot--}}
                {{--                </x-dropdown-menu>--}}


                @canany('Lihat Menu Jurnal Umum', 'Lihat Menu Buku Besar', 'Lihat Menu Neraca Saldo')
                    <x-menu-sections>Jurnal</x-menu-sections>
                @endcanany
                @canany('Lihat Menu Jurnal Umum', 'Lihat Menu Buku Besar', 'Lihat Menu Neraca Saldo')
                    <x-dropdown-menu :active="request()->segment(1) === 'journals'">
                        @slot('parentIcon')
                            <i class="ki-duotone ki-book-square fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        @endslot
                        @slot('menuTitle')
                            Penjurnalan
                        @endslot
                        @slot('menuItem')
                            @can('Lihat Menu Jurnal Umum')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'general-journal'"
                                    href="{{ url('/journals/general-journal/') }}">
                                    Jurnal Umum
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Neraca Saldo')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'trial-balance'"
                                    href="{{ url('/journals/trial-balance/') }}">
                                    Neraca Saldo
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Buku Besar')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'general-ledger'"
                                    href="{{ url('/journals/general-ledger/') }}">
                                    Buku Besar
                                </x-dropdown-menu-item>
                            @endcan
                            {{--                        <x-dropdown-menu-item--}}
                            {{--                            :active="request()->segment(2) === 'financial-report'"--}}
                            {{--                            href="{{ url('/journals/financial-report/') }}">--}}
                            {{--                            Laporan Keuangan--}}
                            {{--                        </x-dropdown-menu-item>--}}
                            {{--                        <x-dropdown-menu-item--}}
                            {{--                            :active="request()->segment(2) === 'income-statement'"--}}
                            {{--                            href="{{ url('/journals/income-statement/') }}">--}}
                            {{--                            Laba Rugi--}}
                            {{--                        </x-dropdown-menu-item>--}}
                            {{--                        <x-dropdown-menu-item--}}
                            {{--                            :active="request()->segment(2) === 'cashflow-statement'"--}}
                            {{--                            href="{{ url('/journals/cashflow-statement/') }}">--}}
                            {{--                            Laporan Arus Kas--}}
                            {{--                        </x-dropdown-menu-item>--}}
                        @endslot
                    </x-dropdown-menu>
                @endcanany
                @canany('Lihat Menu Penawaran', 'Lihat Menu PO', 'Lihat Menu BAA', 'Lihat Menu Fab', 'Lihat Menu BAA', 'Lihat Menu Bast', 'Lihat Menu Invoice', 'Lihat Menu Pengeluaran', 'Lihat Menu Invoice Pengeluaran')
                    <x-menu-sections>Transaksi</x-menu-sections>
                @endcanany
                @canany('Lihat Menu Penawaran', 'Lihat Menu PO', 'Lihat Menu BAA', 'Lihat Menu Fab', 'Lihat Menu BAA', 'Lihat Menu Bast', 'Lihat Menu Invoice')
                    <x-dropdown-menu :active="request()->segment(1) === 'income-transactions'">
                        @slot('parentIcon')
                            <i class="ki-duotone ki-dollar fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        @endslot
                        @slot('menuTitle')
                            Pendapatan
                        @endslot
                        @slot('menuItem')
                            @can('Lihat Menu Penawaran')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'offering-letters'"
                                    href="{{ url('/income-transactions/offering-letters') }}">
                                    Penawaran
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu PO')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'po'"
                                    href="{{ url('/income-transactions/po') }}">
                                    Purchase Order
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Fab')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'fab'"
                                    href="{{ url('/income-transactions/fab') }}">
                                    FAB
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu BAA')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'baa'"
                                    href="{{ url('/income-transactions/baa') }}">
                                    BAA
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Bast')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'bast'"
                                    href="{{ url('/income-transactions/bast') }}">
                                    BAST
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Invoice')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'invoice'"
                                    href="{{ url('/income-transactions/invoice') }}">
                                    Invoice
                                </x-dropdown-menu-item>
                            @endcan
                        @endslot
                    </x-dropdown-menu>
                @endcanany
                @canany('Lihat Menu Pengeluaran', 'Lihat Menu Invoice Pengeluaran')
                    <x-dropdown-menu :active="request()->segment(1) === 'expenditure-transactions'">
                        @slot('parentIcon')
                            <i class="ki-duotone ki-save-deposit fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        @endslot
                        @slot('menuTitle')
                            Pengeluaran
                        @endslot
                        @slot('menuItem')
                            @can('Lihat Menu Pengeluaran')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'expenditure'"
                                    href="{{ url('/expenditure-transactions/expenditure') }}">
                                    Pengeluaran
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Invoice Pengeluaran')
                                <x-dropdown-menu-item
                                    href="#">
                                    Invoice
                                </x-dropdown-menu-item>
                            @endcan
                        @endslot
                    </x-dropdown-menu>
                @endcanany

                {{--                <x-menu-sections>Inventaris</x-menu-sections>--}}

                {{--                <x-dropdown-menu :active="request()->segment(1) === 'inventory'">--}}
                {{--                    @slot('parentIcon')--}}
                {{--                        <i class="ki-duotone ki-basket-ok fs-2">--}}
                {{--                            <span class="path1"></span>--}}
                {{--                            <span class="path2"></span>--}}
                {{--                            <span class="path3"></span>--}}
                {{--                            <span class="path4"></span>--}}
                {{--                        </i>--}}
                {{--                    @endslot--}}
                {{--                    @slot('menuTitle')--}}
                {{--                        Inventaris--}}
                {{--                    @endslot--}}
                {{--                    @slot('menuItem')--}}
                {{--                        <x-dropdown-menu-item--}}
                {{--                                :active="request()->segment(2) === 'goods'"--}}
                {{--                                href="{{ url('inventory/goods') }}">--}}
                {{--                            Barang--}}
                {{--                        </x-dropdown-menu-item>--}}
                {{--                        <x-dropdown-menu-item--}}
                {{--                                :active="request()->segment(2) === 'unit-types'"--}}
                {{--                                href="{{ url('inventory/unit-types') }}">--}}
                {{--                            Satuan--}}
                {{--                        </x-dropdown-menu-item>--}}
                {{--                    @endslot--}}
                {{--                </x-dropdown-menu>--}}

                {{--                <x-menu-sections>Penyesuaian Jurnal</x-menu-sections>--}}

                {{--                <x-dropdown-menu :active="request()->segment(1) === 'journal-adjustment'">--}}
                {{--                    @slot('parentIcon')--}}
                {{--                        <i class="bi bi-tag-fill fs-1"></i>--}}
                {{--                    @endslot--}}
                {{--                    @slot('menuTitle')--}}
                {{--                        Penyesuaian Jurnal--}}
                {{--                    @endslot--}}
                {{--                    @slot('menuItem')--}}
                {{--                        <x-dropdown-menu-item--}}
                {{--                            :active="request()->segment(2) === 'initial-journal'"--}}
                {{--                            href="{{ url('journal-adjustment/initial-journal') }}">--}}
                {{--                            Jurnal Awal--}}
                {{--                        </x-dropdown-menu-item>--}}
                {{--                        <x-dropdown-menu-item--}}
                {{--                            :active="request()->segment(2) === 'adjustment'"--}}
                {{--                            href="{{ url('journal-adjustment/adjustment') }}">--}}
                {{--                            Penyesuaian--}}
                {{--                        </x-dropdown-menu-item>--}}
                {{--                    @endslot--}}
                {{--                </x-dropdown-menu>--}}


                @canany('Lihat Menu Profil Perusahaan')
                    <x-menu-sections>Utilitas</x-menu-sections>
                @endcanany

                @canany('Lihat Menu Profil Perusahaan')
                    <x-dropdown-menu :active="request()->segment(1) === 'utility'">
                        @slot('parentIcon')
                            <i class="ki-duotone ki-abstract-29 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        @endslot
                        @slot('menuTitle')
                            Utilitas
                        @endslot
                        @slot('menuItem')
                            @can('Lihat Menu Profil Perusahaan')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'company-profile'"
                                    href="{{ url('utility/company-profile') }}">
                                    Profil Perusahaan
                                </x-dropdown-menu-item>
                            @endcan
                            {{--                        <x-dropdown-menu-item--}}
                            {{--                            :active="request()->segment(2) === 'letter-head'"--}}
                            {{--                            href="{{ url('utility/letter-head') }}">--}}
                            {{--                            Kop Surat--}}
                            {{--                        </x-dropdown-menu-item>--}}
                        @endslot
                    </x-dropdown-menu>
                @endcanany
                @canany('Lihat Menu Payroll')
                    <x-menu-sections>Manajemen Karyawan</x-menu-sections>
                @endcanany
                @can('Lihat Menu Payroll')
                    <x-dropdown-menu :active="request()->is('payroll/*')">
                        @slot('parentIcon')
                            <i class="ki-duotone ki-profile-user fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        @endslot
                        @slot('menuTitle')
                            Payroll
                        @endslot
                        @slot('menuItem')
                            <x-dropdown-menu-item
                                :active="request()->is('payroll/setting*')"
                                href="{{ url('payroll/setting') }}">
                                Pengaturan
                            </x-dropdown-menu-item>
                            {{--                        <x-dropdown-menu-item--}}
                            {{--                            :active="request()->segment(2) === 'generate'"--}}
                            {{--                            href="{{ url('payroll/generate') }}">--}}
                            {{--                            Generate Payroll--}}
                            {{--                        </x-dropdown-menu-item>--}}
                        @endslot
                    </x-dropdown-menu>
                @endcan
                @canany(['Lihat Menu Data Karyawan', 'Lihat Menu Manajemen Cuti', 'Lihat Menu Permission', 'Lihat Menu Manajemen Cuti', 'Lihat Menu Surat Peringatan', 'Lihat Menu Kontrak Karyawan', 'Lihat Menu SK'])
                    <x-dropdown-menu :active="request()->segment(1) === 'manage-users'">
                        @slot('parentIcon')
                            <i class="ki-duotone ki-profile-user fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        @endslot
                        @slot('menuTitle')
                            Manajemen Karyawan
                        @endslot
                        @slot('menuItem')
                            @can('Lihat Menu Data Karyawan')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'users'"
                                    href="{{ url('manage-users/users') }}">
                                    Data Karyawan
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Permission')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'permissions'"
                                    href="{{ url('manage-users/permissions') }}">
                                    Hak Akses
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Manajemen Cuti')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'leaves'"
                                    href="{{ url('manage-users/leaves') }}">
                                    Manajemen Cuti
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Surat Peringatan')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'sp'"
                                    href="{{ url('manage-users/sp') }}">
                                    Surat Peringatan
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu Kontrak Karyawan')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'contract-management'"
                                    href="{{ url('manage-users/contract-management') }}">
                                    Kontrak Karyawan
                                </x-dropdown-menu-item>
                            @endcan
                            @can('Lihat Menu SK')
                                <x-dropdown-menu-item
                                    :active="request()->segment(2) === 'sk'"
                                    href="{{ url('manage-users/sk') }}">
                                    SK Karyawan
                                </x-dropdown-menu-item>
                            @endcan
                        @endslot
                    </x-dropdown-menu>
                @endcanany
                <x-dropdown-menu :active="request()->segment(1) === 'adms'">
                    @slot('parentIcon')
                        <i class="bi bi-app-indicator"></i>
                    @endslot
                    @slot('menuTitle')
                        Data Absensi
                    @endslot
                    @slot('menuItem')
                        @can('Lihat Menu Hari Libur Nasional')
                            <x-dropdown-menu-item
                                :active="request()->segment(2) === 'national-holiday'"
                                href="{{ url('adms/national-holiday') }}">
                                Libur Nasional
                            </x-dropdown-menu-item>
                        @endcan
                        @can('Lihat Menu Mesin Absen')
                            <x-dropdown-menu-item
                                :active="request()->segment(2) === 'fp-devices'"
                                href="{{ url('adms/fp-devices') }}">
                                Mesin Absen
                            </x-dropdown-menu-item>
                        @endcan
                        @can('Lihat Menu Pengaturan Jam Kerja')
                            <x-dropdown-menu-item
                                :active="request()->segment(2) === 'work-time'"
                                href="{{ url('adms/work-time') }}">
                                Pengaturan Jam Kerja
                            </x-dropdown-menu-item>
                        @endcan
                        <x-dropdown-menu-item
                            :active="request()->segment(2) === 'employee-schedules'"
                            href="{{ url('adms/employee-schedules') }}">
                            Pengaturan Jadwal Libur
                        </x-dropdown-menu-item>
                        @can('Lihat Menu Riwayat Absensi')
                            <x-dropdown-menu-item
                                :active="request()->segment(2) === 'attendances-summary'"
                                href="{{ url('adms/attendances-summary') }}">
                                Riwayat Absensi
                            </x-dropdown-menu-item>
                        @endcan
                        @endslot
                </x-dropdown-menu>
            </div>
        </div>
    </div>
</div>
