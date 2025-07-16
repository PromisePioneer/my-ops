@extends('layouts.template')
@section('page-title', 'Pengaturan Jam Kerja')
@section('breadcrumbs', 'Data Absensi - Pengaturan Jam Kerja')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header card-header-stretch">
                <h3 class="card-title">Pengaturan Jam Kerja</h3>
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                        @can('Lihat Menu Jam Kerja Berdasarkan Jabatan')
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">
                                Jam Kerja Berdasarkan Jabatan
                            </a>
                        </li>
                        @endcan
                        @can('Lihat Menu Jam Kerja Berdasarkan Cabang')
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">
                                Jam Kerja Berdasarkan Kantor Cabang
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    @can('Lihat Menu Jam Kerja Berdasarkan Jabatan')
                        <div
                            class="{{ request()->user()->can('Lihat Menu Jam Kerja Berdasarkan Jabatan') ? 'tab-pane fade show active' : 'tab-pane fade show' }}"
                            id="kt_tab_pane_1" role="tabpanel">
                        @include('pages.adms.work-time-settings.role.index')
                    </div>
                    @endcan
                    @can('Lihat Menu Jam Kerja Berdasarkan Cabang')
                    <div class="tab-pane fade show" id="kt_tab_pane_2" role="tabpanel">
                        @include('pages.adms.work-time-settings.branch.index')
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @include('components.select2.script')
@endsection
