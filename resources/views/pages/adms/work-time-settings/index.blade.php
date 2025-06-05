@extends('layouts.template')
@section('page-title', 'Pengaturan Jam Kerja Kantor Cabang')
@section('breadcrumbs', 'Data Absensi - Pengaturan Jam Kerja - Kantor Cabang')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header card-header-stretch">
                <h3 class="card-title">Pengaturan Jam Kerja (Berlaku untuk semua cabang)</h3>
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">
                                Jam Kerja Berdasarkan Jabatan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">
                                Jam Kerja Berdasarkan Jabatan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                        @include('pages.adms.work-time-settings.role.index')
                    </div>
                    <div class="tab-pane fade show" id="kt_tab_pane_2" role="tabpanel">
                        @include('pages.adms.work-time-settings.branch.index')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.select2.script')
    @include('components.toast')
@endsection
