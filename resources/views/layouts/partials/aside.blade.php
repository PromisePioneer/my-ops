@php use function App\Helper\menus; @endphp
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

                @foreach(menus() as $menu)
                    @if($menu->parent_id === null && !$menu->children()->exists())
                        <x-single-menu-item
                            :active="Request::segment(1) === $menu->link"
                            href="{{ url($menu->link) }}">
                            @slot('parentIcon')
                                {!! $menu->icon !!}
                            @endslot
                            @slot('menuTitle')
                                {{ $menu->name }}
                            @endslot
                        </x-single-menu-item>
                    @endif


                    @if($menu->parent_id === null && $menu->children()->exists())
                        @php
//                            dd($menu->children()->pluck('link')->toArray());
                        @endphp
                        <x-dropdown-menu :active="in_array(request()->path(), $menu->children()->pluck('link')->toArray() )">

                            @slot('parentIcon')
                                <i class="ki-duotone ki-element-7 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            @endslot
                            @slot('menuTitle')
                                {{ $menu->name }}
                            @endslot
                            @slot('menuItem')
                                @foreach($menu->children as $childMenu)
                                    <x-dropdown-menu-item
                                        :active="$childMenu->link == request()->path()"
                                        href="{{  url($childMenu->link) }}">
                                        {{ $childMenu->name }}
                                    </x-dropdown-menu-item>
                                @endforeach
                            @endslot
                        </x-dropdown-menu>

                    @endif
                @endforeach

                {{--                <x-menu-sections>Dashboard</x-menu-sections>--}}
                {{--                <x-single-menu-item :active="request()->segment(1) === 'home'"--}}
                {{--                                    href="{{ url('home') }}">--}}
                {{--                    @slot('parentIcon')--}}
                {{--                    @endslot--}}
                {{--                    @slot('menuTitle')--}}
                {{--                        Dashboard--}}
                {{--                    @endslot--}}
                {{--                </x-single-menu-item>--}}
                {{--                @canany(['Lihat Menu Supplier', 'Lihat Menu Kategori Barang', 'Lihat Menu Kode Joint Closure','Lihat Menu Cabang', 'Lihat Menu Kontak', 'Lihat Menu SKL',  'Lihat Menu Kategori Layanan', 'Lihat Menu Departemen', 'Lihat Menu Jabatan', 'Lihat Menu Paket Broadband', 'Lihat Menu Data Perusahaan', 'Lihat Menu Area','Lihat Menu Akun', 'Lihat Menu Saldo Awal', 'Lihat Menu Pengaturan Pajak', 'Lihat Menu Aset', 'Lihat Menu Jam Kerja'])--}}
                {{--                    <x-menu-sections>Master Data</x-menu-sections>--}}
                {{--                @endcanany--}}
                {{--                @canany(['Lihat Menu Cabang', 'Lihat Menu Kontak', 'Lihat Menu SKL',--}}
                {{--                  'Lihat Menu Kategori Layanan', 'Lihat Menu Departemen',--}}
                {{--                 'Lihat Menu Jabatan', 'Lihat Menu Paket Broadband', 'Lihat Menu Data Perusahaan',--}}
                {{--                  'Lihat Menu Area', 'Lihat Menu Satuan'])--}}
                {{--                                    <x-dropdown-menu :active="request()->segment(2) === 'common'">--}}
                {{--                                        @slot('parentIcon')--}}
                {{--                                            <i class="ki-duotone ki-element-7 fs-2">--}}
                {{--                                                <span class="path1"></span>--}}
                {{--                                                <span class="path2"></span>--}}
                {{--                                            </i>--}}
                {{--                                        @endslot--}}
                {{--                                        @slot('menuTitle')--}}
                {{--                                            Master Umum--}}
                {{--                                        @endslot--}}
                {{--                                        @slot('menuItem')--}}
                {{--                                            @can('Lihat Menu Cabang')--}}
                {{--                                                <x-dropdown-menu-item--}}
                {{--                                                    :active="request()->segment(3) === 'branch'"--}}
                {{--                                                    href="{{ url('master/common/branch') }}">--}}
                {{--                                                    Cabang--}}
                {{--                                                </x-dropdown-menu-item>--}}
                {{--                                            @endcan--}}
                {{--                                            @can('Lihat Menu Kontak')--}}
                {{--                                                <x-dropdown-menu-item--}}
                {{--                                                    :active="request()->segment(3) === 'contact'"--}}
                {{--                                                    href="{{ url('master/common/contact') }}">--}}
                {{--                                                    Kontak--}}
                {{--                                                </x-dropdown-menu-item>--}}
                {{--                                            @endcan--}}
                {{--                                            @can('Lihat Menu SKL')--}}
                {{--                                                <x-dropdown-menu-item--}}
                {{--                                                    :active="request()->segment(3) === 'skl'"--}}
                {{--                                                    href="{{ url('master/common/skl') }}">--}}
                {{--                                                    Syarat Ketentuan Layanan--}}
                {{--                                                </x-dropdown-menu-item>--}}
                {{--                                            @endcan--}}
                {{--                                            @can('Lihat Menu Satuan')--}}
                {{--                                                <x-dropdown-menu-item--}}
                {{--                                                    :active="request()->segment(3) === 'unit-types'"--}}
                {{--                                                    href="{{ url('master/common/unit-types/') }}">--}}
                {{--                                                    Satuan--}}
                {{--                                                </x-dropdown-menu-item>--}}
                {{--                                            @endcan--}}
                {{--                                            @can('Lihat Menu Kategori Layanan')--}}
                {{--                                                <x-dropdown-menu-item--}}
                {{--                                                    :active="request()->segment(3) === 'service-categories'"--}}
                {{--                                                    href="{{ url('master/common/service-categories') }}">--}}
                {{--                                                    Kategori Layanan--}}
                {{--                                                </x-dropdown-menu-item>--}}
                {{--                                            @endcan--}}
                {{--                                            @can('Lihat Menu Departemen')--}}
                {{--                                                <x-dropdown-menu-item--}}
                {{--                                                    :active="request()->segment(3) === 'department'"--}}
                {{--                                                    href="{{ url('master/common/department') }}">--}}
                {{--                                                    Department--}}
                {{--                                                </x-dropdown-menu-item>--}}
                {{--                                            @endcan--}}
                {{--                                            @can('Lihat Menu Jabatan')--}}
                {{--                                                <x-dropdown-menu-item--}}
                {{--                                                    :active="request()->segment(3) === 'roles'"--}}
                {{--                                                    href="{{ url('master/common/roles') }}">--}}
                {{--                                                    Jabatan--}}
                {{--                                                </x-dropdown-menu-item>--}}
                {{--                                            @endcan--}}
                {{--                                            @can('Lihat Menu Paket Broadband')--}}
                {{--                                                <x-dropdown-menu-item--}}
                {{--                                                    :active="request()->segment(3) === 'broadband-packets'"--}}
                {{--                                                    href="{{ url('master/common/broadband-packets') }}">--}}
                {{--                                                    Paket Broadband--}}
                {{--                                                </x-dropdown-menu-item>--}}
                {{--                                            @endcan--}}
                {{--                                            @can('Lihat Menu Data Perusahaan')--}}
                {{--                                                <x-dropdown-menu-item--}}
                {{--                                                    :active="request()->segment(3) === 'companies'"--}}
                {{--                                                    href="{{ url('master/common/companies') }}">--}}
                {{--                                                    Data Perusahaan--}}
                {{--                                                </x-dropdown-menu-item>--}}
                {{--                                            @endcan--}}
                {{--                                            @can('Lihat Menu Area')--}}
                {{--                                                <x-dropdown-menu-item--}}
                {{--                                                    :active="request()->segment(3) === 'area'"--}}
                {{--                                                    href="{{ url('master/common/area') }}">--}}
                {{--                                                    Area--}}
                {{--                                                </x-dropdown-menu-item>--}}
                {{--                                            @endcan--}}
                {{--                                        @endslot--}}
                {{--                                    </x-dropdown-menu>--}}
                {{--                                @endcanany--}}

                {{--                @canany(['Lihat Menu Akun', 'Lihat Menu Saldo Awal', 'Lihat Menu Saldo Awal Persediaan', 'Lihat Menu Pengaturan Pajak', 'Lihat Menu Aset'])--}}
                {{--                    <x-dropdown-menu :active="request()->segment(2) === 'accounting'">--}}
                {{--                        @slot('parentIcon')--}}
                {{--                            <i class="ki-duotone ki-element-7 fs-2">--}}
                {{--                                <span class="path1"></span>--}}
                {{--                                <span class="path2"></span>--}}
                {{--                            </i>--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuTitle')--}}
                {{--                            Master Keuangan--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuItem')--}}
                {{--                            @can('Lihat Menu Kategori Akun')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(3) === 'account-categories'"--}}
                {{--                                    href="{{ url('master/accounting/account-categories') }}">--}}
                {{--                                    Kategori Akun--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Akun')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(3) === 'accounts'"--}}
                {{--                                    href="{{ url('master/accounting/accounts') }}">--}}
                {{--                                    Daftar Akun--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Saldo Awal')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(3) === 'initial-balances'"--}}
                {{--                                    href="{{ url('master/accounting/initial-balances') }}">--}}
                {{--                                    Saldo Awal--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Saldo Awal Persediaan')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(3) === 'initial-inventory-balances'"--}}
                {{--                                    href="{{ url('master/accounting/initial-inventory-balances') }}">--}}
                {{--                                    Saldo Awal Persediaan--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Pengaturan Pajak')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(3) === 'tax-settings'"--}}
                {{--                                    href="{{ url('master/accounting/tax-settings') }}">--}}
                {{--                                    Pengaturan Pajak--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Aset')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(3) === 'assets'"--}}
                {{--                                    href="{{ url('master/accounting/assets') }}">--}}
                {{--                                    Daftar Aset--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                        @endslot--}}
                {{--                    </x-dropdown-menu>--}}
                {{--                @endcanany--}}
                {{--                @canany(['Lihat Menu Supplier', 'Lihat Menu Kategori Barang', 'Lihat Menu PSB', 'Lihat Menu Jam Kerja'])--}}
                {{--                    <x-dropdown-menu :active="request()->segment(2) === 'operational'">--}}
                {{--                        @slot('parentIcon')--}}
                {{--                            <i class="ki-duotone ki-element-7 fs-2">--}}
                {{--                                <span class="path1"></span>--}}
                {{--                                <span class="path2"></span>--}}
                {{--                            </i>--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuTitle')--}}
                {{--                            Master Operasional--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuItem')--}}
                {{--                            @can('Lihat Menu Kategori Barang')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(3) === 'item-categories'"--}}
                {{--                                    href="{{ url('master/operational/item-categories') }}">--}}
                {{--                                    Kategori Barang--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Daftar Barang')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(3) === 'items'"--}}
                {{--                                    href="{{ url('/master/operational/items') }}">--}}
                {{--                                    Daftar Barang--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Jam Kerja')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(3) === 'work-time'"--}}
                {{--                                    href="{{ url('master/operational/work-time') }}">--}}
                {{--                                    Jam Kerja--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                        @endslot--}}
                {{--                    </x-dropdown-menu>--}}
                {{--                @endcanany--}}
                {{--                @canany(['Lihat Menu Stok Barang'])--}}
                {{--                    <x-menu-sections>Inventory</x-menu-sections>--}}
                {{--                    <x-dropdown-menu :active="request()->is('inventory/*')">--}}
                {{--                        @slot('parentIcon')--}}
                {{--                            <i class="ki-duotone ki-dollar fs-2">--}}
                {{--                                <span class="path1"></span>--}}
                {{--                                <span class="path2"></span>--}}
                {{--                                <span class="path3"></span>--}}
                {{--                            </i>--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuTitle')--}}
                {{--                            Inventory Controller--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuItem')--}}
                {{--                            <x-dropdown-menu-item--}}
                {{--                                :active="request()->is('inventory/stocks')"--}}
                {{--                                href="{{ url('inventory/stocks') }}">--}}
                {{--                                Stok Barang--}}
                {{--                            </x-dropdown-menu-item>--}}
                {{--                            <x-dropdown-menu-item--}}
                {{--                                :active="request()->is('inventory/draft-stocks*')"--}}
                {{--                                href="{{ url('inventory/draft-stocks') }}">--}}
                {{--                                Pengkodean Barang--}}
                {{--                            </x-dropdown-menu-item>--}}
                {{--                            <x-dropdown-menu-item--}}
                {{--                                :active="request()->is('inventory/stock-withdrawals*')"--}}
                {{--                                href="{{ url('inventory/stock-withdrawals') }}">--}}
                {{--                                Pemakaian Barang--}}
                {{--                            </x-dropdown-menu-item>--}}
                {{--                            <x-dropdown-menu-item--}}
                {{--                                :active="request()->is('inventory/stock-mutations*')"--}}
                {{--                                href="{{ url('inventory/stock-mutations') }}">--}}
                {{--                                Mutasi Barang--}}
                {{--                            </x-dropdown-menu-item>--}}
                {{--                        @endslot--}}
                {{--                    </x-dropdown-menu>--}}
                {{--                @endcanany--}}


                {{--                @canany(['Lihat Menu Jurnal Umum', 'Lihat Menu Buku Besar', 'Lihat Menu Neraca Saldo'])--}}
                {{--                    <x-menu-sections>Jurnal</x-menu-sections>--}}
                {{--                @endcanany--}}
                {{--                @canany(['Lihat Menu Jurnal Umum', 'Lihat Menu Buku Besar', 'Lihat Menu Neraca Saldo'])--}}
                {{--                    <x-dropdown-menu :active="request()->segment(1) === 'journals'">--}}
                {{--                        @slot('parentIcon')--}}
                {{--                            <i class="ki-duotone ki-book-square fs-2">--}}
                {{--                                <span class="path1"></span>--}}
                {{--                                <span class="path2"></span>--}}
                {{--                                <span class="path3"></span>--}}
                {{--                            </i>--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuTitle')--}}
                {{--                            Penjurnalan--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuItem')--}}
                {{--                            @can('Lihat Menu Jurnal Umum')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'general-journal'"--}}
                {{--                                    href="{{ url('/journals/general-journal/') }}">--}}
                {{--                                    Jurnal Umum--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Neraca Saldo')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'trial-balance'"--}}
                {{--                                    href="{{ url('/journals/trial-balance/') }}">--}}
                {{--                                    Neraca Saldo--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Buku Besar')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'general-ledger'"--}}
                {{--                                    href="{{ url('/journals/general-ledger/') }}">--}}
                {{--                                    Buku Besar--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Buku Besar')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'financial-report'"--}}
                {{--                                    href="{{ url('/journals/financial-report/') }}">--}}
                {{--                                    Laporan Keuangan--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                        @endslot--}}
                {{--                    </x-dropdown-menu>--}}
                {{--                @endcanany--}}

                {{--                @canany(['Lihat Menu Penawaran', 'Lihat Menu PO', 'Lihat Menu BAA', 'Lihat Menu Fab', 'Lihat Menu BAA', 'Lihat Menu Bast', 'Lihat Menu Invoice', 'Lihat Menu Pengeluaran', 'Lihat Menu Invoice Pengeluaran', 'Lihat Menu Transaksi'])--}}
                {{--                    <x-menu-sections>Transaksi</x-menu-sections>--}}
                {{--                @endcanany--}}
                {{--                @can('Lihat Menu Transaksi')--}}
                {{--                    <x-single-menu-item :active="request()->segment(1) === 'transactions'"--}}
                {{--                                        href="{{ url('transactions') }}">--}}
                {{--                        @slot('parentIcon')--}}
                {{--                            <i class="ki-duotone ki-element-11 fs-2">--}}
                {{--                                <span class="path1"></span>--}}
                {{--                                <span class="path2"></span>--}}
                {{--                                <span class="path3"></span>--}}
                {{--                                <span class="path4"></span>--}}
                {{--                            </i>--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuTitle')--}}
                {{--                            Transaksi--}}
                {{--                        @endslot--}}
                {{--                    </x-single-menu-item>--}}
                {{--                @endcan--}}
                {{--                @canany(['Lihat Menu Penawaran', 'Lihat Menu PO', 'Lihat Menu BAA', 'Lihat Menu Fab', 'Lihat Menu BAA', 'Lihat Menu Bast', 'Lihat Menu Invoice'])--}}
                {{--                    <x-dropdown-menu :active="request()->segment(1) === 'income-transactions'">--}}
                {{--                        @slot('parentIcon')--}}
                {{--                            <i class="ki-duotone ki-dollar fs-2">--}}
                {{--                                <span class="path1"></span>--}}
                {{--                                <span class="path2"></span>--}}
                {{--                                <span class="path3"></span>--}}
                {{--                            </i>--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuTitle')--}}
                {{--                            Pendapatan--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuItem')--}}
                {{--                            @can('Lihat Menu Penawaran')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'offering-letters'"--}}
                {{--                                    href="{{ url('/income-transactions/offering-letters') }}">--}}
                {{--                                    Penawaran--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu PO')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'po'"--}}
                {{--                                    href="{{ url('/income-transactions/po') }}">--}}
                {{--                                    Purchase Order--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Fab')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'fab'"--}}
                {{--                                    href="{{ url('/income-transactions/fab') }}">--}}
                {{--                                    FAB--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu BAA')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'baa'"--}}
                {{--                                    href="{{ url('/income-transactions/baa') }}">--}}
                {{--                                    BAA--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Bast')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'bast'"--}}
                {{--                                    href="{{ url('/income-transactions/bast') }}">--}}
                {{--                                    BAST--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Invoice')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'invoice'"--}}
                {{--                                    href="{{ url('/income-transactions/invoice') }}">--}}
                {{--                                    Invoice--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                        @endslot--}}
                {{--                    </x-dropdown-menu>--}}
                {{--                @endcanany--}}
                {{--                @canany('Lihat Menu Profil Perusahaan')--}}
                {{--                    <x-menu-sections>Utilitas</x-menu-sections>--}}
                {{--                @endcanany--}}

                {{--                @canany(['Lihat Menu Profil Perusahaan', 'Lihat Menu Riwayat Aktifitas User'])--}}
                {{--                    <x-dropdown-menu :active="request()->segment(1) === 'utility'">--}}
                {{--                        @slot('parentIcon')--}}
                {{--                            <i class="ki-duotone ki-abstract-29 fs-2">--}}
                {{--                                <span class="path1"></span>--}}
                {{--                                <span class="path2"></span>--}}
                {{--                            </i>--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuTitle')--}}
                {{--                            Utilitas--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuItem')--}}
                {{--                            @can('Lihat Menu Profil Perusahaan')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'company-profile'"--}}
                {{--                                    href="{{ url('utility/company-profile') }}">--}}
                {{--                                    Profil Perusahaan--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Riwayat Aktifitas User')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'activity-log'"--}}
                {{--                                    href="{{ url('utility/activity-log') }}">--}}
                {{--                                    Riwayat Aktifitas--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                        @endslot--}}
                {{--                    </x-dropdown-menu>--}}
                {{--                @endcanany--}}
                {{--                @canany('Lihat Menu Payroll')--}}
                {{--                    <x-menu-sections>Manajemen Karyawan</x-menu-sections>--}}
                {{--                @endcanany--}}
                {{--                @can('Lihat Menu Payroll')--}}
                {{--                    <x-dropdown-menu :active="request()->is('payroll/*')">--}}
                {{--                        @slot('parentIcon')--}}
                {{--                            <i class="ki-duotone ki-profile-user fs-2">--}}
                {{--                                <span class="path1"></span>--}}
                {{--                                <span class="path2"></span>--}}
                {{--                                <span class="path3"></span>--}}
                {{--                                <span class="path4"></span>--}}
                {{--                            </i>--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuTitle')--}}
                {{--                            Payroll--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuItem')--}}
                {{--                            <x-dropdown-menu-item--}}
                {{--                                :active="request()->is('payroll/setting*')"--}}
                {{--                                href="{{ url('payroll/setting') }}">--}}
                {{--                                Pengaturan--}}
                {{--                            </x-dropdown-menu-item>--}}
                {{--                            <x-dropdown-menu-item--}}
                {{--                                :active="request()->segment(3) === 'employee'"--}}
                {{--                                href="{{ url('payroll/generate-payroll/employee') }}">--}}
                {{--                                Karyawan--}}
                {{--                            </x-dropdown-menu-item>--}}
                {{--                        @endslot--}}
                {{--                    </x-dropdown-menu>--}}
                {{--                @endcan--}}
                {{--                @canany(['Lihat Menu Data Karyawan', 'Lihat Menu Manajemen Cuti', 'Lihat Menu Permission', 'Lihat Menu Manajemen Cuti', 'Lihat Menu SP', 'Lihat Menu Kontrak Karyawan', 'Lihat Menu SK'])--}}
                {{--                    <x-dropdown-menu :active="request()->segment(1) === 'manage-users'">--}}
                {{--                        @slot('parentIcon')--}}
                {{--                            <i class="ki-duotone ki-profile-user fs-2">--}}
                {{--                                <span class="path1"></span>--}}
                {{--                                <span class="path2"></span>--}}
                {{--                                <span class="path3"></span>--}}
                {{--                                <span class="path4"></span>--}}
                {{--                            </i>--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuTitle')--}}
                {{--                            Manajemen Karyawan--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuItem')--}}
                {{--                            @can('Lihat Menu Data Karyawan')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'users'"--}}
                {{--                                    href="{{ url('manage-users/users') }}">--}}
                {{--                                    Data Karyawan--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Permission')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'permissions'"--}}
                {{--                                    href="{{ url('manage-users/permissions') }}">--}}
                {{--                                    Hak Akses--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Manajemen Cuti')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'leaves'"--}}
                {{--                                    href="{{ url('manage-users/leaves') }}">--}}
                {{--                                    Manajemen Cuti--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu SP')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'sp'"--}}
                {{--                                    href="{{ url('manage-users/sp') }}">--}}
                {{--                                    Surat Peringatan--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Kontrak Karyawan')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'contract-management'"--}}
                {{--                                    href="{{ url('manage-users/contract-management') }}">--}}
                {{--                                    Kontrak Karyawan--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu SK')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'sk'"--}}
                {{--                                    href="{{ url('manage-users/sk') }}">--}}
                {{--                                    SK Karyawan--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu SK')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'role-hierarchy'"--}}
                {{--                                    href="{{ url('manage-users/role-hierarchy') }}">--}}
                {{--                                    Struktur Jabatan--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                        @endslot--}}
                {{--                    </x-dropdown-menu>--}}
                {{--                @endcanany--}}
                {{--                @canany(['Lihat Menu Pengaturan Jadwal Libur','Lihat Menu Hari Libur Nasional', 'Lihat Menu Mesin Absen', 'Lihat Menu Riwayat Absensi'])--}}
                {{--                    <x-dropdown-menu :active="request()->segment(1) === 'adms'">--}}
                {{--                        @slot('parentIcon')--}}
                {{--                            <i class="bi bi-app-indicator"></i>--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuTitle')--}}
                {{--                            Data Absensi--}}
                {{--                        @endslot--}}
                {{--                        @slot('menuItem')--}}
                {{--                            @can('Lihat Menu Hari Libur Nasional')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'national-holiday'"--}}
                {{--                                    href="{{ url('adms/national-holiday') }}">--}}
                {{--                                    Libur Nasional--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Mesin Absen')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'fp-devices'"--}}
                {{--                                    href="{{ url('adms/fp-devices') }}">--}}
                {{--                                    Mesin Absen--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @canany(['Lihat Menu Jam Kerja Berdasarkan Jabatan', 'Lihat Menu Jam Kerja Berdasarkan Cabang', 'Lihat Menu Jam Kerja Jabatan Di Cabang'])--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'work-time-settings'"--}}
                {{--                                    href="{{ url('adms/work-time-settings') }}">--}}
                {{--                                    Pengaturan Jam Kerja--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcanany--}}
                {{--                            @can('Lihat Menu Pengaturan Jadwal Libur')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'employee-schedules'"--}}
                {{--                                    href="{{ url('adms/employee-schedules') }}">--}}
                {{--                                    Pengaturan Jadwal Libur--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                            @can('Lihat Menu Riwayat Absensi')--}}
                {{--                                <x-dropdown-menu-item--}}
                {{--                                    :active="request()->segment(2) === 'attendances-summary'"--}}
                {{--                                    href="{{ url('adms/attendances-summary') }}">--}}
                {{--                                    Riwayat Absensi--}}
                {{--                                </x-dropdown-menu-item>--}}
                {{--                            @endcan--}}
                {{--                        @endslot--}}
                {{--                    </x-dropdown-menu>--}}
                {{--                @endcanany--}}
            </div>
        </div>
    </div>
</div>
